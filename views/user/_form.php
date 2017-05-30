<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$script = <<< JS
$(document).ready(function(){

    $('#username').on("input", function() {
      var dInput = $(this).val(); 
      $('#acc_name').val(dInput);

    });

}); 
JS;
$this->registerJs($script);



/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-lg-6 col-xs-12 col-sm-12">

        <?= $form->field($model, 'username')->textInput(['maxlength' => true,'id'=>'username']) ?>

        <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label('Password') ?>

    </div>
    <div class="col-lg-6 col-xs-12 col-sm-12">

        <?php if ($type == 'Buyer') { ?>

            <label class="mt-checkbox mt-checkbox-outline"> Administrator
                <input type="checkbox" value="3300" name="LookupRole[role_id][]">
                <span></span>
            </label>

            <label class="mt-checkbox mt-checkbox-outline"> Buyer
                <input type="checkbox" value="3100" name="LookupRole[role_id][]">
                <span></span>
            </label>

            <label class="mt-checkbox mt-checkbox-outline"> Approval
                <input type="checkbox" value="3200" name="LookupRole[role_id][]">
                <span></span>
            </label>

            <label class="mt-checkbox mt-checkbox-outline"> User
                <input type="checkbox" value="3400" name="LookupRole[role_id][]">
                <span></span>
            </label>

        <?php } else if($type == 'Seller') { ?>
        
        <label class="mt-checkbox mt-checkbox-outline"> Administrator
            <input type="checkbox" value="2200" name="LookupRole[role_id][]">
            <span></span>
        </label>

        <label class="mt-checkbox mt-checkbox-outline"> User
            <input type="checkbox" value="2100" name="LookupRole[role_id][]">
            <span></span>
        </label>
        <?php } ?>
        <br>

        <label class="control-label" for="user-account_name">Account Name</label>
        <div class="input-group input-large">

            <input type="text" class="form-control" id="acc_name" name="User[account_name]" readonly>
            <span class="input-group-addon">
                <?php echo $company->asia_ebuy_no = substr($company->asia_ebuy_no,0, strrpos($company->asia_ebuy_no, '@')); ?>
            </span>
        </div>
        <br>


        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

    </div>


    <?php ActiveForm::end(); ?>

