
window.packagingData = {};
window.orderData = {};
window.orderUpdateEnabled = true;
window.dy = -1;

function showMessage() {
    if (confirm("Заказ уже обработан.\nСоздать новый заказ?")) {
        newOrder();
    }
}

function loadPackaging() {
    jQuery.ajax('index.php?r=sell/packaging&pos_id=' + pos_id + '&t=' + Math.random(), {
        dataType: 'json',
        success: function (data) {
            //var i, cnt;
            // console.log(data);
            window.packagingData = data;

            // draw categories:
            drawCategories(data);

            // draw basic packaging
            drawBasicPackaging(data);

        }
    });
}

function drawCategories(data) {
    // 1. get list of categories
    var categories = {};
    for (i = 0, cnt = data.packagingBasic.length; i < cnt; i++) {
        categories[data.packagingBasic[i].category_id] = 0;
    }
    // 2 fill-in category data structure
    for (i = 0, cnt = data.category; i < cnt; i++) {
        categories[data.category[i].category_id] = data.category[i];
    }
    // 3 get active category
    var activeCategoryId = -1;
    var activeCategory = $('#categories').find('.active');
    if (activeCategory.length > 0) {
        activeCategoryId = 1 * activeCategory.first().attr('data-category_id');
    }
    if (activeCategoryId < 0) {
        activeCategoryId = data.category[0].category_id;
    }
    // console.log('activeCategoryId='+activeCategoryId);
    // 4 draw list of categories
    $('#categories').empty();
    $('#categories').append($('<div class="tipcontainer logo"><div class="tip">&nbsp;</div></div>'));
    for (i = 0, cnt = data.category.length; i < cnt; i++) {
        var element = domOneCategory(data.category[i]);
        if (activeCategoryId == data.category[i].category_id) {
            element.addClass('active');
            var skin = element.attr('data-category_skin');
            //$('#tovaryList').removeClass().addClass('tovary').addClass(skin);
            $('#packagingBasic').removeClass().addClass('tovar tov_1 ' + skin);
        }
        $('#categories').append(element);
    }
}

function domOneCategory(item) {
    var element = $('<div class="tip"></div>');
    var html = '';
    html += '<h5>';
    html += item.category_title;
    html += '</h5>';
    element.html(html);

    var container = $('<div class="tipcontainer"></div>');
    container.append(element);
    container.addClass(item.category_skin);
    container.attr('data-category_id', item.category_id);
    container.attr('data-category_skin', item.category_skin);
    container.click(categoryClicked);
    return container;
}

function categoryClicked(event) {
    // alert('clicked!');
    // remove active class
    $('#categories').find('.active').removeClass('active');
    // set active class to current category
    var element = $(this);
    element.addClass('active');
    var skin = element.attr('data-category_skin');
    $('#packagingBasic').removeClass().addClass('tovar tov_1 ' + skin);
    // re-draw packagingBasic
    drawBasicPackaging(window.packagingData);
}

function packagingClickedDisabled() {
    alert('Не хватает составляющих, чтобы продать продукт.');
}

function domOnePackaging(item) {
    var element = $('<div class="produkt_block"></div>');
    element.attr('data-packaging_id', item.packaging_id);
    element.attr('data-packaging_price', item.packaging_price);
    if (item.packaging_is_available) {
        element.click(packagingClicked);
    } else {
        element.addClass('disabled');
        //element.click(packagingClickedDisabled); 
        element.click(packagingClicked);
    }

    var html = '';
    html += '<div class="produkt">';
    html += item.imageThumb;
    html += '<div>';
    html += item.packaging_title + '; ' + item.packaging_price + "&nbsp;" + currency;
    html += '</div>';
    html += '</div>';
    element.html(html);
    return element;
}

function drawBasicPackaging(data) {
    var activeCategoryId = -1;
    var activeCategory = $('#categories').find('.active');
    if (activeCategory.length > 0) {
        activeCategoryId = 1 * activeCategory.first().attr('data-category_id');
    }
    if (activeCategoryId < 0) {
        activeCategoryId = data.category[0].category_id;
    }

    $('#packagingBasic').empty();
    for (i = 0, cnt = data.packagingBasic.length; i < cnt; i++) {
        if (data.packagingBasic[i].category_id == activeCategoryId) {
            // console.log('drawn:'+data.packagingBasic[i].packaging_title);
            var element = domOnePackaging(data.packagingBasic[i]);
            $('#packagingBasic').append(element);
        } else {
            // console.log('skiped:'+data.packagingBasic[i].packaging_title);
        }
    }
}

