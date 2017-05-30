<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAssetLogin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    
    'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons',
    'https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css',
    'wizard/assets/css/bootstrap.min.css',
    'wizard/assets/css/material-bootstrap-wizard.css',
    'wizard/assets/css/demo.css',



    ];
    public $js = [


    'wizard/assets/js/bootstrap.min.js',
    'wizard/assets/js/jquery.bootstrap.js',
    'wizard/assets/js/material-bootstrap-wizard.js',
    'wizard/assets/js/jquery.validate.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
