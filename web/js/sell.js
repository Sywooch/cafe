
window.packagingData={};
window.orderData={};
window.orderUpdateEnabled=true;
window.dy=-1;

function showMessage(){
    if(confirm("Заказ уже обработан.\nСоздать новый заказ?")){
        newOrder();
    }
}


function loadPackaging(){
    jQuery.ajax( 'index.php?r=sell/packaging&pos_id='+pos_id+'&t='+Math.random() , {
        dataType:'json',
        success:function(data){
            //var i, cnt;
            // console.log(data);
            window.packagingData=data;
            
            // draw categories:
            drawCategories(data);
            
            // draw basic packaging
            drawBasicPackaging(data);

            // draw additional 
            //drawAdditionalPackaging(data);
        }
    } );
}

function drawCategories(data){
    // 1. get list of categories
    var categories={};
    for(i=0, cnt=data.packagingBasic.length; i<cnt; i++ ){
        categories[data.packagingBasic[i].category_id]=0;
    }
    // 2 fill-in category data structure
    for(i=0, cnt=data.category; i<cnt; i++){
        categories[data.category[i].category_id]=data.category[i];
    }
    // 3 get active category
    var activeCategoryId=-1;
    var activeCategory=$('#categories').find('.active');
    if(activeCategory.length>0){
        activeCategoryId=1*activeCategory.first().attr('data-category_id');
    }
    if(activeCategoryId<0){
        activeCategoryId=data.category[0].category_id;
    }
    // console.log('activeCategoryId='+activeCategoryId);
    // 4 draw list of categories
    $('#categories').empty();
    for(i=0, cnt=data.category.length; i<cnt; i++ ){
        var element=domOneCategory(data.category[i]);
        if(activeCategoryId == data.category[i].category_id ){
            element.addClass('active');
            var skin=element.attr('data-category_skin');
            //$('#tovaryList').removeClass().addClass('tovary').addClass(skin);
            $('#packagingBasic').removeClass().addClass('tovar tov_1 '+skin);
        }
        $('#categories').append(element);
    }
}

function domOneCategory(item){
    var element=$('<div class="tip"></div>');
    element.addClass(item.category_skin);
    element.attr('data-category_id',item.category_id);
    element.attr('data-category_skin',item.category_skin);
    element.click(categoryClicked);
    var html='';
    html+='<h5>';
    html+=item.category_title;
    html+='</h5>';
    element.html(html);
    return element;
}

function categoryClicked(event){
    // alert('clicked!');
    // remove active class
    $('#categories').find('.active').removeClass('active');
    // set active class to current category
    var element=$(this);
    element.addClass('active');
    var skin=element.attr('data-category_skin');
    $('#packagingBasic').removeClass().addClass('tovar tov_1 '+skin);
    // re-draw packagingBasic
    drawBasicPackaging(window.packagingData);
}


function packagingClickedDisabled(){
    alert('Не хватает составляющих, чтобы продать продукт.');
}

function domOnePackaging(item){
    var element=$('<div class="produkt_block"></div>');
    element.attr('data-packaging_id',item.packaging_id);
    element.attr('data-packaging_price',item.packaging_price);
    if(item.packaging_is_available){
        element.click(packagingClicked);
    }else{
        element.addClass('disabled');
        //element.click(packagingClickedDisabled); 
        element.click(packagingClicked);
    }
    
    var html='';
    html+='<div class="produkt">';
    html+=item.imageThumb;
    html+='<div>';
    html+=item.packaging_title+'; '+item.packaging_price+"&nbsp;"+currency;
    html+='</div>';
    html+='</div>';
    element.html(html);
    return element;
}

function drawBasicPackaging(data){
    var activeCategoryId=-1;
    var activeCategory=$('#categories').find('.active');
    if(activeCategory.length>0){
        activeCategoryId=1*activeCategory.first().attr('data-category_id');
    }
    if(activeCategoryId<0){
        activeCategoryId=data.category[0].category_id;
    }

    $('#packagingBasic').empty();
    for(i=0, cnt=data.packagingBasic.length; i<cnt; i++ ){
        if(data.packagingBasic[i].category_id==activeCategoryId){
            // console.log('drawn:'+data.packagingBasic[i].packaging_title);
            var element=domOnePackaging(data.packagingBasic[i]);
            $('#packagingBasic').append(element);
        }else{
            // console.log('skiped:'+data.packagingBasic[i].packaging_title);
        }
    }
    
    //$("#packagingBasic").mCustomScrollbar({
    //   axis:"y", // vertical scrollbar
    //   theme:'dark-3',
    //   mouseWheel:true
    //});
}