function drawAdditionalPackaging(data) {
    $('#packagingAdditional').empty();
    for (i = 0, cnt = data.packagingAdditional.length; i < cnt; i++) {
        var element = domOnePackaging(data.packagingAdditional[i]);
        $('#packagingAdditional').append(element);
    }
}

function gotCacheChanged() {
    var gotCache = $('#gotCache').val();
    var orderTotal = $('#orderTotal').text();
    var sdacha = gotCache - orderTotal;
    if (sdacha < 0) {
        $('#sdacha').empty().html('0');
    } else {
        $('#sdacha').empty().html(sdacha + ' ' + currency);
    }
    $('.calcVal').each(sdachaTabl);
}

function sdachaTabl(ind, el){
    var elm=$(el);
    var orderTotal = parseFloat($('#orderTotal').text());
    var billSgn=parseFloat(elm.attr('data-val'));
    if(billSgn>orderTotal){
        elm.html((billSgn-orderTotal)+'&nbsp;'+currency);
    }else{
        elm.html('0&nbsp;'+currency);
    }
}

function getStats() {
    jQuery.ajax('index.php?r=sell/sellerstats&seller_id=' + seller_id + '&t=' + Math.random(), {
        dataType: 'json',
        success: function (data) {
            // console.log(data);
            $('#sellerName').html(data.sysuser_fullname+'@'+data.pos_title);
            $("#ordersCount").html(data.count);
            $("#ordersTotal").html(data.total);
            $("#sellerCommissionFee").html(data.comission);
        }
    });
}

function newOrder() {
    $('#zakazItemsPanel').empty();
    $('#gotCache').attr('value', '');
    $('#sdacha').empty().html(0);
    $('#orderTotal').html(0);
    $('#discountSelector').val('');
    // get new zakaz number
    jQuery.ajax('index.php?r=sell/ordernumber&pos_id=' + pos_id + '&t=' + Math.random(), {
        dataType: 'json',
        success: function (data) {
            $('#zakazId').empty().html(data.id);
            window.orderData = {order_day_sequence_number: data.id};
            window.orderUpdateEnabled = true;
        }
    });

}

function packagingClicked(event) {
    if (!window.orderUpdateEnabled) {
        showMessage();
        return;
    }
    //alert('clicked!');
    var element = $(this);
    // get data
    var packaging_id = element.attr('data-packaging_id');
    // console.log(packaging_id+' clicked');
    // search packaging by id
    //console.log(window.packagingData);
    var dat, i, packaging = false;
    dat = window.packagingData.packagingAdditional;
    for (i = 0; i < dat.length && !packaging; i++) {
        if (packaging_id === dat[i].packaging_id) {
            packaging = dat[i];
        }
    }
    dat = window.packagingData.packagingBasic;
    for (i = 0; i < dat.length && !packaging; i++) {
        if (packaging_id === dat[i].packaging_id) {
            packaging = dat[i];
        }
    }
    if (packaging) {
        addPackagingToOrder(packaging);
        updateOrderTotal();
    }

}

function addPackagingToOrder(packaging) {
    // add to order
    if (window.orderData[packaging.packaging_id]) {
        window.orderData[packaging.packaging_id].count++;
    } else {
        window.orderData[packaging.packaging_id] = {
            count: 1,
            packaging: packaging
        }
    }

    var orderItem = $('#orderItem' + packaging.packaging_id);
    if (orderItem.length == 0) {
        var dom = $('<div class="stroka_zakaza"></div>');
        dom.attr('id', 'orderItem' + packaging.packaging_id);

        var html;
        html = $('<div class="nazvanie">' + packaging.packaging_title + '</div>');
        dom.append(html);

        html = $('<div class="minus" data-packaging_id="' + packaging.packaging_id + '">&ndash;</div>');
        html.click(minusitem);
        dom.append(html);

        html = $('<div class="kolichestvo" id="orderItem' + packaging.packaging_id + 'count">1</div>');
        dom.append(html);

        html = $('<div class="plus" data-packaging_id="' + packaging.packaging_id + '">+</div>');
        html.click(plusitem);
        dom.append(html);

        html = $('<div class="cena">&nbsp;&times;' + packaging.packaging_price + ' ' + currency + '</div>');
        dom.append(html);

        html = $('<div class="otmena" data-packaging_id="' + packaging.packaging_id + '">&times;</div>');
        html.click(removeitem);
        dom.append(html);

        $('#zakazItemsPanel').append(dom);

        reactiveteScroller();
        $('#zakazItemsPanel').animate({marginTop: "-" + window.dy + 'px'}, 300);

    } else {
        $('#orderItem' + packaging.packaging_id + 'count').html(window.orderData[packaging.packaging_id].count);

    }

    updateOrderTotal();
}

