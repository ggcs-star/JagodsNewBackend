<?php

namespace App\Http\Services;

use Google\Client;
use App\Models\User;
use App\Enums\NotificationType;
use GuzzleHttp\Client as guzzle;
use App\Notifications\OrderUpdated;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;


class PushNotificationService
{

    public function sendPushNotification($notification, $topicName = null)
{
    Log::info('PushNotification: Start sending notification', [
        'notification_id' => $notification->id ?? null,
        'type'            => $notification->type,
        'topicName'       => $topicName,
    ]);

    try {
        $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

        Log::info('PushNotification: FCM URL prepared', ['url' => $url]);

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            throw new \Exception('FCM access token not generated');
        }

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json'
        ];

        Log::info('PushNotification: FCM headers prepared');

        $messageData = [
            'notification' => [
                'title' => $notification->title,
                'body'  => $notification->description,
            ],
            'data' => [
                'title'         => $notification->title,
                'body'          => $notification->description,
                'sound'         => 'default',
                'image'         => $notification->image ?? null,
                'topicName'     => $topicName,
                'restaurant_id' => $notification->restaurant_id ? (string) $notification->restaurant_id : null,
                'type'          => 'restaurant_update',
                'screen'        => 'restaurant_details'
            ],
            'webpush' => [
                'headers' => [
                    'Urgency' => 'high'
                ]
            ]
        ];

       Log::info('AppCustomerNotification: Payload prepared', [
            'payload' => $messageData,
        ]);

        
        if ($notification->type == NotificationType::SINGLE) {

            Log::info('PushNotification: SINGLE notification flow');

            $user = User::find($notification->customer_id);

            if (!$user) {
                throw new \Exception('User not found: ' . $notification->customer_id);
            }

            
            $token = $user->device_token ?: $user->web_token;

            Log::info('PushNotification: Token resolved', [
                'customer_id' => $notification->customer_id,
                'token_type'  => $user->device_token ? 'device_token' : ($user->web_token ? 'web_token' : 'none'),
            ]);

            if (!$token) {
                throw new \Exception('No FCM token found for user ID ' . $notification->customer_id);
            }

            $messageData['token'] = $token;
            $message = ['message' => $messageData];

            $result = $this->sendRequest($url, $headers, $message);

            Log::info('PushNotification: SINGLE notification sent', [
                'response' => $result,
            ]);

            return $result;
        }

        
        Log::info('PushNotification: ALL notification flow');

        $users = User::whereNotNull('device_token')
            ->orWhereNotNull('web_token')
            ->get(['id', 'device_token', 'web_token']);

        Log::info('PushNotification: Total users with tokens', [
            'count' => $users->count(),
        ]);

        if ($users->isEmpty()) {
            throw new \Exception('No users with FCM tokens found');
        }

        foreach ($users as $index => $user) {

            $token = $user->device_token ?: $user->web_token;
            if (!$token) {
                continue;
            }

            Log::info('PushNotification: Sending to user', [
                'index'   => $index,
                'user_id' => $user->id,
            ]);

            $messageData['token'] = $token;
            $message = ['message' => $messageData];

            $result = $this->sendRequest($url, $headers, $message);

            Log::info('PushNotification: FCM response received', [
                'user_id'  => $user->id,
                'response' => $result,
            ]);
        }

