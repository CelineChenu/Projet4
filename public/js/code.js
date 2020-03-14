$(function() {
    $('#cgv').change(function() {
        if ($('#cgv:checked').length > 0) {
            document.getElementsByClassName("stripe-button-el")[0].disabled=false;

        } else {
            document.getElementsByClassName("stripe-button-el")[0].disabled=true;
        }
    });
});

//var stripe = Stripe('pk_test_dnJVwJevLE1FWm9uXMxIrjKI00fxL68lH8');
//var elements = stripe.elements();