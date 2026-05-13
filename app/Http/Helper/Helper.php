<?php

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ThemeSettings;
use Carbon\Carbon;

if (!function_exists('getCountry')) {
    function getCountry(Request $request){
        $ip = $request->ip();
        $ip ='103.72.212.130';
        $response = Http::get("http://ip-api.com/json/{$ip}");
        if ($response->successful()) {
            $location = $response->json();
            $country = $location['country'] ?? 'Unknown';
            return $country;
        } else {
            return 'Unknown';
        }
    }
}

if (!function_exists('pluck')) {
    function pluck($array, $value, $key = null)
    {
        $returnArray = [];
        if (count($array)) {
            foreach ($array as $item) {
                if ($key != null) {
                    $returnArray[$item->$key] = strtolower($value) == 'obj' ? $item : $item->$value;
                } else {
                    if ($value == 'obj') {
                        $returnArray[] = $item;
                    } else {
                        $returnArray[] = $item->$value;
                    }
                }
            }
        }

        return $returnArray;
    }
}


if (!function_exists('getCurrentVersion')) {
     function getCurrentVersion()
     {
         $version = File::get(public_path() . '/version.txt');
         return $version;
     }
}

if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}


if (!function_exists('orderAddress')) {
    function orderAddress($string)
    {
        if(isJson($string)){
            $address = json_decode($string,true);
            return isset($address['apartment']) ? 'Apartment : '.$address['apartment'].','.$address['address'] : $address['address'];
        }
        return $string;
    }
}


if (!function_exists('currencyFormat')) {
    function currencyFormat($currency)
    {
        return Setting::get('currency_code') . number_format((float)$currency, 2);
    }
}

if (!function_exists('currencyName')) {
    function currencyName($currency)
    {
        return Setting::get('currency_name') . ' ' . $currency;
    }
}

if (!function_exists('currencyFormatWithName')) {
    function currencyFormatWithName($currency)
    {
        return number_format($currency, 2) . ' ' . Setting::get('currency_name');
    }
}

if (!function_exists('transactionCurrencyFormat')) {
    function transactionCurrencyFormat($transaction)
    {
        $amount = '+ ' . $transaction->amount;
        if ($transaction->source_balance_id == auth()->user()->balance_id) {
            $amount = '- ' . $transaction->amount;
        }
        return $amount;
    }
}

if (!function_exists('settingLogo')) {
    function settingLogo()
    {
        return asset("images/" . setting('site_logo'));
    }
}

if (!function_exists('food_date_format')) {
    function food_date_format($date)
    {
        return \Carbon\Carbon::parse($date)->format('d M Y h:i A');
    }
}

if (!function_exists('food_date_format_with_day')) {
    function food_date_format_with_day($date)
    {
        return \Carbon\Carbon::parse($date)->format('l, d M Y h:i A');
    }
}

/**
 * Get domain (host without sub-domain)
 *
 * @param null $url
 * @return string
 */
function getDomain($url = null)
{
    if (!empty($url)) {
        $host = parse_url($url, PHP_URL_HOST);
    } else {
        $host = getHost();
    }

    $tmp = explode('.', $host);
    if (count($tmp) > 2) {
        $itemsToKeep = count($tmp) - 2;
        $tlds        = config('tlds');
        if (isset($tmp[$itemsToKeep]) && isset($tlds[$tmp[$itemsToKeep]])) {
            $itemsToKeep = $itemsToKeep - 1;
        }
        for ($i = 0; $i < $itemsToKeep; $i++) {
            \Illuminate\Support\Arr::forget($tmp, $i);
        }
        $domain = implode('.', $tmp);
    } else {
        $domain = @implode('.', $tmp);
    }

    return $domain;
}

/**
 * Get host (domain with sub-domain)
 *
 * @param null $url
 * @return array|mixed|string
 */
function getHost($url = null)
{
    if (!empty($url)) {
        $host = parse_url($url, PHP_URL_HOST);
    } else {
        $host = (trim(request()->server('HTTP_HOST')) != '') ? request()->server('HTTP_HOST') : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    }

    if ($host == '') {
        $host = parse_url(url()->current(), PHP_URL_HOST);
    }

    return $host;
}

function isValidJson($string)
{
    try {
        json_decode($string);
    } catch (\Exception $e) {
        return false;
    }

    return (json_last_error() == JSON_ERROR_NONE);
}

function generateUsername($email)
{
    $emails = explode('@', $email);
    return $emails[0] . mt_rand();
}

if (!function_exists('domain')) {
    function domain($input)
    {
        $input = trim($input, '/');
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
        $urlParts = parse_url($input);

        $link = '';
        if (isset($urlParts['port'])) {
            $link .= ':' . $urlParts['port'];
        }

        if (isset($urlParts['path'])) {
            $link .= $urlParts['path'];
        }

        return preg_replace('/^www\./', '', ($urlParts['host'] . $link));
    }
}