function plusitem(event) {
    if (!window.orderUpdateEnabled) {
        showMessage();
        return;
    }
    var el = $(this);
    var packaging_id = el.attr('data-packaging_id');
    window.orderData[packaging_id].count++;
    $('#orderItem' + packaging_id + 'count').html(window.orderData[packaging_id].count);

    updateOrderTotal();
}

function minusitem(event) {
    if (!window.orderUpdateEnabled) {
        showMessage();
        return;
    }
    var el = $(this);
    var packaging_id = el.attr('data-packaging_id');
    window.orderData[packaging_id].count--;

    if (window.orderData[packaging_id].count > 0) {
        $('#orderItem' + packaging_id + 'count').html(window.orderData[packaging_id].count);
    } else {
        delete(window.orderData[packaging_id]);
        $('#orderItem' + packaging_id).remove();
    }

    updateOrderTotal();
}

function removeitem(event) {
    if (!window.orderUpdateEnabled) {
        showMessage();
        return;
    }
    var el = $(this);
    var packaging_id = el.attr('data-packaging_id');
    delete(window.orderData[packaging_id]);
    $('#orderItem' + packaging_id).remove();

    updateOrderTotal();

    reactiveteScroller();
}

function updateOrderTotal() {
    //alert('updateOrderTotal');
    var total = 0;
    //console.log(window.orderData);
    for (var packaging_id in window.orderData) {
        if (isNaN(packaging_id)) {
            continue;
        }
        total += window.orderData[packaging_id].count * window.orderData[packaging_id].packaging.packaging_price;
    }

    total -= getDiscount(false);

    $('#orderTotal').empty().html(total);
    window.orderData.order_total = total;
    gotCacheChanged();
}

/**
 * 
 */
