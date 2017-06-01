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
            <div class="actions">
                <div class="btn-group">
                    <a class="btn blue btn-outline btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" > Actions
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => '']) ?>
             
                        </li>
                        <li>
                            <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
                                'class' => '',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </li>

                    </ul>
                </div>
            </div>



        </div>
        <div class="portlet-body">


            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'item_name',
                    'brands.brand',
                    'models.model',
                    'categorys.category',
                    'subs.sub_category',
                    'description',
                    'specification',
                    'leads.lead_time',
                    'cost',
                    'stock',
                    'quantity',
                    'discount',
                    'publish',



                ],
            ]) ?>

        </div>
     </div>
      </div>
 </div>

