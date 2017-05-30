<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\LookupTypeOfBusiness;
use app\models\LookupBank;

$this->title = 'Welcome To AsiaEBuy';
$this->params['breadcrumbs'][] = $this->title;


$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model2->country])->asArray()->all(), 'id', 'state');
$type_of_business = ArrayHelper::map(LookupTypeOfBusiness::find()->asArray()->all(), 'type_of_business', 'type_of_business');
$bank = ArrayHelper::map(LookupBank::find()->asArray()->all(), 'bank', 'bank');


?>

    <!--      Wizard container        -->
    <div class="wizard-container">
        <div class="card wizard-card" data-color="blue" id="wizard">
            <?php $form = ActiveForm::begin(); ?>
        <!--        You can switch " data-color="blue" "  with one of the next bright colors: "green", "orange", "red", "purple"             -->

            	<div class="wizard-header">
                	<h3 class="wizard-title">
                		<?= Html::encode($this->title) ?>
                	</h3>
					<h5>This information will let us know more about you.</h5>
            	</div>
				<div class="wizard-navigation">
					<ul>
                        <li><a href="#details" data-toggle="tab">Basic</a></li>
                        <li><a href="#captain" data-toggle="tab">Account</a></li>

                    </ul>
				</div>

                <div class="tab-content">
                    <div class="tab-pane" id="details">

                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="info-text"> Basic Information </h4>
                            </div>

                            <div class="col-sm-7 col-sm-offset-1">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">work</i>
                                    </span>
                                	<div class="form-group label-floating">
                                		<label class="control-label">Company Name <small>(required)</small></label>
                                        <input name="Company[company_name]" type="text" class="form-control">

                                	</div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group label-floating">
                                	<label class="control-label">Resgisteration No </label>
                                    <input name="Company[company_registeration_no]" type="text" class="form-control">

                                </div>
                            </div>

                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="form-group label-floating">
                                    <label class="control-label">Address</label>
                                    <textarea class="form-control" name="Company[address]" placeholder="" rows="4"></textarea>

                                </div>
                            </div>

                            <div class="col-sm-2 col-sm-offset-1">
                            	<div class="form-group label-floating">
                            		<label class="control-label">Postal Code</label>
                                    <input name="Company[zip_code]" type="text" class="form-control">

                            	</div>
                            </div>
                            <div class="col-sm-3">
                            	<div class="form-group label-floating">
                            		<?= $form->field($model2, 'country')->dropDownList(
                                        $country, 
                                    [
                                        'prompt' => '-Select Country-',
                                        'class' => 'form-control',
                                        'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/site/state','id'=>'']).'"+$(this).val(), function(data){$("select#state-id").html(data);})',

                                    ])->label(false) ?>



                            	</div>
                            </div>

                            <div class="col-sm-3">
            			        <div class="form-group label-floating">

                                    <?= $form->field($model2, 'state')->dropDownList(
                                        $state, 
                                    [
                                        'prompt' => '-Select State-',
                                        'class' => 'form-control',
                                        'id'=> 'state-id',

                                    ])->label(false) ?>


                            	</div>

                            </div>

                            <div class="col-sm-2">
                            	<div class="form-group label-floating">
                            		<label class="control-label">City</label>
                                    <input name="Company[city]" type="text" class="form-control">

                            	</div>
                            </div>

                            <div class="col-sm-5 col-sm-offset-1">
                            	<div class="form-group label-floating">
									<?= $form->field($model2, 'type_of_business')->dropDownList(
                                        $type_of_business, 
                                    [
                                        'prompt' => '-Select Type Of Business-',
                                        'class' => 'form-control',

                                    ])->label(false) ?>

                            	</div>
                            </div>

                            <div class="col-sm-2">
                            	<div class="form-group label-floating">
                            		<label class="control-label">Tax No</label>
                                    <input name="Company[tax_no]" type="text" class="form-control">

                            	</div>
                            </div>





                        </div>



                    </div>
                    <div class="tab-pane" id="captain">
                        <h4 class="info-text">User Account Information </h4>
                        <div class="row">

                        	<div class="col-sm-9 col-sm-offset-1">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">email</i>
                                    </span>

                                	<div class="form-group label-floating">
                                		<label class="control-label">Email <small>(required)</small></label>
                                        <input name="User[email]" type="email" class="form-control">

                                	</div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-sm-offset-1">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">perm_identity</i>
                                    </span>

                                    <div class="form-group label-floating">
                                    	<label class="control-label">Username <small>(required)</small></label>
                                        <input name="User[username]" type="text" class="form-control">

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-sm-offset-1">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                    <div class="form-group label-floating">
                                    	<label class="control-label">Password <small>(required)</small></label>
                                        <input name="User[password_hash]" type="password" class="form-control">

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>


                </div>

            	<div class="wizard-footer">
                	<div class="pull-right">
                        <input type='button' class='btn btn-next btn-fill btn-info btn-wd' name='next' value='Next' />
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-finish btn-fill btn-info btn-wd', 'name' => 'register-button']) ?>

                    </div>
                    <div class="pull-left">
                        <input type='button' class='btn btn-previous btn-fill btn-default btn-wd' name='previous' value='Previous' />

						<div class="footer-checkbox">
							<div class="col-sm-12">
							  <div class="checkbox">
							  	<?= Html::a('Back', ['site/register'], ['style' => 'color: #5c585d;']) ?>
							  </div>
						  </div>
						</div>
                    </div>
                    <div class="clearfix"></div>
            	</div>
            <?php ActiveForm::end(); ?>
        </div>
    </div> <!-- wizard container -->