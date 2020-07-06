$(function() {
    $('div.offer').click(function() {
        if ($(this).hasClass('priced')) {
            deletePrice($(this).attr('data-header-id'), $(this).attr('data-present-price'), $(this).attr('data-category'), $(this).attr('data-purchase-cost'));
        } else if(! $(this).hasClass('disabled')) {
            price($(this).attr('data-header-id'), $(this).attr('data-price') - 1, $(this).attr('data-category'), $(this).attr('data-purchase-cost'));
        }
    })
});
$(window).keyup(function(e){
    var focused = $(':focus');
    var nextId;
    switch (e.keyCode) {
        case 13:
            if (focused.hasClass('price')) {
                price(focused.attr('data-header-id'), focused.val(), focused.attr('data-category'), focused.attr('data-purchase-cost'));
            }
            break;
        case 46:
            if (focused.hasClass('price')) {
                deletePrice(focused.attr('data-header-id'), focused.attr('data-present-price'), focused.attr('data-category'), focused.attr('data-purchase-cost'));
            }
            break;
        case 40:
            if (focused.attr('data-header-id')) {
                nextId = parseInt(focused.attr('data-header-id')) + 1;
                $('#price' + nextId).focus();
            }
            break;
        case 38:
            if (focused.attr('data-header-id')) {
                nextId = parseInt(focused.attr('data-header-id')) - 1;
                $('#price' + nextId).focus();
            }
            break;

    }
});

$(function() {
    $('.send-to-slack').click(function() {
        sendToSlack($(this).attr('data-asin'), $(this).attr('data-header-id'))
    })
});

$(function() {
    $('.mark-item').click(function() {
        markItem($(this).attr('data-stock-header-id'));
    });
    $('.exclude-items').click(function () {
        excludeItems($(this).data('stock-header-id'));
    });
});

