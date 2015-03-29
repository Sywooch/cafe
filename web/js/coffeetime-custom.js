






$(window).load(function () {
      
    var wh = $(window).height();
    var ww = $(window).width();
    window.categoryHeight = 137;
    window.statistikaHeight =  40;
    window.btnOplataHeight = 150;
    //window.calcHeight = 160;
    window.itogoHeight = 40;
    window.zakazScrollHeight = 20;
    window.zakazTopMargin = 3;
    window.discountHeight = 40;
    window.customerHeight = 40;
    window.zakazBorderWidth = 10;
    if(wh<=600){
        window.btnOplataHeight = 50;
        window.itogoHeight = 22;
        window.customerHeight = 25;
        window.discountHeight=30;
        //window.calcHeight = 60;
    }
    if( ww>=590 && ww<=980){
       window.itogoHeight = 30;
       window.customerHeight = 30;
    }
  
    //window.categoryHeight = window.categoryHeight ? window.categoryHeight : 127;
    //window.statistikaHeight = window.statistikaHeight ? window.statistikaHeight : 40;
    //window.btnOplataHeight = window.btnOplataHeight ? window.btnOplataHeight : 150;
    //window.calcHeight = window.calcHeight ? window.calcHeight : 160;
    //window.itogoHeight = window.itogoHeight ? window.itogoHeight : 40;
    //window.zakazScrollHeight = window.zakazScrollHeight ? window.zakazScrollHeight : 20;
    //window.zakazTopMargin = window.zakazTopMargin ? window.zakazTopMargin : 3;
    //window.discountHeight = window.discountHeight ? window.discountHeight : 40;
    //window.zakazBorderWidth = window.zakazBorderWidth ? window.zakazBorderWidth : 10;
    //window.customerHeight = window.customerHeight ? window.customerHeight : 40;

    var wh = $(window).height();
    //alert(wh);


    $('#categories').css({height: window.categoryHeight + 'px', marginBottom: 0});
    $('.statistika').css('height', window.statistikaHeight + 'px');

    var tovaryListHeight = wh - window.categoryHeight - window.statistikaHeight - window.zakazBorderWidth;
    $('#tovaryList').css('height', tovaryListHeight + 'px');



    // right column
    //$('#newOrder').css('height',statistikaHeight+'px');
    $('#newOrder').css('height', window.itogoHeight + 'px');

    //var newBtnHeight=$('#newOrder').height();

    //$('.oplata').css({height: btnOplateHeight+'px',bottom:newBtnHeight+'px'});
    $('.oplata').css({height: window.btnOplataHeight + 'px', bottom: '0px'});

    $('.raschet').css({height: (window.btnOplataHeight-2) + 'px'});

    $('.itogo').css({height: window.itogoHeight + 'px', bottom: (window.btnOplataHeight) + 'px'});

    $('.customer').css({height: window.customerHeight + 'px', bottom: ( window.itogoHeight + window.btnOplataHeight) + 'px'});
    var customerWidth=$('.customer').width();
    var customerLabelWidth=$('.customer').find('.col1').first().width();
    var customerBtnWidth=$('#clientTelBtn').width();
    $('#clientTel').css('width',(customerWidth-customerLabelWidth-customerBtnWidth-20)+'px');



    $('.discounts').css({height: window.discountHeight + 'px', bottom: (window.customerHeight + window.itogoHeight + window.btnOplataHeight) + 'px'});
    var discountsWidth=$('.discounts').width();
    var discountsLabelWidth=$('.discounts').find('.col1').first().width();
    $('#discountSelector').css('width',(discountsWidth-discountsLabelWidth-3)+'px');


    $('#zakazScrollUp').css({height: window.zakazScrollHeight + 'px'});
    $('#zakazScrollDown').css({height: window.zakazScrollHeight + 'px'});

    var zakazHeight = wh - window.customerHeight - window.discountHeight - window.itogoHeight - window.btnOplataHeight;
    $('.zakaz').css({height: zakazHeight + 'px'});

    var zakazH2Height = window.zakazBorderWidth + 1 * $('.zakaz h2').first().outerHeight();
    var zakazItemsHeight = zakazHeight - zakazH2Height - 2 * window.zakazScrollHeight - 2 * window.zakazTopMargin;

    $('#zakazItems').css({height: zakazItemsHeight + 'px', marginTop: window.zakazTopMargin + 'px', marginBottom: window.zakazTopMargin + 'px'});

    $('.bordercolumn').css({height: (wh - window.statistikaHeight) + 'px'});
    var zigzagWidth = $('.bordercolumn').width();
    $('#cornertop').css({
        marginTop: (zakazH2Height - 2 * window.zakazBorderWidth) + 'px',
        marginLeft: (window.zakazBorderWidth) + 'px',
        height: (wh - window.statistikaHeight - zakazH2Height) + 'px'});

    $('#cornerbottom').css({
        marginRight: (zigzagWidth - 2 * window.zakazBorderWidth + 1) + 'px',
        height: (window.zakazBorderWidth) + 'px'});

    $('.button_stat').css('width', window.statistikaHeight + 'px');

    var textStatWidth = $('.statistika').width() - window.statistikaHeight  -(zigzagWidth - 3 * window.zakazBorderWidth + 1)-12;
    $('.text_stat').css({width: (textStatWidth) + 'px',height:window.statistikaHeight+'px'});

})