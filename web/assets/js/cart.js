$(document).ready(function()
{
    $('#detailCart').on('click', '.btn-danger', function(event)
    {
        event.preventDefault();
        console.log(this);

        var $product = $(this).closest('.item-product');

        $.ajax
        ({
            url: $(this).attr('href'),
            dataType: 'json'
        }).done(function(data, textStatus)
            {
                console.log(data);
                console.log(textStatus)  ;

                $product.fadeOut(700, function()
                    {
                        $(this).remove();
                        var totalPrice = 0;
                        $('.price').each(function() {
                            totalPrice += parseFloat($(this).text());
                        });

                        $('.totalPrice').html(totalPrice);
                        console.log(totalPrice);
                    });
            });



    });


    $('.plus').on('click',function(e)
    {

        e.preventDefault();

        //alert('ici');
        var $currentQty = $(this).siblings('.quantity');
        //console.log($currentQty);

        var $oneprice = $(this).closest('.col-md-1').siblings('.oneprice');

        var quantity = parseFloat($currentQty.val());
        var oneprice = parseFloat($oneprice.text());
        var totalPrice = 0;
        var price = 0;
        // variable that stock $(this) pour l'utiliser partous dans la fonction
        var that = $(this);

        //console.log(quantity);
        console.log(oneprice);

        $.ajax
        ({
            url: $(this).attr('href'),
            dataType: 'json'
        }).done(function()
        {
            //alert('là');

            quantity = quantity + 1;

            price = quantity * oneprice;

            console.log(price);

            that.closest('.traversing').siblings('.price').html(price);

            //console.log(quantity);

            $('.price').each(function() {
                totalPrice += parseFloat($(this).text());
            });

            $('.totalPrice').html(totalPrice);

            $currentQty.val(quantity);

        });
    });

    $('.minus').on('click',function(e)
    {

        e.preventDefault();

        //alert('ici');
        var $currentQty = $(this).siblings('.quantity');
        //console.log($currentQty);

        var $oneprice = $(this).closest('.traversing').siblings('.oneprice');

        var quantity = parseFloat($currentQty.val());
        var oneprice = parseFloat($oneprice.text());
        var totalPrice = 0;
        var price = 0;
        var that = $(this);

        //console.log(quantity);
        console.log(oneprice);

        $.ajax
        ({
            url: $(this).attr('href'),
            dataType: 'json'
        }).done(function()
        {
            //alert('là');

            quantity = quantity - 1;

            if(quantity <= 0)
            {
                //alert("ici");

                var trigger = that.closest('.traversing').siblings('.disablable').find('.trigger');

                //console.log(trigger);

                trigger.trigger('click');

                $('#ajaxMessage').append('<h2 class="text-center text-uppercase main-heading"><i class="fa fa-exclamation-triangle fa-2x"></i> Votre panier est vide <i class="fa fa-exclamation-triangle fa-2x"></i></h2> ')
            }

            price = quantity * oneprice;

            console.log(price);

            that.closest('.traversing').siblings('.price').html(price);

            //console.log(quantity);

            $('.price').each(function() {
                totalPrice += parseFloat($(this).text());
            });

            $('.totalPrice').html(totalPrice);

            $currentQty.val(quantity);

        });
    });



});
