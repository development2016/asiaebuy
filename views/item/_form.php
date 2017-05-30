<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupGroup;
use app\models\LookupBrand;
use app\models\LookupCategory;
use app\models\LookupSubCategory;
use app\models\LookupModel;
use app\models\LookupLeadTime;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
$group = ArrayHelper::map(LookupGroup::find()->asArray()->all(),'id','group'); 
$brand = ArrayHelper::map(LookupBrand::find()->asArray()->all(),'id','brand'); 

$category = ArrayHelper::map(LookupCategory::find()->where(['group_id'=>$model->group])->asArray()->all(), 'id', 'category');
$sub = ArrayHelper::map(LookupSubCategory::find()->where(['category_id'=>$model->category])->asArray()->all(), 'id', 'sub_category');

$models = ArrayHelper::map(LookupModel::find()->where(['brand_id'=>$model->brand])->asArray()->all(), 'id', 'model');
$lead = ArrayHelper::map(LookupLeadTime::find()->asArray()->all(),'id','lead_time'); 

//$j = LookupGroup::find()->select('*')->asArray()->column();

$script = <<< JS
$(document).ready(function(){

    $('.li-group').on('click', function () {
        var group = $(this).val();
        var label = $(this).attr('label');
        $.ajax({
            url: 'category',
            data: {id: group},
            success: function(data) {
                $("ul.ul-category").html(data);
                $(".info-group").show();
                $("span.group").html(label);
                $('#group').val(group);

            }

        })

    });

    $('.search-item').on('click', function () {
        $('.show-auto-complete-drill').show();
        $('.show-drill').hide();
        var search = $('#input-search').val();
        $.ajax({
            type: 'POST',
            url: 'suggestion',
            data: {word: search},
            success: function(data) {
                $(".info-complete").html(data);
   

            }

        })


    });

    $('#aTa').click(function() {
        if($(this).is(':checked'))
            $(".value-aTa").attr("disabled", "disabled"); 
        else
             $(".value-aTa").removeAttr("disabled"); 
            
    });

    $('#aTb').click(function() {
        if($(this).is(':checked'))
             $(".value-aTb").attr("disabled", "disabled"); 
        else
             $(".value-aTb").removeAttr("disabled"); 
    });


    $('.install').click(function() {
        if($(this).is(':checked'))
        {
             $('.required').prop("disabled", false);
             $('.not-required').prop("disabled", false);
             $(".info-required").show();
         }
        else
        {

             $('.required').prop("disabled", true);
             $('.not-required').prop("disabled", true);
             $('.value-required').attr("disabled", "disabled");
             $('.value-required-free').prop("disabled", true);
             $(".info-required").hide(); 
             $(".info-price-required").hide();
         }
    });

    $('.required').change(function(){
        if($(this).is(':checked'))
        {
             $('.value-required').prop("disabled", false);
             $('.value-required-free').prop("disabled", false);
             $(".info-price-required").show();
             $(".value-not-required").attr("disabled", "disabled"); 

         }


    });
    $('.not-required').change(function(){
        if($(this).is(':checked'))
        {
             $('.value-required').prop("disabled", true);
             $('.value-required-free').prop("disabled", true);
             $(".info-price-required").hide();
             $(".value-not-required").removeAttr("disabled"); 

         }

    });
    $('#value-required-free').click(function() {
        if($(this).is(':checked'))
             $(".value-required").attr("disabled", "disabled"); 
        else
             $(".value-required").removeAttr("disabled"); 
    });





}); 
JS;
$this->registerJs($script);
/*
Html::ul($group, ['item' => function($item, $index) {
    return Html::tag(
        'li',
        $item,
        ['value' => $index]
    );
}])
 */


?>

<div class="item-form">



<div class="row">
    <div class="col-lg-4 col-xs-12 col-sm-12">

        <div class="input-group">
            <input type="text" id="input-search" class="form-control input-search" placeholder="">
            <span class="input-group-btn">
                <button class="btn btn-default search-item" type="button"><i class="icon-magnifier"></i> Search</button>
            </span>
        </div>

    </div>
