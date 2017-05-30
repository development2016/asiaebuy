<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'item_name',
                    'brand',
                    'model',
                    'category',
                    'sub_category',
                    'description',
                    'specification',
                    'lead_time',
                    'cost',
                    'stock',
                    'quantity',
                    'publish',
                    'enter_by',
                    'update_by',
                    'date_create',
                    'date_update',
                    'owner_item',
                    'mongo_id'
       


                ],
            ]) ?>

            </div>
        </div>
    </div>
</div>