function drawAdditionalPackaging(data){
    $('#packagingAdditional').empty();
    for(i=0, cnt=data.packagingAdditional.length; i<cnt; i++ ){
        var element=domOnePackaging(data.packagingAdditional[i]);
        $('#packagingAdditional').append(element);
    }
    //$("#packagingAdditional").mCustomScrollbar({
    //   axis:"y", // vertical scrollbar
    //   theme:'dark-3',
    //   mouseWheel:true
    //});
}

function gotCacheChanged(){
    var gotCache=$('#gotCache').val();
    var orderTotal=$('#orderTotal').text();
    var sdacha=gotCache-orderTotal;
    if(sdacha<0){
        $('#sdacha').empty().html('Покупатель должен дать больше');
    }else{
        $('#sdacha').empty().html(sdacha+' '+currency);
    }
}

function getStats(){
    jQuery.ajax( 'index.php?r=sell/sellerstats&seller_id='+seller_id+'&t='+Math.random() , {
        dataType:'json',
        success:function(data){
            // console.log(data);
            $('#sellerName').html(data.sysuser_fullname);
            $("#ordersCount").html(data.count);
            $("#ordersTotal").html(data.total);
            $("#sellerCommissionFee").html(data.comission);
        }
    } );
}

function newOrder(){
    $('#zakazItemsPanel').empty();
    $('#gotCache').attr('value','');
    $('#sdacha').empty().html(0);
    $('#orderTotal').html(0);
    // get new zakaz number
    jQuery.ajax( 'index.php?r=sell/ordernumber&pos_id='+pos_id+'&t='+Math.random() , {
        dataType:'json',
        success:function(data){
            $('#zakazId').empty().html(data.id);
            window.orderData={order_day_sequence_number:data.id};
            window.orderUpdateEnabled=true;
        }
    });
    
}

function packagingClicked(event){
    if(!window.orderUpdateEnabled){
        showMessage();
        return;
    }
    //alert('clicked!');
    var element=$(this);
    // get data
    var packaging_id=element.attr('data-packaging_id');
    // console.log(packaging_id+' clicked');
    // search packaging by id
    //console.log(window.packagingData);
    var dat,i,packaging=false;
    dat=window.packagingData.packagingAdditional;
    for(i=0;i<dat.length && !packaging;i++){
        if(packaging_id===dat[i].packaging_id){
            packaging=dat[i];
        }
    }
    dat=window.packagingData.packagingBasic;
    for(i=0;i<dat.length && !packaging;i++){
        if(packaging_id===dat[i].packaging_id){
            packaging=dat[i];
        }
    }
    // console.log(packaging);
    if(packaging){
        addPackagingToOrder(packaging);
        updateOrderTotal();
    }
    
}

function addPackagingToOrder(packaging){
    // add to order
    if(window.orderData[packaging.packaging_id]){
        window.orderData[packaging.packaging_id].count++;
    }else{
        window.orderData[packaging.packaging_id]={
            count:1,
            packaging:packaging
        }
    }

    var orderItem=$('#orderItem'+packaging.packaging_id);
    if(orderItem.length==0){
        var dom=$('<div class="stroka_zakaza"></div>');
        dom.attr('id','orderItem'+packaging.packaging_id);
        
        var html;
        html=$('<div class="nazvanie">'+packaging.packaging_title+'</div>');
        dom.append(html);

        html=$('<div class="minus" data-packaging_id="'+packaging.packaging_id+'">&ndash;</div>');
        html.click(minusitem);
        dom.append(html);

        html=$('<div class="kolichestvo" id="orderItem'+packaging.packaging_id+'count">1</div>');
        dom.append(html);

        html=$('<div class="plus" data-packaging_id="'+packaging.packaging_id+'">+</div>');
        html.click(plusitem);
        dom.append(html);
        
        html=$('<div class="cena">&nbsp;&times;&nbsp;'+packaging.packaging_price+' '+currency+'</div>');
        dom.append(html);
        
        html=$('<div class="otmena" data-packaging_id="'+packaging.packaging_id+'">&times;</div>');
        html.click(removeitem);
        dom.append(html);
	
        $('#zakazItemsPanel').append(dom);
        
        reactiveteScroller();
        $('#zakazItemsPanel').animate({ marginTop: "-"+window.dy+'px' }, 300);
        //$("#zakazItems").mCustomScrollbar({
        //   axis:"y", // vertical scrollbar
        //   theme:'dark-3',
        //   mouseWheel:true
        //});
    }else{
        $('#orderItem'+packaging.packaging_id+'count').html(window.orderData[packaging.packaging_id].count);
        
    }
    
    updateOrderTotal();
}

