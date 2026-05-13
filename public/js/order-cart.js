window.addEventListener('showCartQty', event => {
    $('.cartCount').text(event.detail.qty);
});

window.addEventListener('openFormModalCart', event => {
    setTimeout(function() {
        $('#cartModal').modal('show');
    }, 520);
});

window.addEventListener('closeFormModalCart', event => {

    $('#cartModal').modal('hide')
});