function getDiscount(verbose) {
    if (!window.orderData.discount_id) {
        return 0;
    }
    try {
        

        var condition_ok=false;

        var json = discounts[window.orderData.discount_id];
        
        // get transformation rule
        var transformationRule;
        var transformationParameter=parseFloat(json.discount_value);
        if(isNaN(transformationParameter)){
            if(verbose){console.log('invalid transformationParameter');}
            transformationRule=function(from){return 0;};
        }else{
            if(json.discount_unit=='%'){
                if(verbose){console.log(' percentage '+transformationParameter);}
                transformationRule=function(from){ return 0.01*transformationParameter*from; };
            }else{
                if(verbose){console.log(' absolute '+transformationParameter);}
                transformationRule=function(from){ return transformationParameter; };
            }            
        }

        
        // get order total
        var order_total = 0;
        for (var packaging_id in window.orderData) {
            if (isNaN(packaging_id)) {
                continue;
            }
            order_total += window.orderData[packaging_id].count * window.orderData[packaging_id].packaging.packaging_price;
        }

        
        // check conditions
        if (json.condition_attribute) {
            if(verbose){console.log(' matching  condition_attribute '+json.condition_attribute);}
            

            switch (json.condition_attribute) {
                case 'order_total':
                    var val=parseFloat(json.condition_value);
                    switch(json.condition_operator){
                        case '>' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total > val); }  break;
                        case '>=': if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total >= val); } break;
                        case '=' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total == val); } break;
                        case '<=': if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total <= val); } break;
                        case '<' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total < val); }  break;
                        case 'e' : condition_ok=true;  break;
                    }
                    if(verbose){console.log(' order_total '+condition_ok);}
                    break;
                
                case 'packaging_id':
                case 'packaging_price':
                    for (var packaging_id in window.orderData) {
                        if (isNaN(packaging_id)) { continue; }
                        if(condition_ok){ continue; }
                        var num=parseFloat(window.orderData[packaging_id].packaging.packaging_price);
                        var val=parseFloat(json.condition_value);
                        switch(json.condition_operator){
                            case '>' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(num > val); }  break;
                            case '>=': if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(num >= val); } break;
                            case '=' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(num == val); } break;
                            case '<=': if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(num <= val); } break;
                            case '<' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(num < val); }  break;
                        }
                        if(verbose){console.log(json.condition_attribute + '  '+condition_ok);}
                    }
                    break;

                case 'packaging_title':
                    for (var packaging_id in window.orderData) {
                        if (isNaN(packaging_id)) { continue; }
                        if(condition_ok){ continue; }
                        var packaging_title=window.orderData[packaging_id].packaging.packaging_title.toLocaleLowerCase();
                        switch(json.condition_operator){
                            case '>' : condition_ok=(packaging_title > json.condition_value);  break;
                            case '>=': condition_ok=(packaging_title >= json.condition_value); break;
                            case '=' : condition_ok=(packaging_title == json.condition_value); break;
                            case '<=': condition_ok=(packaging_title <= json.condition_value); break;
                            case '<' : condition_ok=(packaging_title < json.condition_value);  break;
                            case '~' : 
                                var words=json.condition_value.toLocaleLowerCase().split(/[ ,;-]+/);
                                var res=true;
                                for(var w=0; w<words.length; w++){
                                    res=(res && (packaging_title.indexOf(words[w])>=0) );
                                }
                                condition_ok=res;
                                break;
                        }
                        if(verbose){console.log(json.condition_attribute + '  '+condition_ok);}
                    }
                    break;
            }
        }else{
            condition_ok=true;
        }
        
        // apply transformation
        if (json.search_attribute) {
            if(verbose){console.log(' matching  search_attribute '+json.search_attribute);}
            switch (json.search_attribute) {
                
                // ========== apply discount to order total = begin ============
                case 'order_total':
                    condition_ok=false;
                    var val=parseFloat(json.search_value);
                    switch(json.search_operator){
                        case '>' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total > val); }  break;
                        case '>=': if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total >= val); } break;
                        case '=' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total == val); } break;
                        case '<=': if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total <= val); } break;
                        case '<' : if(isNaN(val)){ condition_ok=false; }else{ condition_ok=(order_total < val); }  break;
                        case 'e' : condition_ok=true; break;
                    }
                    if(condition_ok){
                        
                        var discountValue=transformationRule(order_total);
                        if(verbose){console.log(' discountValue = '+discountValue);}
                        return discountValue;
                    }else{
                        if(verbose){console.log(' discountValue = 0');}
                        return 0;
                    }
                    break;
                // ========== apply discount to order total = end ==============
                
                
                // == apply discount to packaging_id or packaging_price = begin ==
                case 'packaging_id':
                case 'packaging_price':
                    var discountValue=0;
                    condition_ok=false;
                    var val=parseFloat(json.search_value);
                    if(isNaN(val)){ return 0; }
                    for (var packaging_id in window.orderData) {
                        if (isNaN(packaging_id)) { continue; }
                        var num=parseFloat(window.orderData[packaging_id].packaging.packaging_price);
                        switch(json.search_operator){
                            case '>' : condition_ok=(num > val);   break;
                            case '>=': condition_ok=(num >= val);  break;
                            case '=' : condition_ok=(num == val);  break;
                            case '<=': condition_ok=(num <= val);  break;
                            case '<' : condition_ok=(num < val);   break;
                        }
                        if(condition_ok){
                            discountValue+=window.orderData[packaging_id].count * transformationRule(num);
                            if(verbose){console.log(' discountValue = '+discountValue);}
                        }
                    }
                    return discountValue;
                    break;
                // == apply discount to packaging_id or packaging_price = end ====

                case 'packaging_title':
                    var discountValue=0;
                    condition_ok=false;
                    for (var packaging_id in window.orderData) {
                        if (isNaN(packaging_id)) {  continue; }
                        var packaging_title=window.orderData[packaging_id].packaging.packaging_title.toLocaleLowerCase();
                        switch(json.search_operator){
                            case '>' : condition_ok=(packaging_title > json.search_value);  break;
                            case '>=': condition_ok=(packaging_title >= json.search_value); break;
                            case '=' : condition_ok=(packaging_title == json.search_value); break;
                            case '<=': condition_ok=(packaging_title <= json.search_value); break;
                            case '<' : condition_ok=(packaging_title < json.search_value);  break;
                            case '~':
                                var words=json.search_value.toLocaleLowerCase().split(/[ ,;-]+/);
                                var res=true;
                                for(var w=0; w<words.length; w++){
                                    res=(res && (packaging_title.indexOf(words[w])>=0) );
                                }
                                condition_ok=res;
                                break;
                        }
                        if(condition_ok){
                            discountValue+=window.orderData[packaging_id].count * transformationRule(parseFloat(window.orderData[packaging_id].packaging.packaging_price));
                            if(verbose){console.log(' discountValue = '+discountValue);}
                        }
                    }
                    return discountValue;
                    break;
            }
        }
    } catch (err) {
    }
    return 0;
}

