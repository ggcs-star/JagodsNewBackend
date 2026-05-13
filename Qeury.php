CREATE TABLE carts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NULL,

    restaurant_id BIGINT UNSIGNED NOT NULL,

    subtotal DECIMAL(10,2) DEFAULT 0,

    discount DECIMAL(10,2) DEFAULT 0,

    delivery_charge DECIMAL(10,2) DEFAULT 0,

    total DECIMAL(10,2) DEFAULT 0,

    created_at TIMESTAMP NULL DEFAULT NULL,

    updated_at TIMESTAMP NULL DEFAULT NULL
);





CREATE TABLE cart_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    cart_id BIGINT UNSIGNED NOT NULL,

    menu_item_id BIGINT UNSIGNED NOT NULL,

    variation_id BIGINT UNSIGNED NULL,

    options JSON NULL,

    instructions TEXT NULL,

    quantity INT DEFAULT 1,

    price DECIMAL(10,2) NOT NULL,

    total_price DECIMAL(10,2) NOT NULL,

    created_at TIMESTAMP NULL DEFAULT NULL,

    updated_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_cart_items_cart
        FOREIGN KEY (cart_id)
        REFERENCES carts(id)
        ON DELETE CASCADE
);





ALTER TABLE carts
ADD coupon_id BIGINT UNSIGNED NULL AFTER user_id,

ADD gst_amount DECIMAL(10,2) DEFAULT 0 AFTER discount;



ALTER TABLE carts
ADD latitude VARCHAR(50) NULL AFTER order_type,
ADD longitude VARCHAR(50) NULL AFTER latitude;


ALTER TABLE carts
ADD order_type TINYINT(1) NOT NULL DEFAULT 1
AFTER coupon_id;