        Log::info('PushNotification: ALL notifications completed');
        return true;

    } catch (\Exception $exception) {

        Log::error('PushNotification: Error while sending notification', [
            'error'           => $exception->getMessage(),
            'notification_id' => $notification->id ?? null,
        ]);
    }
}


    private function sendRequest($url, $headers, $message)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);
        curl_close($ch);

        return $result ?: false;
    }


  public function fcmSubscribe($request)
{
    Log::info('FCM Subscribe started');

    $deviceToken = $request->device_token;
    $topic = env('FCM_TOPIC') . '_' . str_replace(['@', '.', '+'], ['_', '_', ''], $request->topic);

    Log::info('FCM Subscribe data', [
        'device_token' => $deviceToken,
        'topic' => $topic,
    ]);

    $headers = array(
        'Authorization: key=' . env('FCM_SECRET_KEY'),
        'Content-Type: application/json'
    );

    Log::info('Calling FCM Global Subscribe');
    $this->fcmGlobalSubscribe($request);

    try {
        Log::info('FCM CURL init started');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://iid.googleapis.com/iid/v1/$deviceToken/rel/topics/$topic");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        Log::info('FCM CURL response', [
            'response' => $result,
        ]);

        curl_close($ch);

        return response()->json([
            'status' => 200,
            'message' => 'Subscribed',
        ], 200);

    } catch (\Exception $exception) {

        Log::error('FCM Subscribe exception', [
            'error' => $exception->getMessage(),
        ]);

        return response()->json([
            'status'  => 401,
            'message' => $exception,
        ], 401);
    }
}


    public function fcmGlobalSubscribe($request)
    {
        $deviceToken = $request->device_token;
        $topic = env('FCM_TOPIC');

        $headers = array(
            'Authorization: key=' . env('FCM_SECRET_KEY'),
            'Content-Type: application/json'
        );

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://iid.googleapis.com/iid/v1/$deviceToken/rel/topics/$topic");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            return response()->json([
                'status' => 200,
                'message' => 'Global Subscription',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 401,
                'message' => $exception,
            ], 401);
        }
    }


    public function fcmUnsubscribe($request)
    {
        $request->validate([
            'device_token' => 'required',
            'topic' => 'nullable',
        ]);

        $deviceToken = $request->token;

        $headers = array(
            'Authorization: key=' . env('FCM_SECRET_KEY'),
            'Content-Type: application/json'
        );

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://iid.googleapis.com/v1/web/iid/$deviceToken");
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);

            return response()->json([
                'status' => 200,
                'message' => 'Unsubscribed',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 401,
                'message' => $exception,
            ], 401);
        }
    }

    public function sendWebNotification($order)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';
        $FcmWabToken = User::where(['id' => auth()->user()->id])->whereNotNull('web_token')->pluck('web_token')->toArray();
        $message = [
            "message" => [
                'token' => $FcmWabToken[0],
                "notification" => [
                    "body" => 'A new order has been placed at ' . ucfirst($order->restaurant->name) . ' The order amount is ' . $order->total,
                    "title" => "New Order #" . $order->id,
                    'sound'     => 'default', 
                    'icon'      => public_path('images/fav.png'),
                ]
            ]
        ];

        $encodedData = json_encode($message);
        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json'
        ];


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        
        curl_close($ch);
       
        return true;
    }

    public  function NotificationReservationRestaurant($reservation, $user, $type)
    {
        try {

            $FcmWabToken = User::where(['id' => $user->id])->whereNotNull('web_token')->pluck('web_token')->toArray();

            $message = [
                "message" => [
                    "token" => $FcmWabToken[0],
                    "notification" => [
                        'title' => 'Hello, ' . $user->name,
                        'body'  => "A New Reservation #" . $reservation->id . " has been created by " . $reservation->user->name,
                    ]
                ]
            ];

            $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

            $headers = [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
        }
    }

    public  function NotificationReservationCustomer($reservation, $user, $type)
    {
        try {

            $FcmWabToken = User::where(['id' => $user->id])->whereNotNull('web_token')->pluck('web_token')->toArray();

            $message = [
                "message" => [
                    "token" => $FcmWabToken[0],
                    "notification" => [
                        'title' => 'Hello ' . $user->name,
                        'body'  => "Reservation  #" . $reservation->id . " has been created By " . $reservation->restaurant->name,
                    ]
                ]
            ];

            $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

            $headers = [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
        }
    }

    public  function NotificationForRestaurant($order, $user, $type)
    {
        try {
            $FcmWabToken = User::where(['id' => $user->id])->whereNotNull('web_token')->pluck('web_token')->toArray();
            $message = [
                "message" => [
                    "token" => $FcmWabToken[0],
                    "notification" => [
                        'title' => 'Hello ' . $user->name,
                        'body'  => "A new order #" . $order->id . " has been created by " . $order->user->name,
                    ]
                ]
            ];

            $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

            $headers = [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            Log::error("FCM Error: " . $exception->getMessage());
        }
    }

    public  function NotificationForCustomer($order, $user, $type)
    {
        try {
            $FcmWabToken = User::where(['id' => $user->id])->whereNotNull('web_token')->pluck('web_token')->toArray();
            $message = [
                "message" => [
                    "token" => $FcmWabToken[0],
                    "notification" => [
                        'title' => 'Order Placed Successfully 🎉',
                        'body'  => "Hi " .$user->name.", thanks for ordering! Your food for order is now being prepared.",
                    ]
                ]
            ]; 

            $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

            $headers = [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            Log::error("FCM Error: " . $exception->getMessage());
        }
    }

    public  function NotificationForAppRestaurant($order, $user, $type)
    {
        try {
            $FcmWabToken = User::where(['id' => $user->id])->whereNotNull('device_token')->pluck('device_token')->toArray();

            $message = [
                "data" => [
                    "token" => $FcmWabToken[0],
                    "data" => [
                        'title' => 'Hello ' . $user->name,
                        'body'  => "A new order #" . $order->id . " has been created by " . $order->user->name,
                    ],
                ]
            ];
            

            $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

            $headers = [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            return $result;
        } catch (\Exception $exception) {
            Log::error("FCM Error: " . $exception->getMessage());
        }
    }

 public function NotificationForAppCustomer($order, $user, $type)
{
    Log::info('AppCustomerNotification: Start', [
        'order_id'   => $order->id ?? null,
        'order_code'=> $order->order_code ?? null,
        'user_id'    => $user->id ?? null,
        'type'       => $type,
    ]);

    try {

        
        $deviceToken = User::where('id', $user->id)
            ->whereNotNull('device_token')
            ->value('device_token');

        Log::info('AppCustomerNotification: Device token fetched', [
            'token_found' => (bool) $deviceToken,
        ]);

        if (!$deviceToken) {
            throw new \Exception('Device token not found for user ID ' . $user->id);
        }

        
              $message = [
            "message" => [
                "token" => $deviceToken,
                "notification" => [
                    'title' => 'Order Placed Successfully 🎉',
                    'body'  => "Hi ".$user->name.", thanks for ordering! Your food for order is now being prepared.",
                ],
                "data" => [
                     "title" => "Order Placed Successfully 🎉",
                      "body"  => "Hi ".$user->name.", thanks for ordering!",
                    "order_id" => (string) $order->id,
                    "type"     => "order_update",
                    "screen"   => "order_details"
                ]
            ]
        ];

        Log::info('AppCustomerNotification: Payload prepared', [
            'payload' => $message,
        ]);

        $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === false) {
            Log::error('AppCustomerNotification: CURL failed', [
                'error' => curl_error($ch),
            ]);
        } else {
            Log::info('AppCustomerNotification: FCM response received', [
                'response' => $result,
            ]);
        }

        curl_close($ch);

        return $result;

    } catch (\Exception $exception) {

        Log::error('AppCustomerNotification: Error', [
            'error'   => $exception->getMessage(),
            'user_id'=> $user->id ?? null,
        ]);
    }
}




   public function sendNotificationOrderUpdate($order, $user, $type)
{
    Log::info('OrderUpdateNotification: Start', [
        'order_id' => $order->id,
        'user_id'  => $user->id,
        'type'     => $type,
    ]);

    $tokens = User::where('id', $user->id)
        ->whereNotNull('device_token')
        ->pluck('device_token')
        ->toArray();

    if (empty($tokens)) {
        Log::warning('OrderUpdateNotification: No device token');
        return;
    }

    foreach ($tokens as $token) {
        $this->sendSingleFcmV1(
            $order,
            $user,
            $token,
            'Order Update',
            "Order #{$order->order_code} updated"
        );
    }

    Log::info('OrderUpdateNotification: Completed');
}

public function sendOrderStatusNotificationToCustomer($order, $user, $status)
{
    Log::info('OrderStatusNotification: Start', [
        'order_id' => $order->id,
        'user_id'  => $user->id,
        'status'   => $status
    ]);

    $tokens = User::where('id', $user->id)
        ->whereNotNull('device_token')
        ->pluck('device_token')
        ->toArray();

    if (empty($tokens)) {
        Log::warning('OrderStatusNotification: No device token', [
            'user_id' => $user->id
        ]);
        return;
    }

    $messages = [
        15 => [
            'title' => 'Food is Being Prepared 👨‍🍳',
            'body'  => 'The restaurant has started preparing your order.'
        ],
        17 => [
            'title' => 'Order on the Way 🚴',
            'body'  => 'Your order is on the way. Get ready to enjoy!'
        ],
        20 => [
            'title' => 'Enjoy Your Meal 😋',
            'body'  => 'Your order is now completed. Hope every bite was delicious!'
        ]
    ];

    $title = $messages[$status]['title'] ?? 'Order Update';
    $body  = $messages[$status]['body'] ?? 'Your order status has been updated.';

    foreach ($tokens as $token) {
        $this->sendSingleFcmV1(
            $order,
            $user,
            $token,
            $title,
            $body
        );
    }

    Log::info('OrderStatusNotification: Completed', [
        'order_id' => $order->id,
        'user_id'  => $user->id,
        'status'   => $status
    ]);
}


private function sendSingleFcmV1($order, $user, $token, $title, $body)
{
    $message = [
        "message" => [
            "token" => $token,
            "notification" => [
                "title" => $title,
                "body"  => $body,
            ],
            "data" => [
                "order_id" => (string)$order->id,
                "status"   => (string)$order->status,
                "type"     => "order_update",
                "screen"   => "order_details",
                 "title" => $title,
                "body"  => $body,
            ]
        ]
    ];

    Log::info('FCM Payload', [
        'token' => $token,
        'payload' => $message
    ]);

    $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

    $headers = [
        'Authorization: Bearer ' . $this->getAccessToken(),
        'Content-Type: application/json'
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POSTFIELDS => json_encode($message)
    ]);

    $result = curl_exec($ch);

    if ($result === false) {
        Log::error('FCM v1 CURL failed', [
            'error' => curl_error($ch),
            'token' => $token,
        ]);
    } else {
        Log::info('FCM v1 response', [
            'response' => $result,
            'token' => $token,
        ]);
    }

    curl_close($ch);
}



 public function getAccessToken()
{
    try {
        $client = new \Google\Client();

        $client->setAuthConfig(
            storage_path('app/firebase/service-account-file.json')
        );

        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $tokenData = $client->fetchAccessTokenWithAssertion();

        if (!isset($tokenData['access_token'])) {
            \Log::error('FCM OAuth token not generated', [
                'response' => $tokenData
            ]);
            return null;
        }

        return $tokenData['access_token'];

    } catch (\Exception $e) {
        \Log::error('FCM getAccessToken failed', [
            'error' => $e->getMessage()
        ]);
        return null;
    }
}


    public function sendWebNotificationn($order)
    {
        $projectId = 'new-project-3e70e';
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
        $FcmWebToken = User::where(['id' => auth()->user()->id])->whereNotNull('web_token')->pluck('web_token')->first();
        if (!$FcmWebToken) {
            Log::error('No web token found for user.');
            return false;
        }

        $message = [
            "message" => [
                'token' => $FcmWebToken,
                "notification" => [
                    "body" => 'A new order has been placed at ' . ucfirst($order->restaurant->name) . '. The order amount is ' . $order->total,
                    "title" => "New Order #" . $order->id,
                ]
            ]
        ];

        $encodedData = json_encode($message);
        $headers = [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json'
        ];

        try {
            $client = new guzzle();
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => $encodedData,
            ]);

            if ($response->getStatusCode() != 200) {
                Log::error('Failed to send notification. Response: ' . $response->getBody());
                return false;
            }
        } catch (RequestException $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return false;
        }

        Log::info('Notification sent successfully.');
        return true;
    }
    
   public function sendNewOrderNotificationToDeliveryBoy($order, $deliveryBoy)
{
    try {
        Log::info('DeliveryBoyNewOrderNotification: Start', [
            'order_id'        => $order->id,
            'delivery_boy_id' => $deliveryBoy->id,
        ]);

        
        $token = $deliveryBoy->device_token ?: $deliveryBoy->web_token;

        if (!$token) {
            Log::warning('DeliveryBoyNewOrderNotification: No token found', [
                'delivery_boy_id' => $deliveryBoy->id,
            ]);
            return;
        }

        $url = 'https://fcm.googleapis.com/v1/projects/' . setting('projectId') . '/messages:send';

        $headers = [
            'Authorization: Bearer ' . $this->getAccessToken(),
            'Content-Type: application/json'
        ];

        $message = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => 'New Order for Pickup 📦',
                    'body'  => 'Order #' . $order->order_code . ' is ready for pickup. Please head to the restaurant.',
                ],
                'data' => [
                    'order_id' => (string) $order->id,
                    'status'   => (string) $order->status,
                    'type'     => 'new_order',
                ],
            ]
        ];

        $response = $this->sendRequest($url, $headers, $message);

        Log::info('DeliveryBoyNewOrderNotification: Sent', [
            'order_id' => $order->id,
            'response' => $response,
        ]);

    } catch (\Exception $e) {
        Log::error('DeliveryBoyNewOrderNotification: Error', [
            'error'    => $e->getMessage(),
            'order_id' => $order->id,
        ]);
    }
}


}
