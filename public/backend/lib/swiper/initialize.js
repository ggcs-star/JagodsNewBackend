const bannerSwiper = new Swiper(".banner-swiper", {
    speed: 1000,
    slidesPerView: 1,
    loop: true,  
    autoplay: {
        delay: 2500,
    },
    pagination: {
        el: ".banner-swiper-pagination",
        clickable: true,
        renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + "</span>";
        },
    },
    navigation: {
        nextEl: ".banner-swiper-button-next",
        prevEl: ".banner-swiper-button-prev",
    },
})


const menuSwiper = new Swiper(".menu-swiper", {
    breakpoints: {
        0: {
            slidesPerView: "auto",
            spaceBetween: 10,
            loop: true,
        },
        900: {
            slidesPerView: "auto",
            spaceBetween: 20,
            loop: true,
        },
        1280: {
            slidesPerView: 8,
            spaceBetween: 20,
        }
    }
})


const posMenuSwiper = new Swiper(".pos-menu-swiper", {
    slidesPerView: "auto",
    spaceBetween: 12,
    speed: 1000,
    loop: true, 
})

const cartSwiper = new Swiper(".cart-swiper", {
    slidesPerView: "auto",
    spaceBetween: 16,
    speed: 1000,
    loop: true,  
})

const sizeSwiper = new Swiper(".size-swiper", {
    slidesPerView: "auto",
    spaceBetween: 16,
    speed: 1000,
    loop: false,  
})

const extraSwiper = new Swiper(".extra-swiper", {
    slidesPerView: "auto",
    spaceBetween: 16,
    speed: 1000,
    loop: false,  
})

const addonSwiper = new Swiper(".addon-swiper", {
    slidesPerView: "auto",
    spaceBetween: 16,
    speed: 1000,
    loop: false,  
})

const daySwiper = new Swiper(".day-swiper", {
    slidesPerView: "auto",
    spaceBetween: 12,
    speed: 1000,
    loop: false,  
})

const timeSwiper = new Swiper(".time-swiper", {
    slidesPerView: "auto",
    spaceBetween: 12,
    speed: 1000,
    loop: false,  
})

const branchSwiper = new Swiper(".branch-swiper", {
    slidesPerView: "auto",
    spaceBetween: 12,
    speed: 1000,
    loop: false,  
})
