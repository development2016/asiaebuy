<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'title',
            'description',
            'due_date',
            'project_no',
            'type_of_project',
            'buyer_id',
            'approval_id',
            'approve_date',
            'enter_by',
            'update_by',
            'date_create',
            'date_update',
        ],
    ]) ?>

</div>
