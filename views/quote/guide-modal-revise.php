<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Project No : '.$model[0]['project_no'];

?>
<div class="project-form">

<h3><?= Html::encode($this->title) ?></h3>
<br>
<span>Are Sure Want To Revise This Quotation ? </span>
<br>
<span>
	Quotation No : <b><?= $model[0]['sellers']['quotation_no']; ?></b>

	<br>
	<br>
	<span style="color: red;font-size: 12px;">
		Note : Once You Revise It Can`t Be Undo
	</span>
</span>



	<hr>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			
				<div class="form-group pull-right">

					<?= Html::a('Close', ['quote/index'],['class'=>'btn dark btn-outline']) ?>

 					<?= Html::a('Revise', ['quote/guide-revise',
 					'project'=>(string)$project,
 					'seller'=>$seller,
 					'buyer' => $buyer
 					],['class' => 'btn blue btn-outline modal_revise']) ?>

				
				</div>
		</div>

	</div>

	
</div>