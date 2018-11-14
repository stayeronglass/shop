function add(button, product_id) {
    button  =  $(button);
    button.attr("disabled", true);
    $.post( "/cart/add",{ product_id: product_id }, function( data ) {
        $('#cart').html(data.cart);
        button.attr("disabled", false);
    });
}