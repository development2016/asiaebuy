<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$script = <<< JS
$(document).ready(function(){


    $('.search-item').on('click', function () {
        var search = $('.url').val();
        $.ajax({
            type: 'POST',
            url: 'curl',
            data: {url: search},
            success: function(data) {
                $(".embed").show();
                $(".info-complete").html(data);
   

            }

        })


    });


    $('.url').on("input", function() {
      var dInput = $(this).val(); 
      $('.url_myspot').val(dInput);

    });






}); 
JS;
$this->registerJs($script);
$this->title = 'MySpot Buy';
?>


<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
    <li class="active"><?= Html::encode($this->title) ?></li>
</ol>
</div>
<div class="row">
    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= Html::encode($this->title) ?></span>
                </div>

            </div>
            <div class="portlet-body">



            <input type="text" id="input-search url" class="form-control input-search url" placeholder="Insert URL , Example : http://www.lazada.com.my">
            <br>
            <button class="btn blue search-item" type="button"><i class="icon-magnifier"></i> Enter </button>


            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase"> USING EMBED.LY / IFRAME</span>
                </div>

            </div>

            <div class="portlet-body ">

                <div class="row embed">
                    
                    <div class="col-md-5 info-complete">


                    </div>


                    <div class="col-md-7">

                    <?php $form = ActiveForm::begin(); ?>



                    <?= $form->field($model, 'title')->textInput(['id'=>'id-title','placeholder'=>'-New Title-']) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'due_date')->widget(
                        DatePicker::className(), [
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                    ]);?>

                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>




                    </div>
                    
                

                </div>

        
            </div>
        </div>
    </div>



</div>