function price(headerId, price, category, purchaseCost) {
    if (! price || isNaN(price)) {
        return false;
    }
    var offers = $('td#offers' + headerId).find('div.offer');
    var priceRank = null;
    var offer;
    $('#price' + headerId).val(price);
    $('#price-cell' + headerId).addClass("priced");
    for(var i = 0; i < offers.length; i++) {
        offer = $(offers[i]);
        if (! priceRank && offer.attr('data-price') > price && ! offer.hasClass('disabled')) {
            priceRank = i + 1;
            offer.addClass("priced");
        }
    }
    $('#price' + (parseInt(headerId) + 1)).focus();
    $.ajax({
        type: "POST",
        url: '/price-ajax/price',
        data: {
            'header_id': headerId,
            'price': price,
            'price_rank': priceRank,
            'purchase_cost': purchaseCost,
            'category': category
        },
        dataType: 'json',
        success: function(result){
            if (result.status === 'error') {
                return false;
            }
            if (result.price) {
                for(i = 0; i < offers.length; i++) {
                    offer = $(offers[i]);
                    offer.removeClass("priced");
                    if ((i + 1) === result.price_rank) {
                        offer.addClass("priced");
                    }
                }
                $('#price' + headerId).val(result.price);
                $('#price-cell' + headerId).addClass("priced");
                var profitObject = $('#profit' + headerId);
                // noinspection JSUnresolvedVariable
                if (result.profit > 0) {
                    profitObject.removeClass('minus-profit');
                } else {
                    profitObject.addClass('minus-profit');
                }
                profitObject.removeClass('profit-for-min-price');
                // noinspection JSUnresolvedVariable
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

function deletePrice(headerId, price, category, purchaseCost) {
    var offers = $('td#offers' + headerId).find('div.offer');
    $.ajax({
        type: "POST",
        url: '/price-ajax/delete',
        data: {
            'header_id': headerId,
            'price': price,
            'purchase_cost': purchaseCost,
            'category': category
        },
        dataType: 'json',
        success: function(result){
            if (result.status === 'error') {
                alert(result.message);
                return false;
            }
            for(var i = 0; i < offers.length; i++) {
                $(offers[i]).removeClass("priced");
            }
            $('#price' + headerId).val('');
            $('#price-cell' + headerId).removeClass("priced");
            var profitObject = $('#profit' + headerId);
            // noinspection JSUnresolvedVariable
            if (result.profit > 0) {
                profitObject.removeClass('minus-profit');
            } else {
                profitObject.addClass('minus-profit');
            }
            // noinspection JSUnresolvedVariable
            profitObject.html(result.profit);
            // noinspection JSUnresolvedVariable
            if (result.sku_priced) {
                profitObject.addClass('profit-for-min-price');
            }
            return true;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}

function deleteSkuPrices(headerId, price, category, purchaseCost) {
    var offers = $('td#offers' + headerId).find('div.offer');
    $.ajax({
        type: "POST",
        url: '/price-ajax/deleteSkuPrices',
        data: {
            'header_id': headerId,
            'price': price,
            'purchase_cost': purchaseCost,
            'category': category
        },
        dataType: 'json',
        success: function(result){
            if (result.status === 'error') {
                alert(result.message);
                return false;
            }
            $('#price-cell' + headerId).removeClass("sku-priced");
            for(var i = 0; i < offers.length; i++) {
                $(offers[i]).removeClass("sku-priced");
            }
            var profitObject = $('#profit' + headerId);
            profitObject.removeClass('profit-for-min-price');
            // noinspection JSUnresolvedVariable
            if (result.profit > 0) {
                profitObject.removeClass('minus-profit');
            } else {
                profitObject.addClass('minus-profit');
            }
            // noinspection JSUnresolvedVariable
            profitObject.html(result.profit);
            return true;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}

function priceForAccount(headerId, price, category, purchaseCost, accountId) {
    if (! price || isNaN(price)) {
        return false;
    }
    var offers = $('td#offers' + headerId).find('div.offer');
    var priceRank = 0;
    var offer;
    $('#price-cell' + headerId).addClass("sku-priced");
    for(var i = 0; i < offers.length; i++) {
        offer = $(offers[i]);
        offer.removeClass("sku-priced");
        if (! priceRank && offer.attr('data-price') > price && ! offer.hasClass('disabled')) {
            priceRank = i + 1;
        }
    }
    $.ajax({
        type: "POST",
        url: '/price-ajax/priceForAccount',
        data: {
            'header_id': headerId,
            'account_id' : accountId,
            'price': price,
            'price_rank': priceRank,
            'purchase_cost': purchaseCost,
            'category': category
        },
        dataType: 'json',
        success: function(result){
            if (result.status === 'error') {
                return false;
            }
            // noinspection JSUnresolvedVariable
            if (result.price_ranks) {
                for (i = 0; i < result.price_ranks.length; i++) {
                    $(offers[result.price_ranks[i] - 1]).addClass('sku-priced');
                }
            }
            // noinspection JSUnresolvedVariable
            if (result.profit) {
                var profitObject = $('#profit' + headerId);
                if (result.profit > 0) {
                    profitObject.removeClass('minus-profit');
                } else {
                    profitObject.addClass('minus-profit');
                }
                profitObject.addClass('profit-for-min-price');
                profitObject.html(result.profit);
            }
            return true;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}

function sendToSlack(asin, headerId) {
    var notes = prompt("Slack送信\nメモを入力してください。");
    if (notes === null) {
        return false;
    }
    $.ajax({
        type: "POST",
        url: '/price-ajax/sendToSlack',
        data: {
            'header_id': headerId,
            'asin': asin,
            'notes': notes
        },
        dataType: 'json',
        success: function(result){
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}

function markItem(stockHeaderId) {
    $.ajax({
        type: "POST",
        url: '/price-ajax/markItem',
        data: {
            'stock_header_id': stockHeaderId
        },
        dataType: 'json',
        success: function(result){
            if (result.status === 'error') {
                alert(result.message);
            } else {
                var button = $('#is-marked' + stockHeaderId);
                // noinspection JSUnresolvedVariable
                if (result.is_marked) {
                    button.val('追跡中');
                    button.addClass('marked');
                } else {
                    button.val('追跡する');
                    button.removeClass('marked');
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}

function excludeItems(stockHeaderId) {
    $.ajax({
        type: "POST",
        url: '/price-ajax/exclude-items',
        data: {
            'stock_header_id': stockHeaderId
        },
        dataType: 'json',
        success: function(result){
            if (result.status === 'error') {
                alert(result.message);
            } else {
                var button = $('#exclude-items-' + stockHeaderId);
                console.info(button);
                if (result['is_excluded']) {
                    button.val('除外から戻す');
                    button.addClass('excluded');
                } else {
                    button.val('除外');
                    button.removeClass('excluded');
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(errorThrown);
            return false;
        }
    });
}
