<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;



$this->title = 'Project';


?>
<h1><?= Html::encode($this->title) ?></h1>

                
<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['id'=>'id-title']) ?>

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
        <?= Html::submitButton($model->isNewRecord ? 'GEN. QUOTE' : 'GEN. QUOTE', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>





</div>