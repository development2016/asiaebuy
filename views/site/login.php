<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Welcome To AsiaEBuy';
$this->params['breadcrumbs'][] = $this->title;
?>
    <!--      Wizard container        -->
    <div class="wizard-container">
         <!--        You can switch " data-color="blue" "  with one of the next bright colors: "green", "orange", "red", "purple"             -->
        <div class="card wizard-card" data-color="red" id="wizard">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
            ]); ?>

       

                <div class="wizard-header">
                    <h3 class="wizard-title">
                        <?= Html::encode($this->title) ?>
                    </h3>
                    <h5>This information will let us know more about you.</h5>
                </div>
                <div class="wizard-navigation">
                    <ul>
                        <li><a href="#details" data-toggle="tab">ASIAEBUY</a></li>
                    </ul>
                </div>

                <div class="tab-content" style="min-height: 240px;">
                    <div class="tab-pane" id="details">
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="info-text"> Let's start with the basic details.</h4>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">perm_identity</i>
                                    </span>
                                    <div class="form-group label-floating">
                                        <label class="control-label">Username <small>(required)</small></label>
                                        <input name="LoginForm[username]" type="text" class="form-control username" id="username">
                                       
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">lock_outline</i>
                                    </span>
                                    <div class="form-group label-floating">
                                        <label class="control-label">Password <small>(required)</small></label>
                                        <input name="LoginForm[password]" type="password" class="form-control password" id="password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="wizard-footer">
                    <div class="pull-right">

                        <?= Html::a('Register', ['site/register'], ['style' => 'color: #5c585d;']) ?>
                        &nbsp;
                        <?= Html::submitButton('Login', ['class' => 'btn btn-finish btn-fill btn-danger btn-wd', 'name' => 'login-button']) ?>

                    </div>
                    <div class="pull-left">

                        <div class="footer-checkbox">
                            <div class="col-sm-12">
                              <div class="checkbox">
                                  <label>
                                      <input type="checkbox" name="optionsCheckboxes">
                                  </label>
                                  Forgot Password
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div> <!-- wizard container -->