function paid(paymentTypeName) {

    return function () {

        if (!window.orderUpdateEnabled) {
            showMessage();
            return;
        }

        var order_packaging = {};
        var nItems = 0;
        for (var packaging_id in window.orderData) {
            if (isNaN(packaging_id)) {
                continue;
            }
            order_packaging[packaging_id] = window.orderData[packaging_id].count;
            nItems++;
        }

        if (nItems == 0) {
            return;
        }


        // block buttons
        window.orderUpdateEnabled = false;
        $("#dialog").dialog("open");

        window.orderData.order_payment_type = paymentTypeName;

        var post = {
                order: {
                    order_day_sequence_number: window.orderData.order_day_sequence_number,
                    order_payment_type: paymentTypeName,
                    discount_id: window.orderData.discount_id,
                    order_packaging: order_packaging
                }
            };

        jQuery.ajax('index.php?r=sell/createorder&pos_id=' + pos_id, {
            //dataType:'json',
            type: "POST",
            data: post,
            success: function (data) {
                // console.log(data);
                getStats();
                $("#dialog").dialog("close");
                try {
                    printReceipt();
                } catch (err) {
                    //alert('print_error');
                    console.log(err);
                }
                loadPackaging();
                newOrder();
            }
        });
    };
}

function zakazScrollDownClick() {
    if (!$('#zakazScrollDown').hasClass('active'))
        return;
    $('#zakazItemsPanel').animate({marginTop: "+=20"}, 300, function () {
        var mtp = parseInt($('#zakazItemsPanel').css('margin-top'));
        if (mtp > 0) {
            $('#zakazItemsPanel').animate({marginTop: '0px'}, 300);
            $('#zakazScrollDown').removeClass('active');
        }
        $('#zakazScrollUp').addClass('active');
    });
}
;

function zakazScrollUpClick() {
    if (!$('#zakazScrollUp').hasClass('active'))
        return;
    $('#zakazItemsPanel').animate({marginTop: "-=20"}, 300, function () {
        var mtp = parseInt($('#zakazItemsPanel').css('margin-top'));
        if ((window.dy + mtp) < 0) {
            $('#zakazItemsPanel').animate({marginTop: "-" + window.dy + 'px'}, 300);
            $('#zakazScrollUp').removeClass('active');
        }
        $('#zakazScrollDown').addClass('active');
    });
}
;


function reactiveteScroller() {
    $('#zakazScrollUp').removeClass('active');
    $('#zakazScrollDown').removeClass('active');
    var zh = $('#zakazItems').height();
    var ph = $('#zakazItemsPanel').height();
    window.dy = ph - zh;
    if (window.dy > 0) {
        $('#zakazScrollDown').addClass('active');
    } else {
        $('#zakazItemsPanel').animate({marginTop: '0px'}, 300);
    }
}


