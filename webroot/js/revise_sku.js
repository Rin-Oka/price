$(function() {
    $('div.offer').click(function() {
        if ($(this).hasClass('priced')) {
            deletePrice($(this).attr('data-item-id'), $(this).attr('data-present-price'), $(this).attr('data-category'), $(this).attr('data-purchase-cost'));
        } else if(! $(this).hasClass('disabled')) {
            price($(this).attr('data-item-id'), $(this).attr('data-price') - 1, $(this).attr('data-category'), $(this).attr('data-purchase-cost'));
        }
    })
});
$(window).keyup(function(e){
    var focused = $(':focus');
    switch (e.keyCode) {
        case 13:
            if (focused.hasClass('price')) {
                price(focused.attr('data-item-id'), focused.val(), focused.attr('data-category'), focused.attr('data-purchase-cost'));
            }
            break;
        case 46:
            if (focused.hasClass('price')) {
                deletePrice(focused.attr('data-item-id'), focused.attr('data-present-price'), focused.attr('data-category'), focused.attr('data-purchase-cost'));
            }
            break;

    }
});

function price(itemId, price, category, purchaseCost) {
    if (! price || isNaN(price)) {
        return false;
    }
    var offers = $('td#offers-' + itemId).find('div.offer');
    var priceRank = 0;
    var offer;
    $('#price-' + itemId).val(price);
    $('#price-cell-' + itemId).addClass("priced");
    for(var i = 0; i < offers.length; i++) {
        offer = $(offers[i]);
        if (! priceRank && offer.attr('data-price') > price && ! offer.hasClass('disabled')) {
            priceRank = i + 1;
            offer.addClass("priced");
        }
    }
    $.ajax({
        type: "POST",
        url: '/price-ajax/priceForItem',
        data: {
            'item_id': itemId,
            'price': price,
            'price_rank': priceRank,
            'purchase_cost': purchaseCost,
            'category': category
        },
        dataType: 'json',
        success: function(result){
            if (result.status == 'error') {
                return false;
            }
            if (result.price) {
                for(i = 0; i < offers.length; i++) {
                    offer = $(offers[i]);
                    offer.removeClass("priced");
                    if ((i + 1) == result.price_rank) {
                        offer.addClass("priced");
                    }
                }
                $('#price-' + itemId).val(result.price);
                $('#price-cell-' + itemId).addClass("priced");
                var profitObject = $('#profit-' + itemId);
                if (result.profit > 0) {
                    profitObject.removeClass('minus-profit');
                } else {
                    profitObject.addClass('minus-profit');
                }
                profitObject.html(result.profit);
                return true;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}

function deletePrice(itemId, price, category, purchaseCost) {
    var offers = $('td#offers-' + itemId).find('div.offer');
    $.ajax({
        type: "POST",
        url: '/price-ajax/deleteForItem',
        data: {
            'item_id': itemId,
            'price': price,
            'purchase_cost': purchaseCost,
            'category': category
        },
        dataType: 'json',
        success: function(result){
            if (result.status == 'error') {
                alert(result.message);
                return false;
            }
            for(var i = 0; i < offers.length; i++) {
                $(offers[i]).removeClass("priced");
            }
            $('#price-' + itemId).val('');
            $('#price-cell-' + itemId).removeClass("priced");
            var profitObject = $('#profit-' + itemId);
            if (result.profit > 0) {
                profitObject.removeClass('minus-profit');
            } else {
                profitObject.addClass('minus-profit');
            }
            profitObject.html(result.profit);
            return true;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}