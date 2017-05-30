<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$script = <<< JS
$(document).ready(function(){

    var typingTimer;                //timer identifier
    var doneTypingInterval = 2000;  //time in ms, 2 second for example


    $('#myInput').on('keyup', function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });


    $('#myInput').on('keydown', function () {
      clearTimeout(typingTimer);
    });


    function doneTyping () {
        var inputVal = $('.show-search-div').val();

        $.ajax({
            type: 'POST',
            url: 'ajax',
            data: {value: inputVal},

            success: function(data) {

                $('.show-search-info').show();
                $('.info').html(data);

            }

        })



    }



}); 
JS;
$this->registerJs($script);



$this->title = 'Item In ElasticSearch DB';
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

    <div  class="col-lg-6 col-xs-6 col-sm-6">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">Normal Searching</span>

                </div>
              
            </div>
            <div class="portlet-body">
                <?php  echo $this->render('_search'); ?>

                 <span class="font-red-thunderbird">* this searching will go to another page when button clicked</span>

            </div>
        </div>

    </div>

    <div  class="col-lg-6 col-xs-6 col-sm-6">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">Ajax Searching</span>

                </div>
              
            </div>
            <div class="portlet-body form-inline">


                        <div class="form-group">
                            <label class="control-label" for="search">Search: </label>
                            <input id="myInput" name="search" placeholder="Search Here" class="form-control show-search-div" required value="" type="text">
                        </div>
                        <div class="form-group">


                        <span class="font-red-thunderbird">* this searching will auto search when key up</span>

                        </div>

            </div>
        </div>

    </div>




</div>

<div class="row">
        <div class='col-lg-12 col-xs-12 col-sm-12'>
     
                <div style="display: none;" class="show-search-info">
                <h3>Result Seaching AJAX</h3>
                            <div class="info">



                            </div>

                    </div>
                </div>
      
    </div>
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

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                 
                        'item_name',
                       // 'specification',
                        'brand',
                        'model',
                        'sub_category',
                        'category',
                        'group',
                        
                        //'mongo_id',
                        
                        ['class' => 'yii\grid\ActionColumn'],
       
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>