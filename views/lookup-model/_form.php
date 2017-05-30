<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\LookupBrand;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\LookupModel */
/* @var $form yii\widgets\ActiveForm */
$brand = ArrayHelper::map(LookupBrand::find()->asArray()->all(),'id','brand'); 
?>

<div class="lookup-model-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'brand_id')->dropDownList(
        $brand, 
    [
        'prompt' => '-Select Model-',
        'class' => 'form-control',
        'id'=> 'brand_id',

    ])->label() ?>


    <?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
