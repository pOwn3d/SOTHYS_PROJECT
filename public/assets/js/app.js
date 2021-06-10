$(document).ready(function () {

    $("#addToCart").click(function (e) {
        e.preventDefault();
        const url = this.href;
        const qty = document.getElementById('quantity').value;
        const res = url.replace("qty", qty);

        $.ajax({
            method: "POST",
            url: res,
            success: function (result) {
                var data = JSON.parse(result)
                console.log(result)
                console.log('ok')
                document.getElementById('cartItem').innerHTML = data.cartItem
                $('.cart__access').removeClass('--scale-2x');
                setTimeout(() => {
                    $('.cart__access').addClass('--scale-2x');
                }, 10);
            },
        });
    });

    $(document).on("mouseup", ".quantity-down", (function () {
            var t = $(this).parent().prev();
            let product = t['0'].dataset.product
            let qty = t.val() - 1
            let promo = t['0'].dataset.promo
            const url = "/add-to-cart/" + product + "/" + qty + "/" + promo

            t.val() > 1 && (t.val(parseInt(t.val()) - 1),
                setTimeout((function () {
                        $.ajax({
                            method: "POST",
                            url: url,
                            success: function (result) {
                                var data = JSON.parse(result)
                                document.getElementById('qty_update_' + product).innerHTML = data.quantity + ' x ' + data.quantityBundling
                                document.getElementById('price_update_' + product).innerHTML = (data.price * data.quantity).toFixed(2) + ' € '
                                    document.getElementById('priceTotal').innerHTML              = data.total + ' € '
                                    document.getElementById('cartItem').innerHTML                = data.cartItem

                                $('.cart__access').removeClass('--scale-2x');
                                setTimeout(() => {
                                    $('.cart__access').addClass('--scale-2x');
                                }, 10);
                            },
                        });
                        t.trigger("change")
                    }
                ), 0))
        }
    ))

    $(document).on("mouseup", ".quantity-up", (function () {
            var t = $(this).parent().prev();

            let promo = t['0'].dataset.promo
            t.val(parseInt(t.val()) + 1),

                setTimeout((function () {

                        let product = t['0'].dataset.product
                        let qty = t.val()
                        const url = "/add-to-cart/" + product + "/" + qty + "/" + promo

                        $.ajax({
                            method: "POST",
                            url: url,
                            success: function (result) {
                                console.log(result)
                                var data = JSON.parse(result)

                                document.getElementById('qty_update_' + product).innerHTML   = data.quantity + ' x ' + data.quantityBundling
                                document.getElementById('price_update_' + product).innerHTML = (data.price * data.quantity).toFixed(2) + ' € '
                                document.getElementById('priceTotal').innerHTML              = data.total + ' € '
                                document.getElementById('cartItem').innerHTML                = data.cartItem
                                $('.cart__access').removeClass('--scale-2x');
                                setTimeout(() => {
                                    $('.cart__access').addClass('--scale-2x');
                                }, 10);
                            },
                        });
                        t.trigger("change")
                    }
                ), 100)
        }
    ))

    $(".js-update-cart-quantity").on("input", function (e) {
        e.preventDefault();
        let product = this.dataset.product
        let qty = $(this).val();
        let promo = this.dataset.promo
        const url = "/add-to-cart/" + product + "/" + qty + "/" + promo

        $.ajax({
            method: "POST",
            url: url,
            success: function (result) {
                var data = JSON.parse(result)
                console.log(data)
                document.getElementById('qty_update_' + product).innerHTML = data.quantity + ' x ' + data.quantityBundling
                document.getElementById('price_update_' + product).innerHTML = (data.price * data.quantity).toFixed(2) + ' € '
                document.getElementById('priceTotal').innerHTML = data.total + ' € '
                document.getElementById('cartItem').innerHTML = data.cartItem
                $('.cart__access').removeClass('--scale-2x');
                setTimeout(() => {
                    $('.cart__access').addClass('--scale-2x');
                }, 10);
            },
        });
    });

    $('.js-cart-search-button').on('click', function(e) {

        e.preventDefault();

        let term = $('.js-cart-search-term').val()

        $.post('/search', {
            term: term
        }).then((res) => {

            var results = res.reduce(function(text, result) {
                text += '<div class="js-cart-add-button" data-product-id="' + result.id + '">' + result.gamme + ' - ' + result.label + ' - ' + result.reference + '</div>';
                return text;
            }, '');

            $('.search-results').html(results)
        })
    });

    $('.section--checkout').on('click', '.js-cart-add-button', function() {
        var id = $(this).attr('data-product-id');
        let qty = $(this).val();
        let promo = this.dataset.promo
        const url = "/add-to-cart/" + id + "/1/undefined"

        $.ajax({
            method: "POST",
            url: url,
            success: function (result) {
                window.location.reload();
            },
        });
    });
});
