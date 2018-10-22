function add(button, product_id) {
    button  =  $(button);
    button.attr("disabled", true);
    $.post( "/cart/add",{ product_id: product_id }, function( data ) {
        console.log(data);
        button.attr("disabled", false);
        $('#cart_items_count').text(data.cart_message);
    });
}