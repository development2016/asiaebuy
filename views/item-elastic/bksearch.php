<?php

use yii\helpers\BaseStringHelper;
$this->title = 'Return Value Search';
?>
<?php $result = $dataProvider->getModels(); ?>
<h1>Search Result for <?php echo "<span class='label label-danger'>" . $query . "</span>" ?></h1>
<br>
    <div class='row'>
        <div class='col-lg-12 col-xs-12 col-sm-12'>


            <?php foreach ($result as $key) { ?>
            <div>
                <div class="portlet light ">

                <?php foreach ($key['_source'] as $key => $value) { ?>

                    <?php if ($key == "item_name") { ?>

                        <div class="portlet light ">
                   
                            <span class="caption-subject  bold uppercase">
                                <h3><b><?= $value; ?></b></h3>
                            </span>
                        </div>
                            

                    <?php } ?>
                    <?php if ($key == "specification") { ?>
                    <div class="portlet-body ">

                        <div class='well'><?= $value ?></div>
                        <div class='well well-large label-info'><?= $value ?></div>

                    </div>
                    <?php } ?>

                 <?php  } ?>


                </div>


            </div>
            <?php }?>




        </div>
    </div>

