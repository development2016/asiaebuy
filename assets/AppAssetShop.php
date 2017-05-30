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
class AppAssetShop extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

        'https://fonts.googleapis.com/css?family=Oswald:400,300,700',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
        'metronic/assets/global/plugins/morris/morris.css',
        'metronic/assets/global/plugins/fullcalendar/fullcalendar.min.css',
        'metronic/assets/global/plugins/jqvmap/jqvmap/jqvmap.css',
        'metronic/assets/global/css/components.min.css',
        'metronic/assets/layouts/layout3/css/layout.min.css',
        'metronic/assets/layouts/layout3/css/themes/red-intense.min.css',
        'metronic/assets/layouts/layout3/css/custom.min.css',
        'metronic/assets/pages/css/about.min.css',
        'css/shop.css',
        'metronic/assets/global/plugins/typeahead/typeahead.css',
        'metronic/assets/pages/css/blog.min.css',
    ];
    public $js = [
        'metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'metronic/assets/global/plugins/js.cookie.min.js',
        'metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'metronic/assets/global/plugins/jquery.blockui.min.js',
        'metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'metronic/assets/global/plugins/moment.min.js',
        'metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        'metronic/assets/global/plugins/morris/morris.min.js',
        'metronic/assets/global/plugins/morris/raphael-min.js',
        'metronic/assets/global/plugins/counterup/jquery.waypoints.min.js',
        'metronic/assets/global/plugins/counterup/jquery.counterup.min.js',
        'metronic/assets/global/plugins/fullcalendar/fullcalendar.min.js',
        'metronic/assets/global/plugins/flot/jquery.flot.min.js',
        'metronic/assets/global/plugins/flot/jquery.flot.resize.min.js',
        'metronic/assets/global/plugins/flot/jquery.flot.categories.min.js',
        'metronic/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
        'metronic/assets/global/plugins/jquery.sparkline.min.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js',
        'metronic/assets/global/scripts/app.min.js',
        'metronic/assets/pages/scripts/dashboard.min.js',
        'metronic/assets/layouts/layout3/scripts/layout.min.js',
        'metronic/assets/layouts/layout3/scripts/demo.min.js',
        'metronic/assets/layouts/global/scripts/quick-sidebar.min.js',
        'metronic/assets/layouts/global/scripts/quick-nav.min.js',
        'metronic/assets/global/plugins/typeahead/handlebars.min.js',
        'metronic/assets/global/plugins/typeahead/typeahead.bundle.min.js',



    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
