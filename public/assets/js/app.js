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
                                var data                                                     = JSON.parse(result)
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
});