if (!function_exists('view_button')) {
    function view_button($route, $permission = null)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action view modal-btn">
                <i class="fa-solid fa-eye"></i>
                <span class="db-tooltip">view</span>
                </a>';
        }
        return '';
    }
}
if (!function_exists('permission_button')) {
    function permission_button($route, $permission = null)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action view modal-btn">
                      <i class="fas fa-plus"></i>
                      <span class="db-tooltip">permission</span>
                    </a>';
        }
        return '';
    }
}
if (!function_exists('modify_button')) {
    function modify_button($route, $permission = null)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action view modal-btn">
                      <i class="far fa-list-alt"></i>
                      <span class="db-tooltip">add variation/ option</span>
                    </a>';
        }
        return '';
    }
}
if (!function_exists('accept_button')) {
    function accept_button($route)
    {
        return '<a href="' . $route . '" class="db-table-action db-btn-fill purple p-0 me-2">
        <i class="fa-solid fa-check"></i>
        <span class="db-tooltip">Accept</span>
        </a>';
    }
}
if (!function_exists('reject_button')) {
    function reject_button($route)
    {
        return '<a href="' . $route . '" class="db-table-action db-btn-fill p-0 red">
        <i class="fa-solid fa-ban"></i>
        <span class="db-tooltip">Reject</span>
        </a>';
    }
}
if (!function_exists('edit_button')) {
    function edit_button($route, $permission = null)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action edit modal-btn">
                      <i class="fa-solid fa-pencil"></i>
                      <span class="db-tooltip">edit</span>
                    </a>';
        }
        return '';
    }
}
if (!function_exists('delete_button')) {
    function delete_button($route, $permission)
    {
        if (auth()->user()->can($permission)) {
            return '<form class="inline-block" action="' . $route . '" method="POST">' . method_field('DELETE') . csrf_field() .
                '<button class="db-table-action delete modal-btn"> <i class="fa-solid fa-trash-can"></i> <span class="db-tooltip">delete</span></button></form>';
        }
    }
}
if (!function_exists('delivery_button')) {
    function delivery_button($route, $permission)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action edit modal-btn">
                        <i class="fa-solid fa-bicycle"></i>
                        <span class="db-tooltip">delivery</span>
                    </a>';
        }
        return '';
    }
}
if (!function_exists('action_button')) {
    function action_button($array)
    {
        if (isset($array['view']['permission']) || isset($array['edit']['permission']) || isset($array['delete']['permission']) || isset($array['delivery']['permission'])) {
            $retAction = '';
            if (isset($array['permission']['route'])) {
                $retAction .= permission_button($array['permission']['route'], $array['permission']['permission']);
            }
            if (isset($array['modify']['route'])) {
                $retAction .= modify_button($array['modify']['route'], $array['modify']['permission']);
            }
            if (isset($array['view']['route'])) {
                $retAction .= view_button($array['view']['route'], $array['view']['permission']);
            }
            if (isset($array['edit']['route'])) {
                $retAction .= edit_button($array['edit']['route'], $array['edit']['permission']);
            }
            if (isset($array['delete']['route'])) {
                $retAction .= delete_button($array['delete']['route'], $array['delete']['permission']);
            }
            if (isset($array['delivery']['route'])) {
                $retAction .= delivery_button($array['delivery']['route'], $array['delivery']['permission']);
            }
            return $retAction;
        } else if ( isset($array['accept']) || isset($array['reject']) ) {
            $retAction = '';
            if (isset($array['accept']['route'])) {
                $retAction .= accept_button($array['accept']['route']);
            }
            if (isset($array['reject']['route'])) {
                $retAction .= reject_button($array['reject']['route']);
            }
            return $retAction;
        }
        return '';
    }
}

if (!function_exists('add_http')) {
    function add_http($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
}

if (!function_exists('add_button')) {
    function add_button($route, $permission = null, $label = null,$langFile = null, $bgColor = 'btn-primary')
    {
        if (auth()->user()->can($permission)) {
            return  '<a href="' . $route . '" class="db-btn h-[37px] text-white bg-primary">
            <i class="fa-solid fa-circle-plus"></i>
             <span>' . trans($langFile.'.' . $label . '') . '</span>
             </a>';
        }
        return '';
    }
}
if (!function_exists('view_button')) {
    function view_button($route, $permission = null)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action view modal-btn">
                <i class="fa-solid fa-eye"></i>
                <span class="db-tooltip">view</span>
                </a>';
        }
        return '';
    }
}
if (!function_exists('edit_button')) {
    function edit_button($route, $permission = null)
    {
        if (auth()->user()->can($permission)) {
            return '<a href="' . $route . '" class="db-table-action edit modal-btn">
                      <i class="fa-solid fa-pencil"></i>
                      <span class="db-tooltip">edit</span>
                    </a>';
        }
        return '';
    }
}
if (!function_exists('delete_button')) {
    function delete_button($route, $permission)
    {
        if (auth()->user()->can($permission)) {
            return '<form class="inline-block" action="' . $route . '" method="POST">' . method_field('DELETE') . csrf_field() .
                '<button class="db-table-action delete modal-btn"> <i class="fa-solid fa-trash-can"></i> <span class="db-tooltip">delete</span></button></form>';
        }
    }
}
if (!function_exists('action_button')) {
    function action_button($array)
    {
        if (isset($array['view']['permission']) || isset($array['edit']['permission']) || isset($array['delete']['permission'])) {
            $retAction = '';
            if (isset($array['view']['route'])) {
                $retAction .= view_button($array['view']['route'], $array['view']['permission']);
            }
            if (isset($array['edit']['route'])) {
                $retAction .= edit_button($array['edit']['route'], $array['edit']['permission']);
            }
            if (isset($array['delete']['route'])) {
                $retAction .= delete_button($array['delete']['route'], $array['delete']['permission']);
            }
            return $retAction;
        }
        return '';
    }
}


if (!function_exists('themeSetting')) {
    function themeSetting($key)
    {
        return ThemeSettings::where(['key' => $key])->first();
    }
}

if (!function_exists('greeting')) {
    function greeting()
    {
        $time = new Carbon();
        $greet = '';
        switch ($time->hour) {
            case $time->hour < 12 :
                $greet = __('dashboard.good_morning');
                break;
            case $time->hour >= 12 && $time->hour <= 17:
                $greet = __('dashboard.good_afternoon');
                break;
            case $time->hour >= 17 && $time->hour <= 24:
                $greet = __('dashboard.good_evening');
                break;
            
            default:
                $greet = __('dashboard.good_morning');
                break;
        }
        return $greet;
    }
}