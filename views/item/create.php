<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = 'Create Item';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$script = <<< JS
$(document).ready(function(){

    $('.model').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

}); 
JS;
$this->registerJs($script);

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
                    <a class="btn blue btn-outline btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" title="This Shortcut For Lookup Setting"> Actions
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="" style="color: red" disabled>Brand</a>
                        </li>
                        <li>
                            <?= Html::a('Model',FALSE, [
                            'value'=>Url::to(['lookup-model/create']),
                            'class' => 'model','id'=>'model'
                            ]) ?>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <a href="" style="color: red">Group</a>
                        </li>
                        <li>
                            <a href="" style="color: red">Category</a>
                        </li>
                        <li>
                            <a href="" style="color: red">Sub Category</a>
                        </li>
                    </ul>
                </div>
            </div>


        </div>
        <div class="portlet-body">

    <?= $this->render('_form', [
        'model' => $model,
        'checkState' => $checkState
    ]) ?>

		</div>
	</div>
	</div>
</div>