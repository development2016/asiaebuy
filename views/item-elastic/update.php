<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Elastic */

$this->title = 'Update Item Elastic: ' . $model->item_name;
$this->params['breadcrumbs'][] = 'Update';
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

			    <?= $this->render('_form', [
			        'model' => $model,
			    ]) ?>


            </div>
        </div>
    </div>
</div>