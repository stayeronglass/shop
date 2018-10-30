function add(button, product_id) {
    button  =  $(button);
    button.attr("disabled", true);
    $.post( "/cart/add",{ product_id: product_id }, function( data ) {
        $('#full_cart').html(data.full_cart_message);
        console.log($('#full_cart'));
        $('#short_cart').html(data.short_cart_message);
        button.attr("disabled", false);
    });
}