<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
    <li class="active">Items</li>
</ol>
</div>

<div class="row">

    <div  class="col-lg-12 col-xs-12 col-sm-12">


    <div class="portlet light bordered">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-bubbles font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">Items</span>

            </div>
            <?= Html::a('Create Item', ['create'], ['class' => 'btn blue btn-sm pull-right']) ?>
        </div>
        <div class="portlet-body">
            <?php Pjax::begin(); ?>    
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'label' => 'Details',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return '<table class="table table-bordered table-hover" >
                                            <tr>
                                                <th style="background-color: #ecf6ff;"">Image</th>
                                                <td ><img src="'.Yii::$app->request->baseUrl.'/image/image-not-found.png" style="witdh:150px;height:150px;"></td>
                                                <th style="background-color: #ecf6ff;"">Item</th>
                                                <td colspan="5">'.$data->item_name.'</td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #ecf6ff;"">Brand</th>
                                                <td>'.$data->brands['brand'].'</td>
                                                <th style="background-color: #ecf6ff;"">Model</th>
                                                <td  colspan="5">'.$data->models['model'].'</td>
                                            </tr>
                                            <tr >
                                                <th style="background-color: #ecf6ff;"">Specification</th>
                                                <td style="max-width:500px;" colspan="5">'.$data->specification.'</td>

                                            </tr>
                                            <tr>
                                                <th style="background-color: #ecf6ff;"">Description</th>
                                                <td style="max-width:500px;" colspan="5">'.$data->description.'</td>
                                            </tr>
                                            <tr>
                                                <th style="background-color: #ecf6ff;"">Group</th>
                                                <td style="max-width:500px;">'.$data->groups->group.'</td>
                                                <th style="background-color: #ecf6ff;"">Category</th>
                                                <td style="max-width:500px;">'.$data->categorys->category.'</td>
                                                <th style="background-color: #ecf6ff;"">Sub Category</th>
                                                <td style="max-width:500px;">'.$data->subs->sub_category.'</td>

                                            </tr>
                                        </table>';




                            }
                        ],

                        'stock',
                        [
                            'label' => 'Cost',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return '<span>Price : '.$data->cost.'</span><br><span> Discount : '.$data->discount.'% </span>';

                            }
                        ],
                        'quantity',
                        [
                            'label' => 'Publish',
                            'attribute' => 'publish',
                            'format'=>'raw',
                            'value'=>function ($data) {
                                if ($data->publish == 'Publish') {

                                    return '<a href="javascript:;" class="btn btn-sm green-jungle">'.$data->publish.'</a>';

                                } elseif ($data->publish == 'Off') {

                                    return '<a href="javascript:;" class="btn btn-sm red-mint">'.$data->publish.'</a>';

                                } 
                            }
                        ],

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            <?php Pjax::end(); ?>

        </div>
    </div>
    </div>
</div>
