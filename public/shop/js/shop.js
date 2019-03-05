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

    radio = elem.find("input[type=radio]");
    radio.prop("checked", true);
    console.log(radio);
    return true;
}


$( function() {
    var availableTags = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++",
        "Clojure",
        "COBOL",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme"
    ];
    $( "[name='q']" ).autocomplete({
        source: availableTags
    });


} );