</div>

<br>

<div class="show-auto-complete-drill" style="display: none;">
    <div class="row">
        <div class="col-lg-12 col-xs-12 col-sm-12">

            <div class="portlet light bordered info-complete">



            
            </div>
        </div>
    </div>
</div>


<div class="show-drill">
    
    <div class="row">
        <div class="col-lg-4 col-xs-12 col-sm-12">
            <!-- BEGIN PORTLET-->
                <label class="uppercase bold">Group</label>
            <div class="portlet light bordered">


                <div class="portlet-body" style="max-height: 300px;height: 300px;overflow-y: auto">
                            
                            <ul class="ul-group">
                                <?php foreach ($group as $key => $value) { ?>
                                    <li class="li-group" value="<?php echo $key; ?>" label="<?php echo $value; ?>"><?php echo $value; ?></li>
                                <?php } ?>
                                

                            </ul>
                            
                    
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
        <div class="col-lg-4 col-xs-12 col-sm-12">
            <!-- BEGIN PORTLET-->
             <label class="uppercase bold">Category</label>
            <div class="portlet light bordered">

                <div class="portlet-body" style="max-height: 300px;height: 300px;overflow-y: auto">
                        
                     <ul class="ul-category">

                     </ul>
                            
                    
                </div>
            </div>
            <!-- END PORTLET-->
        </div>

        <div class="col-lg-4 col-xs-12 col-sm-12">
            <!-- BEGIN PORTLET-->
             <label class="uppercase bold">Sub Category</label>
            <div class="portlet light bordered">

                <div class="portlet-body" style="max-height: 300px;height: 300px;overflow-y: auto">
           
                    <ul class="ul-sub-category">

                     </ul>
                    
                </div>
            </div>
            <!-- END PORTLET-->
        </div>


    </div>







</div>