function printReceipt() {

    if (!printerUrl) {
        return;
    }

    var orderData = {};
    orderData.order_day_sequence_number = window.orderData.order_day_sequence_number;
    orderData.sysuser_lastname = sysuser_lastname;
    orderData.order_total = window.orderData.order_total;
    //orderData.discount_title=window.orderData.order_total;

    switch (window.orderData.order_payment_type) {
        case 'cash':
            orderData.order_payment_type = 'Наличные';
            break;
        case 'card':
            orderData.order_payment_type = 'Карта';
            break;
        case 'return':
            orderData.order_payment_type = 'Возврат';
            break;
    }

    var d = new Date();
    var day = d.getDate();
    if (day < 10) {
        day = '0' + day;
    }
    var mon = d.getMonth() + 1;
    if (mon < 10) {
        mon = '0' + mon;
    }
    var year = d.getFullYear();

    var hrs = d.getHours();
    if (hrs < 10) {
        hrs = '0' + hrs;
    }
    var mnt = d.getMinutes();
    if (mnt < 10) {
        mnt = '0' + mnt;
    }
    orderData.order_datetime = day + '.' + mon + '.' + year + ' ' + hrs + ':' + mnt;

    //console.log(window.orderData);
    orderData.packaging = {};
    for (var packaging_id in window.orderData) {
        if (isNaN(packaging_id)) {
            continue;
        }
        orderData.packaging[packaging_id] = {
            order_packaging_number: window.orderData[packaging_id].count,
            packaging_price: window.orderData[packaging_id].packaging.packaging_price,
            packaging_title: window.orderData[packaging_id].packaging.packaging_title
        }
    }

    var str = processTemplate(orderData);
    //console.log(str);
    jQuery.ajax(printerUrl, {
        //dataType:'json',
        type: "POST",
        data: {data: str},
        success: function (data) {
        }
    });
    return;
    //
}





function tpl() {
    this.max_str_size = 50;

    this.blanks = '';
    for (var j = 0; j < this.max_str_size; j++) {
        this.blanks += ' ';
    }

    this.center = function (s) {
        var s_len = s.length;
        var rows = [];
        var cnt = Math.floor(s_len / this.max_str_size);
        for (var i = 0; i < cnt; i++) {
            rows.push(s.substr(i * this.max_str_size, this.max_str_size));
        }
        var last_str = s.substr(cnt * this.max_str_size, this.max_str_size);
        var margin = this.blanks.substr(0, Math.floor((this.max_str_size - last_str.length) / 2));
        last_str = ' ' + margin + last_str + margin;
        if (last_str.length > this.max_str_size) {
            last_str = last_str.substr(1, last_str.length - 1);
        }
        rows.push(last_str);
        return rows;
    }


    this.justify = function (left, fill, right) {
        var rows = [];
        var left_length = left.length;
        var right_length = right.length;

        var cnt, i, left_last_str, right_last_str;
        if (left_length > this.max_str_size) {
            cnt = Math.floor(left_length / this.max_str_size);
            for (i = 0; i < cnt; i++) {
                rows.push(left.substr(i * this.max_str_size, this.max_str_size));
            }
            left_last_str = left.substr(cnt * this.max_str_size, this.max_str_size);
        } else {
            left_last_str = left;
        }
        //console.log(rows, left_last_str);

        var rrows = [];
        if (right_length > this.max_str_size) {
            cnt = Math.floor(right_length / this.max_str_size);
            for (i = 0; i < cnt; i++) {
                rrows.push(right.substr(right_length - (i + 1) * this.max_str_size, this.max_str_size));
            }
            right_last_str = right.substr(right_length - (cnt + 1) * this.max_str_size, this.max_str_size);
        } else {
            right_last_str = right;
        }

        if ((left_last_str.length + right_last_str.length) < this.max_str_size) {
            var rasporka = '';
            var rs = this.max_str_size - (left_last_str.length + right_last_str.length);
            for (var i = 0; i < rs; i++) {
                rasporka += fill;
            }
            rows.push(left_last_str + rasporka + right_last_str);
        } else {
            var rs;
            rs = this.max_str_size - left_last_str.length;
            for (var i = 0; i < rs; i++) {
                left_last_str += fill;
            }
            rows.push(left_last_str);

            rs = this.max_str_size - right_last_str.length;
            for (var i = 0; i < rs; i++) {
                right_last_str = fill + right_last_str;
            }
            rows.push(right_last_str);
        }

        rrows.reverse();
        for (var k = 0; k < rrows.length; k++) {
            rows.push(rrows[k]);
        }
        return rows;
    };
}



