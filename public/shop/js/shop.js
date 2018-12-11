function add(button, product_id) {
    button  =  $(button);
    button.attr("disabled", true);
    $.post( "/cart/add",{ product_id: product_id }, function( data ) {
        $('#cart').html(data.cart);
        button.attr("disabled", false);
    });
}

function select_radio(elem){
    elem = $(elem);
    elem.children(0).children(0).prop("checked", true);
    console.log(elem.children(0).children(0));
    return true;
}
