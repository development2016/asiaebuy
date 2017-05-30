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
                        <li><a href="#captain" data-toggle="tab">ASIAEBUY</a></li>
                    </ul>
                </div>

                <div class="tab-content" style="min-height: 240px;">
                    <div class="tab-pane" id="captain">
                        <h4 class="info-text">What type of B2B would you want? </h4>
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="col-sm-6">
                                    <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Buyer">
                                        <?= Html::a('<div class="icon"><i class="material-icons">group</i></div>', ['site/buyer'], []) ?>
                                        <h6>Become A Buyer</h6>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="choice" data-toggle="wizard-radio" rel="tooltip" title="Seller">
                                        <?= Html::a('<div class="icon"><i class="material-icons">business</i></div>', ['site/seller'], []) ?>
                                        <h6>Become A Seller</h6>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
                <div class="wizard-footer">
                    <div class="pull-right">



                    </div>
                    <div class="pull-left">

                        <div class="footer-checkbox">
                            <div class="col-sm-12">
                              <div class="checkbox">

                                <?= Html::a('Back', ['site/login'], ['style' => 'color: #5c585d;']) ?>

                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div> <!-- wizard container -->