<br>





    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>



    <div class="row " >
        <div class="col-lg-4 col-xs-12 col-sm-12 info-group" style="display: none;">
            <span class="bold">Selected Group : </span>
            <span class="group bg-red-pink"> </span>
            <?= $form->field($model, 'group')->hiddenInput(['id'=>'group','class'=>'group'])->label(false) ?>

        </div>
        <div class="col-lg-4 col-xs-12 col-sm-12 info-category" style="display: none;">
            <span class="bold">Selected Category : </span>
            <span class="category bg-red-pink"> </span>
            <?= $form->field($model, 'category')->hiddenInput(['id'=>'category','class'=>'category'])->label(false) ?>
        </div>
        <div class="col-lg-4 col-xs-12 col-sm-12 info-sub-category" style="display: none;">
            <span class="bold">Selected Sub Category : </span>
            <span class="sub-category bg-red-pink"> </span>
            <?= $form->field($model, 'sub_category')->hiddenInput(['id'=>'sub_category','class'=>'sub_category'])->label(false) ?>
        </div>


    </div>




    <?= $form->field($model, 'item_name') ?>


    <?= $form->field($model, 'brand')->dropDownList(
        $brand, 
    [
        'prompt' => '-Select Brand-',
        'class' => 'form-control',
        'id' => 'brand-id',
        'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/item/brand','id'=>'']).'"+$(this).val(), function(data){$("select#model-id").html(data);})',

    ])->label() ?>


    <?= $form->field($model, 'model')->dropDownList(
        $models, 
    [
        'prompt' => '-Select Model-',
        'class' => 'form-control',
        'id'=> 'model-id',

    ])->label() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'specification')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'lead_time')->dropDownList($lead, 
    [
        'prompt' => '-Please Choose-',
        'id' => 'lead-time',

    ]) ?>

    <?= $form->field($model, 'cost') ?>

    <?= $form->field($model, 'stock')->dropDownList([ 'In Stock' => 'In Stock', 'Out Of Stock' => 'Out Of Stock', ], ['prompt' => '-Please Choose-','id'=>'stock']) ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'publish')->dropDownList([ 'Publish' => 'Publish', 'Off' => 'Off', ], ['prompt' => '-Please Choose-']) ?>

    <?= $form->field($model, 'discount') ?>


    <label>Shipping</label>
    <br>
    <?php
    // this for malaysia only
    if ($checkState->locate == 'East') {?>

        <table class="table table-bordered">
            <tr>
                <th>Location</th>
                <th>To</th>
                <th>Location</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>East</td>
                <td>To</td>
                <td>East</td>
                <td>
                   
                    <div class="form-group form-inline">
                         <input type="text" class="form-control input-small value-ete" id="value-ete" name="Item[shippings][eastToeast]">

                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" class="ete" id="ete" name="Item[shippings][eastToeast]" value="Free"> Free
                            <span></span>
                        </label>

                    </div>

      
                </td>
            </tr>
            <tr>
                <td>East</td>
                <td>To</td>
                <td>West</td>
                <td>
                    <div class="form-group form-inline">
                         <input type="text" class="form-control input-small value-etw" id="value-etw" name="Item[shippings][eastTowest]">

                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" class="etw" id="etw" name="Item[shippings][eastTowest]" value="Free"> Free
                            <span></span>
                        </label>


                    </div>

                </td>
            </tr>
        </table>


    
        
    <?php } elseif ($checkState->locate == 'West') { ?>

        <table class="table table-bordered">
            <tr>
                <th>Location</th>
                <th>To</th>
                <th>Location</th>
                <th>Price</th>
            </tr>
            <tr>
                <td>West</td>
                <td>To</td>
                <td>West</td>
                <td>
                   
                    <div class="form-group form-inline">
                         <input type="text" class="form-control input-small value-aTa" id="value-aTa" name="Item[shippings][aTa]">

                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" class="aTa" id="aTa" name="Item[shippings][aTa]" value="Free"> Free
                            <span></span>
                        </label>


                    </div>

      
                </td>
            </tr>
            <tr>
                <td>West</td>
                <td>To</td>
                <td>East</td>
                <td>
                    <div class="form-group form-inline">
                         <input type="text" class="form-control input-small value-aTb" id="value-aTb" name="Item[shippings][aTb]">

                        <label class="mt-checkbox mt-checkbox-outline">
                            <input type="checkbox" class="aTb" id="aTb" name="Item[shippings][aTb]" value="Free"> Free
                            <span></span>
                        </label>

                    </div>

                </td>
            </tr>
        </table>

    <?php } ?>



    <label class="mt-checkbox mt-checkbox-outline">
        <input type="checkbox" class="install" id="install" name=""> Commisioning , Installation, & Training Charge
        <span></span>
    </label>
    <br>

    <div class="row">

        <div  class="col-lg-1 col-xs-8 col-sm-12">
        </div>

        <div  class="col-lg-11 col-xs-8 col-sm-12 info-required" style="display: none;">
            <div class="form-group">
                <div class="mt-radio-inline">
                    <label class="mt-radio">

                        <input type="radio" name="Item[installations][installation]" id="required" class="required" value="required" disabled="disabled"> Required
                        <span></span>
                    </label>
                    <label class="mt-radio">
                        <input type="radio" name="Item[installations][installation]" id="not-required" class="not-required" value="not-required" disabled="disabled"> Not Required
                        <span></span>
                    </label>
                </div>
            </div>

            <div style="display: none;" class="info-price-required">

                    <div class="form-group form-inline">
                         <input type="text" class="form-control input-small value-required" id="value-required" name="Item[installations][installation_price]" disabled="disabled">

                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" class="value-required-free" id="value-required-free" name="Item[installations][installation_price]" value="Free" disabled="disabled"> Free
                        <span></span>
                    </label>


                    <input type="hidden" name="Item[installations][installation_price]" id="value-not-required" class="form-control input-small value-not-required" value="0">



                    </div>


                
            </div>




        </div>


    </div>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
