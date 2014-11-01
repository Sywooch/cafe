
var packagingData={};



function loadPackaging(){
    jQuery.ajax( 'index.php?r=sell/packaging&pos_id='+pos_id+'&t='+Math.random() , {
        dataType:'json',
        success:function(data){
            var i, cnt;
            // console.log(data);
            packagingData=data;
             
            // draw categories:
            drawCategories(data);
            
            // draw basic packaging
            drawBasicPackaging(data);

            // draw additional 
            drawAdditionalPackaging(data);
            
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
            $('#tovaryList').removeClass().addClass('tovary').addClass(skin);
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
    $('#tovaryList').removeClass().addClass('tovary').addClass(skin);
    // re-draw packagingBasic
    drawBasicPackaging(packagingData);
}


function domOnePackaging(item){
    var element=$('<div class="produkt_block"></div>');
    element.attr('data-packaging_id',item.packaging_id);
    element.attr('data-packaging_price',item.packaging_price);
    element.click(packagingClicked);
    var html='';
    html+='<div class="produkt">';
    html+=item.imageThumb;
    //html+='<p>';
    html+=item.packaging_title;
    //html+='</p>';
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
}

function drawAdditionalPackaging(data){
    $('#packagingAdditional').empty();
    for(i=0, cnt=data.packagingAdditional.length; i<cnt; i++ ){
        var element=domOnePackaging(data.packagingAdditional[i]);
        $('#packagingAdditional').append(element);
    }
}

function gotCacheChanged(){
    var gotCache=$('#gotCache').val();
    var orderTotal=$('#orderTotal').text();
    var sdacha=gotCache-orderTotal;
    if(sdacha<0){
        $('#sdacha').empty().html('Покупатель заплатил слишком мало');
    }else{
        $('#sdacha').empty().html(sdacha);
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
    $('#zakazItems').empty();
    $('#gotCache').attr('value','');
    $('#sdacha').empty().html(0);
    $('#orderTotal').html(0);
    // get new zakaz number
    jQuery.ajax( 'index.php?r=sell/ordernumber&pos_id='+pos_id+'&t='+Math.random() , {
        dataType:'json',
        success:function(data){
            $('#zakazId').empty().html(data.id);
        }
    });
}


function packagingClicked(event){
    alert('clicked!');
    var element=$(this);
    // get data
    // add to order
}


$(window).load(function(){
    loadPackaging();
    $('#newOrder').click(newOrder);
    $('#gotCache').keyup(gotCacheChanged);
    getStats();
    newOrder();
});