function adjustSizes() {

    var categoryHeight = 127;
    var statistikaHeight = 40;
    var btnOplataHeight = 150;
    var calcHeight = 160;
    var itogoHeight = 40;
    var zakazScrollHeight = 20;
    var zakazTopMargin = 3;
    var discountHeight = 40;
    var zakazBorderWidth = 10;

    var wh = $(window).height();
    //alert(wh);


    $('#categories').css({height: categoryHeight + 'px', marginBottom: 0});
    $('.statistika').css('height', statistikaHeight + 'px')

    var tovaryListHeight = wh - categoryHeight - statistikaHeight - zakazBorderWidth;
    $('#tovaryList').css('height', tovaryListHeight + 'px');



    // right column
    //$('#newOrder').css('height',statistikaHeight+'px');
    $('#newOrder').css('height', itogoHeight + 'px');

    //var newBtnHeight=$('#newOrder').height();

    //$('.oplata').css({height: btnOplateHeight+'px',bottom:newBtnHeight+'px'});
    $('.oplata').css({height: btnOplataHeight + 'px', bottom: '0px'});

    //$('.raschet').css({height:calcHeight+'px',bottom: (newBtnHeight+btnOplateHeight)+'px'});
    $('.raschet').css({height: (calcHeight-7) + 'px', bottom: (btnOplataHeight+7) + 'px'});

    //$('.itogo').css({height:itogoHeight+'px',bottom: (newBtnHeight+btnOplateHeight+calcHeight)+'px'});
    $('.itogo').css({height: itogoHeight + 'px', bottom: (btnOplataHeight + calcHeight) + 'px'});

    $('.discounts').css({height: discountHeight + 'px', bottom: (itogoHeight + btnOplataHeight + calcHeight) + 'px'});


    $('#zakazScrollUp').css({height: zakazScrollHeight + 'px'});
    $('#zakazScrollDown').css({height: zakazScrollHeight + 'px'});

    var zakazHeight = wh - discountHeight - itogoHeight - calcHeight - btnOplataHeight;
    $('.zakaz').css({height: zakazHeight + 'px'});

    var zakazH2Height = zakazBorderWidth + 1 * $('.zakaz h2').first().outerHeight();
    //var zakazItemsHeight= wh - zakazH2Height-2*zakazScrollHeight-itogoHeight-calcHeight-btnOplateHeight-newBtnHeight-2*zarazTopMargin;
    var zakazItemsHeight = zakazHeight - zakazH2Height - 2 * zakazScrollHeight - 2 * zakazTopMargin;

    $('#zakazItems').css({height: zakazItemsHeight + 'px', marginTop: zakazTopMargin + 'px', marginBottom: zakazTopMargin + 'px'});

    $('.bordercolumn').css({height: (wh - statistikaHeight) + 'px'});
    var zigzagWidth = $('.bordercolumn').width();
    $('#cornertop').css({
        marginTop: (zakazH2Height - 2 * zakazBorderWidth) + 'px',
        marginLeft: (zakazBorderWidth) + 'px',
        height: (wh - statistikaHeight - zakazH2Height) + 'px'});

    $('#cornerbottom').css({
        marginRight: (zigzagWidth - 2 * zakazBorderWidth + 1) + 'px',
        height: (zakazBorderWidth) + 'px'});

    $('.button_stat').css('width', statistikaHeight + 'px');

    var textStatWidth = $('.statistika').width() - statistikaHeight  -(zigzagWidth - 3 * zakazBorderWidth + 1);
    $('.text_stat').css({width: (textStatWidth) + 'px',height:statistikaHeight+'px'});
}





function popupDialog(selector, title) {
    var w = $(window).width();
    var h = $(window).height();
    var width = (w < 800 ? Math.round(w * 0.9) : 780);
    $(selector).dialog({
        //position: ["center", "top"],
        position:{ my: "center top", at: "center top", of: document.getElementById('sellerPage') },
        title: title,
        modal: true,
        draggable:false,
        show: 'slide',
        width: width + 'px',
        //close:popupDialogClosed,
        create: function (event, ui) {
            $("body").css({overflow: 'hidden'});
        },
        beforeClose: function (event, ui) {
            $("body").css({overflow: 'inherit'});
        }
    });
    //$('#popupDialogContent').attr('src', url);
    $("#popupDialog").css('height', Math.round(h * 0.8 - 2) + 'px');
    $('div.ui-dialog').css('height', (h*0.8) + 'px');
}



