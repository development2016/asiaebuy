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
$this->title = 'Choose Approver  By Level';


$script = <<< JS
$(document).ready(function(){

    $('#level').on('change', function () {
        var level = $('#level').val();
        var buyer = $('#level').children('option:selected').data('buyer');
        var project = $('#level').children('option:selected').data('project');
        var seller = $('#level').children('option:selected').data('seller');
        var type = $('#level').children('option:selected').data('type');
        $.ajax({
            type: 'POST',
            url: 'level',
            data: 'level='+level+'&buyer='+buyer+'&project='+project+'&seller='+seller+'&type='+type,
            success: function(data) {
                $(".info-complete").html(data);
   

            }
        })

    });



}); 
JS;
$this->registerJs($script);


?>
<div class="project-form">

<h3><?= Html::encode($this->title) ?></h3>
<br>
<label>You Have <label class="font-red-sunglo"><?= count($approval) ?></label> Approver, Please Choose Level Of Approver</label>
<span>

	<select class="form-control input-xsmall " id="level" name="level">
		<option>-</option>
		<?php for ($i=1; $i <= count($approval) ; $i++) { ?>
			<option value="<?= $i; ?>" 
			data-buyer="<?= $buyer ?>"  
			data-project="<?= $project ?>" 
			data-seller="<?= $seller ?>"
			data-type="<?= $type ?>"  

			 ><?= $i; ?></option>
		<?php } ?>
	</select>
	<br>


    <div class="info-complete">
    	
    </div>


</span>
</div>