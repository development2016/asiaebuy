<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="company-offline-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'company_name') ?>

    <?= $form->field($model, 'company_registeration_no') ?>

    <?= $form->field($model, 'address') ?>

    <?= $form->field($model, 'zip_code') ?>

    <?= $form->field($model, 'country') ?>

    <?= $form->field($model, 'state') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'telephone_no') ?>

    <?= $form->field($model, 'fax_no') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'website') ?>

    <?= $form->field($model, 'gst') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