function plusitem(event){
    if(!window.orderUpdateEnabled){
        showMessage();
        return;
    }
    var el=$(this);
    var packaging_id=el.attr('data-packaging_id');
    window.orderData[packaging_id].count++;
    $('#orderItem'+packaging_id+'count').html(window.orderData[packaging_id].count);
    
    updateOrderTotal();
}

function minusitem(event){
    if(!window.orderUpdateEnabled){
        showMessage();
        return;
    }
    var el=$(this);
    var packaging_id=el.attr('data-packaging_id');
    window.orderData[packaging_id].count--;
    
    if(window.orderData[packaging_id].count>0){
        $('#orderItem'+packaging_id+'count').html(window.orderData[packaging_id].count);    
    }else{
        delete(window.orderData[packaging_id]);
        $('#orderItem'+packaging_id).remove();  
    }
    
    updateOrderTotal();
}

function removeitem(event){
    if(!window.orderUpdateEnabled){
        showMessage();
        return;
    }
    var el=$(this);
    var packaging_id=el.attr('data-packaging_id');    
    delete(window.orderData[packaging_id]);
    $('#orderItem'+packaging_id).remove();  
    
    updateOrderTotal();
    
    reactiveteScroller();
}

function updateOrderTotal(){
    //alert('updateOrderTotal');
    var total=0;
    //console.log(window.orderData);
    for(var packaging_id in window.orderData){
        if(isNaN(packaging_id)){
           continue; 
        }
        total+=window.orderData[packaging_id].count * window.orderData[packaging_id].packaging.packaging_price;
    }
    $('#orderTotal').empty().html(total);
    gotCacheChanged();
}


function paid(paymentTypeName){

    return function (){

        if(!window.orderUpdateEnabled){
            showMessage();
            return;
        }

        var order_packaging={};
        var nItems=0;
        for(var packaging_id in window.orderData){
            if(isNaN(packaging_id)){
               continue; 
            }
            order_packaging[packaging_id]=window.orderData[packaging_id].count;
            nItems++;
        }
        
        if(nItems==0){
            return;
        }
        
        
        // block buttons
        window.orderUpdateEnabled=false;
        $( "#dialog" ).dialog( "open" );

        
        var post={
            // r:'sell/createorder',
            order:{
                order_day_sequence_number:window.orderData.order_day_sequence_number,
                order_payment_type:paymentTypeName,
                discount_id:0,
                order_discount:0,
                order_packaging:order_packaging
            }
        };

        jQuery.ajax( 'index.php?r=sell/createorder&pos_id='+pos_id , {
            //dataType:'json',
            type:"POST",
            data:post,
            success:function(data){
                // console.log(data);
                getStats();
                $( "#dialog" ).dialog( "close" );
                printReceipt();
                loadPackaging();
                newOrder();
            }
        } );
    }
}

function zakazScrollDownClick(){
        if(!$('#zakazScrollDown').hasClass('active')) return;
		$('#zakazItemsPanel').animate({ marginTop: "+=20" }, 300,function(){
		    var mtp=parseInt($('#zakazItemsPanel').css('margin-top'));
			if( mtp>0){
			   $('#zakazItemsPanel').animate({ marginTop: '0px' }, 300);
			   $('#zakazScrollDown').removeClass('active');
			}
			$('#zakazScrollUp').addClass('active');
		});	
	};

function zakazScrollUpClick(){
        if(!$('#zakazScrollUp').hasClass('active')) return;
	 	$('#zakazItemsPanel').animate({ marginTop: "-=20" }, 300,function(){
		    var mtp=parseInt($('#zakazItemsPanel').css('margin-top'));
			if( (window.dy+mtp)<0){
			   $('#zakazItemsPanel').animate({ marginTop: "-"+window.dy+'px' }, 300);
			   $('#zakazScrollUp').removeClass('active');
			}
			$('#zakazScrollDown').addClass('active');
		});	
};


function reactiveteScroller(){
    $('#zakazScrollUp').removeClass('active');
    $('#zakazScrollDown').removeClass('active');
    var zh=$('#zakazItems').height();
    var ph=$('#zakazItemsPanel').height();
    window.dy=ph-zh;
    if(window.dy>0){
        $('#zakazScrollDown').addClass('active');
    }else{
        $('#zakazItemsPanel').animate({ marginTop: '0px' }, 300);
    }
}


