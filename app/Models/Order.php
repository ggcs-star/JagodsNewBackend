<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderTypeStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Shipu\Watchable\Traits\HasModelEvents;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use HasModelEvents, InteractsWithMedia;

    protected $table = 'orders';
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'total',
        'sub_total',
        'gst_amount',
        'delivery_charge',
        'status',
        'last_customer_notification_status',
        'last_delivery_notification_status',
        'payment_status',
        'paid_amount',
        'address',
        'payment_method',
        'mobile',
        'lat',
        'long',
        'misc',
        'invoice_id',
        'order_type',
        'discount',
        'search_fee',
        'handling_fee',
        'packaging_fee',
        'payment_order_id',
        'payment_id',
        'packing_charge',
        'platform_fee',
        'large_order_fee',
        'surge_fee',
        'tip_amount',
        'order_instructions',
        'user_device_id'

    ];
    protected $casts = [
        'status' => 'int',
        'order_type' => 'int',
        'payment_status' => 'int',
        'product_received' => 'int',
        'payment_method' => 'int',
        'user_id' => 'int',
        'restaurant_id' => 'int',
        'delivery_boy_id' => 'int',
    ];

    public function items()
    {
        return $this->hasMany(OrderLineItem::class)->with('menuItem', 'variation')->with('restaurant');
    }
    public function orderLines()
    {
        return $this->hasMany(OrderLineItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->with('media', 'roles');
    }

    public function delivery()
    {
        return $this->belongsTo(User::class, 'delivery_boy_id', 'id')->with('media', 'roles');
    }



    public function discount()
    {
        return $this->hasOne(Discount::class, 'order_id', 'id');
    }

    public function getOrderCodeAttribute()
    {
        return json_decode($this->misc)->order_code ?? 'ORD-000000';
    }

    public function getRemarksAttribute()
    {
        return json_decode($this->misc)->remarks;
    }

    private function onModelCreated()
    {
        $invoice_id = Str::uuid();

        $invoice = new Invoice;
        $invoice->id = $invoice_id;
        $invoice->meta = ['order_id' => $this->id, 'amount' => $this->total, 'user_id' => $this->user_id];
        $invoice->creator_type = User::class;
        $invoice->editor_type = User::class;
        $invoice->creator_id = 1;
        $invoice->editor_id = 1;
        $invoice->save();

        $this->invoice_id = $invoice_id;
        $this->save();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id')->with('media');
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Invoice::class);
    }
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function getGetOrderStatusAttribute()
    {
        return __('order_status.' . $this->status);
    }
    public function getGetOrderTypeAttribute()
    {

        return __('orders_type.' . $this->order_type);
    }

    public function getGetPaymentStatusAttribute()
    {
        return __('payment_status.' . $this->payment_status);
    }

    public function getGetPaymentMethodAttribute()
    {
        return __('payment_method.' . $this->payment_method);
    }

    public function getImageAttribute()
    {
        if (!empty($this->getFirstMediaUrl('orders'))) {
            return asset($this->getFirstMediaUrl('orders'));
        }
        return asset('backend/images/default/order.png');
    }

    public function getAttachmentAttribute()
    {
        return $this->getFirstMediaUrl('orders');
    }

    public function getAttachmentInfoAttribute()
    {
        return $this->getFirstMedia('orders');
    }

    public function scopeOrderowner($query)
    {
        $roleID = auth()->user()->myrole ?? 0;
        if ($roleID != UserRole::ADMIN) {
            if ($roleID == UserRole::RESTAURANTOWNER) {
                $restaurant_id = auth()->user()->restaurant->id ?? 0;
                $query->where('restaurant_id', $restaurant_id);
            } else if ($roleID == UserRole::DELIVERYBOY) {
                $query->where('delivery_boy_id', auth()->id());
            } else {
                $query->where('user_id', auth()->id());
            }
        }
    }

    public function getPaymentStatusNameAttribute()
    {
        if ($this->payment_status == PaymentStatus::PAID) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('payment_status.' . $this->payment_status) . '</span>';
        } elseif ($this->payment_status == PaymentStatus::UNPAID) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('payment_status.' . $this->payment_status) . '</span>';
        } else {
            return '<span class="db-table-badge text-black bg-gray-200">' . trans('payment_status.' . $this->payment_status) . '</span>';
        }
    }

  public function getStatusNameAttribute()
    {
        
        if ($this->status == OrderStatus::PAYMENT_PENDING) {
            return '<span class="db-table-badge text-orange-600 bg-orange-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::ACCEPT) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::PENDING) {
            return '<span class="db-table-badge text-yellow-600 bg-yellow-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::CANCEL) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::REJECT) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::PROCESS) {
            return '<span class="db-table-badge text-blue-600 bg-blue-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::ON_THE_WAY) {
            return '<span class="db-table-badge text-yellow-600 bg-yellow-100">' . trans('order_status.' . $this->status) . '</span>';
        } elseif ($this->status == OrderStatus::COMPLETED) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('order_status.' . $this->status) . '</span>';
        } else {
            return '<span class="db-table-badge text-black bg-gray-200">' . trans('order_status.' . $this->status) . '</span>';
        }
    }

    public function getGetOrderTypeNameAttribute()
    {
        if ($this->order_type == OrderTypeStatus::DELIVERY) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('orders_type.' . $this->order_type) . '</span>';
        } elseif ($this->order_type == OrderTypeStatus::PICKUP) {
            return '<span class="db-table-badge text-yellow-600 bg-yellow-100">' . trans('orders_type.' . $this->order_type) . '</span>';
        } elseif ($this->order_type == OrderTypeStatus::TABLE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('orders_type.' . $this->order_type) . '</span>';
        } else {
            return '<span class="db-table-badge text-black bg-gray-200">' . trans('orders_type.' . $this->order_type) . '</span>';
        }
    }

    public function device()
{
    return $this->belongsTo(UserDevice::class, 'user_device_id');
}
}
