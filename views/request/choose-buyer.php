<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Project No : '.$model[0]['project_no'];
$this->title = 'Choose Buyer To Assign';

?>
<div class="project-form">

<h3><?= Html::encode($this->title) ?></h3>
<br>
<span>
<?php $form = ActiveForm::begin(); ?>

	<div class="form-group">

		<div class="mt-checkbox-list">
			<?php foreach ($buyer_list as $key => $value) { ?>
		    <label class="mt-checkbox mt-checkbox-outline"> <?php echo $value['account_name'] ?>
		        <input type="checkbox" value="<?php echo $value['account_name'] ?>" name="Project[sellers][buyer][]">
		        <span></span>
		    </label>
		    <?php } ?>

		</div>


	</div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Choose' : 'Choose', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>



<?php ActiveForm::end(); ?>
</span>



	
</div>