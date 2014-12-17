<?php
/**
 * Description of return
 *
 * @author dobro
 */
?>
<style type="text/css">
            #popupDialog h1{
                
            }
            #popupDialog h1, #popupDialog h3, #popupDialog h2, #popupDialog h4{
                
            }
            input.button, input[type="button"], input[type="submit"], input[type="reset"]{
                margin-top:0px;
            }
        </style>

    <?php

if($formPosted){
    if($model){
        ?>
        <br/>
        <h4 style="margin-bottom:20px;margin-left:0px;"><?=Yii::t('app','Choose packaging to return')?></h4>
        <?php
        $orderPackaging=$model->getOrderPackagings()->all();
        //var_dump($orderPackaging);
        
        function numberOptions($max){
            $tor="";
            for($i=0; $i<=$max; $i++){
                $tor.="<option value=\"$i\">$i</option>";
            }
            return $tor;
        }
        
        foreach($orderPackaging as $pack){
            echo "
                <div class=\"pack\"
                     data-order-id=\"{$pack->order_id}\" 
                     data-packaging-id=\"{$pack->packaging_id}\" 
                     data-packaging-number=\"{$pack->order_packaging_number}\"
                     data-packaging-price=\"{$pack->packaging_price}\">
                   
                   ".Yii::t('app','Return')
                   ." <select class=\"order_packaging_number\"
                              data-order-id=\"{$pack->order_id}\" 
                              data-packaging-price=\"{$pack->packaging_price}\"
                              data-packaging-id=\"{$pack->packaging_id}\">"
                              .numberOptions($pack->order_packaging_number)
                   ."</select>&nbsp;".Yii::t('app','items')."
                   &nbsp;&nbsp;&nbsp;
                   {$pack->packaging_title} {$pack->order_packaging_number} &times; {$pack->packaging_price} ".Yii::$app->params['currency']."
                </div>
                ";
                //<label><input type=\"checkbox\">".Yii::t('app','Return')."</label>
        }
        
        echo "
         <br/>
         <input type=\"button\" id=\"returnPacks\" value=\"".Yii::t('app','Return')."\">    
        ";
        ?>
    <script type="application/javascript">
        $('#returnPacks').click(function(){
    
            var order_packaging = {};
            var nItems = 0;
            $('.order_packaging_number').each(function(key,val){
                var ell=$(val);
                var nit=1*ell.val();
                if(nit==0){
                    return;
                }
                order_packaging[ell.attr('data-packaging-id')] = nit;
                nItems+=nit;
            });
            //console.log(order_packaging);
            if (nItems == 0) {
                return;
            }
            var post = {
                order: {
                    order_day_sequence_number: $('#orderDaySequenceNumber').val(),
                    order_payment_type: 'return',
                    discount_id: null,
                    order_packaging: order_packaging
                }
            };

            jQuery.ajax('index.php?r=sell/doreturn&pos_id=' + pos_id, {
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
                    $('#popupDialog').dialog('close');
                }
            });

        });
        </script>

        <?php
    }
}else{
    ?>
    <h1 style="margin-bottom:30px;margin-left:0px;"><?=Yii::t('app','Return payment')?></h1>
    <form action="index.php" method="get" id="orderNumberForm">
       <input type="hidden" name="r" value="sell/return">
       <input type="hidden" name="pos_id" value="<?=$pos_id?>">
       <input type="hidden" name="formPosted" value="1">
       <?=Yii::t('app','OrderDaySequenceNumber')?>
       <input type="text" class="form-control" style="width:100px;display:inline-block;" name="orderDaySequenceNumber" id="orderDaySequenceNumber">
       <input type="submit" class="btn btn-success" value="<?=Yii::t('app','Find order')?>">
    </form>
    <div id="orderInfo"></div>
    <script type="application/javascript">
        $('#orderNumberForm').submit(function( event ) {
            event.preventDefault();
            $('#orderInfo').empty();
            var postData=$('#orderNumberForm').serializeArray();
            $.ajax({
                url: "index.php",
                data: postData,
                type: "GET",
                dataType : "html",
                success: function( html ) {
                    //console.log(html);
                    $('#orderInfo').html(html);
                },
            });
        });
    </script>
    <?php

}

