<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupTitle;
use dosamigos\datepicker\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
$title = ArrayHelper::map(LookupTitle::find()->asArray()->all(),'title','title'); 


$this->title = 'Project';

$script = <<< JS
$(document).ready(function(){

    $('.change').click(function(){
        var id = $(this).val();

        if (id == 1){

            $(".text-title").show();
            $(".dropdown-title").hide();
            $('button#change').text('Choose Title');
            $('button#change').val(2);
            $('#id-title').prop("disabled", false);
            $('#temp_id').prop("disabled", false);

            $("#title-id").prop("disabled", true);
            $("#temp_id_2").attr('disabled', 'disabled');

            

        } else if (id == 2){

            $(".text-title").hide();
            $(".dropdown-title").show();
            $('button#change').text('New Title');
            $('button#change').val(1);
            $("#title-id").prop("disabled", false);
            $("#temp_id_2").removeAttr('disabled');

            $("#id-title").prop("disabled", true);
            $("#temp_id").attr('disabled', 'disabled');


        }


    });


}); 
JS;
$this->registerJs($script);



?>
<h1><?= Html::encode($this->title) ?> <button class="btn btn-outline dark pull-right change" id="change" value="1">New Title</button></h1>

                
<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="text-title" style="display: none;">

        <?= $form->field($model2, 'title')->textInput(['id'=>'id-title','disabled'=>'disabled']) ?>
        <input type="hidden" name="temp" id="temp_id" value="2" disabled="disabled">

    </div>

    <div class="dropdown-title">
    <?= $form->field($model, 'title')->dropDownList($title, 
    [
        'prompt' => '-Please Choose-',
        'id'=> 'title-id'

    ]) ?>
    <input type="hidden" name="temp" id="temp_id_2" value="1">
    </div>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sellers[0][quantity]')->textInput()->label('Quantity') ?>

    <?= $form->field($model, 'due_date')->widget(
        DatePicker::className(), [
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
    ]);?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'REQUEST' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>





</div>