var extraLinksCount = 0;

$(window).load(function () {
    loadPackaging();
    $('#newOrder').click(newOrder);
    $('#gotCache').keyup(gotCacheChanged);
    getStats();
    newOrder();
    $('#cachPaid').click(paid('cash'));
    $('#cardPaid').click(paid('card'));

    $("#dialog").dialog({autoOpen: false, modal: true});

    $('#button_stat').click(function () {
        $("#extraLinks").slideToggle("slow");
        extraLinksCount = 1;
    });
    $(document).click(function (evene) {
        if (extraLinksCount > 0) {
            extraLinksCount--;
            return;
        }
        if ($('#extraLinks:visible').length > 0) {
            $("#extraLinks").slideToggle("slow");
        }
    });


    // --------------- extra links - begin -------------------------------------
    var lnk = $('<a href="javascript:void(\'Товар получен\')">Товар получен</a>');
    lnk.click(function () {
        $.get("index.php?r=supply/accept&pos_id=" + pos_id, function (data) {
            alert("ОК");
        });
    });
    
    $('#extraLinks').append(lnk);
    lnk = $('<div><a href="javascript:void(\'Возврат\')">Возврат</a></div>');
    lnk.click(function () {
        //alert("index.php?r=supply/accept&pos_id=" + pos_id);
        popupDialog('#popupDialog','Возврат');
        $('#popupDialog').load("index.php?r=sell/return&pos_id=" + pos_id);
    });
    $('#extraLinks').append(lnk);
    // --------------- extra links - end ---------------------------------------


    $('#zakazScrollUp').click(zakazScrollUpClick);
    $('#zakazScrollDown').click(zakazScrollDownClick);


    var dyWheel = 10;
    $('#zakazItems').on('mousewheel', function (event) {
        // console.log(event.deltaX, event.deltaY, event.deltaFactor);
        if (event.deltaY < 0) {
            if (!$('#zakazScrollUp').hasClass('active'))
                return;
            $('#zakazItemsPanel').animate({marginTop: "-=" + dyWheel}, 30, function () {
                var mtp = parseInt($('#zakazItemsPanel').css('margin-top'));
                if ((window.dy + mtp) < 0) {
                    $('#zakazItemsPanel').animate({marginTop: "-" + window.dy + 'px'}, 300);
                    $('#zakazScrollUp').removeClass('active');
                }
                $('#zakazScrollDown').addClass('active');
            });
        }
        if (event.deltaY > 0) {
            if (!$('#zakazScrollDown').hasClass('active'))
                return;
            $('#zakazItemsPanel').animate({marginTop: "+=" + dyWheel}, 30, function () {
                var mtp = parseInt($('#zakazItemsPanel').css('margin-top'));
                if (mtp > 0) {
                    $('#zakazItemsPanel').animate({marginTop: '0px'}, 300);
                    $('#zakazScrollDown').removeClass('active');
                }
                $('#zakazScrollUp').addClass('active');
            });
        }
    });

    // add list of discounts
    var discountSelector = $('<select id="discountSelector">');
    var option;
    option = $('<option value=""></option>');
    discountSelector.append(option);
    for (var i in discounts) {
        option = $('<option value="' + i + '">' + discounts[i].discount_title + '</option>');
        discountSelector.append(option);
    }
    $('.discounts').append(discountSelector);
    discountSelector.change(function () {
        window.orderData.discount_id = $(this).val();
        updateOrderTotal();
    });


    // Таблица сдачи c разных купюр
    var calcRows=$('#calcRow');
    for(var bl=0; bl<bill.length; bl++){
        calcRows.append($('<span class="calcCell">'+bill[bl]+'&nbsp;'+currency+'</span>'));
        calcRows.append($('<span class="calcCell calcVal" data-val="'+bill[bl]+'">0&nbsp;'+currency+'</span>'));
    }
    



    adjustSizes();
});

