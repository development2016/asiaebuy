<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTypeOfBusiness;
use app\models\LookupBank;
use app\models\LookupTerm;


$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
$type_of_business = ArrayHelper::map(LookupTypeOfBusiness::find()->asArray()->all(), 'type_of_business', 'type_of_business');
$bank = ArrayHelper::map(LookupBank::find()->asArray()->all(), 'bank', 'bank');
$term = ArrayHelper::map(LookupTerm::find()->asArray()->all(), 'term', 'term');



$this->title = 'Manage Company';
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

    <div  class="col-lg-12 col-xs-12 col-sm-12">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= Html::encode($this->title) ?></span>
                </div>

            </div>
            <div class="portlet-body">

            <?php $form = ActiveForm::begin(); ?>


                <div class="row">

                    <div  class="col-lg-12 col-xs-12 col-sm-12">
                    <?= $form->errorSummary($model); ?>

                    </div>
                </div>


                <div class="row">

                    <div  class="col-lg-6 col-xs-12 col-sm-12">

                        <?= $form->field($model, 'asia_ebuy_no')->textInput(['maxlength' => true,'readonly'=>true]) ?>

                        <?= $form->field($model, 'company_name') ?>

                        <?= $form->field($model, 'company_registeration_no') ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'website') ?>

                        <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'country')->dropDownList(
                            $country, 
                        [
                            'prompt' => '-Select Country-',
                            'class' => 'form-control',
                            'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/company/state','id'=>'']).'"+$(this).val(), function(data){$("select#state-id").html(data);})',

                        ]) ?>

                        <?= $form->field($model, 'state')->dropDownList(
                            $state, 
                        [
                            'prompt' => '-Select State-',
                            'class' => 'form-control',
                            'id'=> 'state-id',

                        ]) ?>

                        <?= $form->field($model, 'city') ?>

                        <?= $form->field($model, 'zip_code') ?>

                        <?= $form->field($model, 'tax_no') ?>

                        <?= $form->field($model, 'telephone_no') ?>

                        <?= $form->field($model, 'fax_no') ?>

                        <?= $form->field($model, 'term')->dropDownList(
                            $term, 
                        [
                            'prompt' => '-Select Term-',
                            'class' => 'form-control',

                        ]) ?>


                    </div>

                    <div  class="col-lg-6 col-xs-12 col-sm-12">

                        <?= $form->field($model, 'type_of_business')->dropDownList(
                            $type_of_business, 
                        [
                            'prompt' => '-Select Type Of Business-',
                            'class' => 'form-control',

                        ]) ?>

                        <?= $form->field($model, 'bank')->dropDownList(
                            $bank, 
                        [
                            'prompt' => '-Select Bank-',
                            'class' => 'form-control',

                        ]) ?>

                        <?= $form->field($model, 'bank_account_name') ?>

                        <?= $form->field($model, 'bank_account_no') ?>

                
                        <?= $form->field($model, 'keyword')->textarea(['rows' => 6]) ?>

                        <div class="form-group pull-right">
                            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>


                    </div>


                </div>

                <?php ActiveForm::end(); ?>



            </div>
        </div>
    </div>
</div>