function printReceipt(){
    return;
    //
        jQuery.ajax( printerUrl , {
            //dataType:'json',
            type:"POST",
            //data:post,
            success:function(data){
                // console.log(data);
                //getStats();
                //$( "#dialog" ).dialog( "close" );
            }
        } );
}



function adjustSizes(){

   var categoryHeight=125;
   var statistikaHeight=50;
   var btnOplateHeight=150;
   var calcHeight=90;
   var itogoHeight=35;
   var zakazScrollHeight=20;
   var zarazTopMargin=3;

   var wh=$(window).height();
   //alert(wh);
   
   
   $('#categories').css({height:categoryHeight+'px', marginBottom:0});
   $('.statistika').css('height',statistikaHeight+'px')
   
   var tovaryListHeight=wh-categoryHeight-statistikaHeight;
   $('#tovaryList').css('height',tovaryListHeight+'px');

   $('.button_stat').css('width',statistikaHeight+'px');

   var textStatWidth=$('.statistika').width()-statistikaHeight;
   $('.text_stat').css('width',(textStatWidth-20)+'px');

   $('#newOrder').css('height',statistikaHeight+'px');

   var newBtnHeight=$('#newOrder').height();

   $('.oplata').css({height: btnOplateHeight+'px',bottom:newBtnHeight+'px'});

   $('.raschet').css({height:calcHeight+'px',bottom: (newBtnHeight+btnOplateHeight)+'px'});
   
   $('.itogo').css({height:itogoHeight+'px',bottom: (newBtnHeight+btnOplateHeight+calcHeight)+'px'});
   
   $('#zakazScrollUp').css({height:zakazScrollHeight+'px'});
   $('#zakazScrollDown').css({height:zakazScrollHeight+'px'});
   
   var zakazH2Height=$('.zakaz h2').first().outerHeight();
   var zakazItemsHeight= wh - zakazH2Height-2*zakazScrollHeight-itogoHeight-calcHeight-btnOplateHeight-newBtnHeight-2*zarazTopMargin;
   
   $('#zakazItems').css({height:zakazItemsHeight+'px',marginTop:zarazTopMargin+'px',marginBottom:zarazTopMargin+'px'});
}








var extraLinksCount=0;

$(window).load(function(){
    loadPackaging();
    $('#newOrder').click(newOrder);
    $('#gotCache').keyup(gotCacheChanged);
    getStats();
    newOrder();
    $('#cachPaid').click(paid('cash'));
    $('#cardPaid').click(paid('card'));
    
    $( "#dialog" ).dialog({ autoOpen: false, modal: true });
    
    $('#button_stat').click(function(){
        $( "#extraLinks" ).slideToggle("slow");
        extraLinksCount=1;
    });
    $(document).click(function(evene){
        if(extraLinksCount>0){
            extraLinksCount--;
            return;
        }
        if($('#extraLinks:visible').length > 0){
            $( "#extraLinks" ).slideToggle("slow");
        }
    });
    
    var lnk=$('<a href="javascript:void(\'Товар получен\')">Товар получен</a>');
    lnk.click(function(){
        $.get( "index.php?r=supply/accept&pos_id="+pos_id, function( data ) {
            alert( "ОК" );
        });
    });
    $('#extraLinks').append(lnk);


    $('#zakazScrollUp').click(zakazScrollUpClick);
    $('#zakazScrollDown').click(zakazScrollDownClick);


    var dyWheel=10;
    $('#zakazItems').on('mousewheel', function(event) {
       console.log(event.deltaX, event.deltaY, event.deltaFactor);
       if(event.deltaY < 0){
          if(!$('#zakazScrollUp').hasClass('active')) return;
          $('#zakazItemsPanel').animate({ marginTop: "-="+dyWheel }, 30,function(){
               var mtp=parseInt($('#zakazItemsPanel').css('margin-top'));
               if( (window.dy+mtp)<0){
	          $('#zakazItemsPanel').animate({ marginTop: "-"+window.dy+'px' }, 300);
	          $('#zakazScrollUp').removeClass('active');
	       }
	       $('#zakazScrollDown').addClass('active');
	  });	
       }
       if(event.deltaY > 0){
               if(!$('#zakazScrollDown').hasClass('active')) return;
		$('#zakazItemsPanel').animate({ marginTop: "+="+dyWheel }, 30,function(){
		    var mtp=parseInt($('#zakazItemsPanel').css('margin-top'));
			if( mtp>0){
			   $('#zakazItemsPanel').animate({ marginTop: '0px' }, 300);
			   $('#zakazScrollDown').removeClass('active');
			}
			$('#zakazScrollUp').addClass('active');
		});	
       }
    });

    adjustSizes();
});

