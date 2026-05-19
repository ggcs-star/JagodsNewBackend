<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray($request)
    {
      return [
           'site_email'                  => isset($this['site_email']) ? $this['site_email'] : '',
           'site_name'                   => isset($this['site_name']) ? $this['site_name'] : '',
           'site_phone_number'           => isset($this['site_phone_number']) ? $this['site_phone_number'] : '',
           'stripe_key'                  => isset($this['stripe_key']) ? $this['stripe_key'] : '',
           'stripe_secret'               => isset( $this['stripe_secret']) ?  $this['stripe_secret'] : '',
           'paystack_key'                => isset($this['paystack_key']) ? $this['paystack_key'] : '',
           'razorpay_key'                => isset($this['razorpay_key']) ? $this['razorpay_key'] : '',
           'razorpay_secret'             => isset($this['razorpay_secret']) ? $this['razorpay_secret'] : '',
           'google_map_api_key'          => isset($this['google_map_api_key']) ? $this['google_map_api_key'] : '',
           'free_delivery_radius'        => isset($this['free_delivery_radius']) ? $this['free_delivery_radius'] : '',
           'charge_per_kilo'             => isset($this['charge_per_kilo']) ? $this['charge_per_kilo'] : '',
           'basic_delivery_charge'       => isset($this['basic_delivery_charge']) ? $this['basic_delivery_charge'] : '',
           'currency_name'               => isset($this['currency_name']) ? $this['currency_name'] : '',
           'support_phone'               => isset($this['support_phone']) ? $this['support_phone'] : '',
           'currency_code'               => isset($this['currency_code']) ? $this['currency_code'] : '',
           'site_logo'                   => setting('site_logo') ? asset("images/app/" . setting('site_logo')) : '',
           'customer_app_name'           => isset($this['customer_app_name']) ? $this['customer_app_name'] : '',
           'customer_app_logo'           => setting('customer_app_logo') ? asset("images/app/" . setting('customer_app_logo')) : '',
           'customer_splash_screen_logo' => setting('customer_splash_screen_logo') ? asset("images/app/" . setting('customer_splash_screen_logo')) : '',
           'vendor_app_name'             => isset($this['vendor_app_name']) ? $this['vendor_app_name'] : '',
           'vendor_app_logo'             => setting('vendor_app_logo') ? asset("images/app/" . setting('vendor_app_logo')) : '',
           'vendor_splash_screen_logo'   => setting('vendor_splash_screen_logo') ? asset("images/app/" . setting('vendor_splash_screen_logo')) : '',
           'delivery_app_name'           => isset($this['delivery_app_name']) ? $this['delivery_app_name'] : '',
           'delivery_app_logo'           => setting('delivery_app_logo') ? asset("images/app/" . setting('delivery_app_logo')) : '',
           'delivery_splash_screen_logo' => setting('delivery_splash_screen_logo') ? asset("images/app/" . setting('delivery_splash_screen_logo')) : '',
           'cart_note'                  => isset($this['cart_note']) ? $this['cart_note'] : '',
            'voucher_note'               => isset($this['voucher_note']) ? $this['voucher_note'] : '',
            'packaging_charge'               => isset($this['packaging_charge']) ? $this['packaging_charge'] : '',
            'handling_charge'               => isset($this['handling_charge']) ? $this['handling_charge'] : '',
            'surge_fee'               => isset($this['surge_fee']) ? $this['surge_fee'] : '',
            'high_order_amount_limit'     => isset($this['high_order_amount_limit']) ? $this['high_order_amount_limit'] : '',
            'high_order_delivery_charge'  => isset($this['high_order_delivery_charge']) ? $this['high_order_delivery_charge'] : '',
       ];
    }
}
