<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\ItemElastic;
use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\Query;
use yii\elasticsearch\QueryBuilder;
/**
 * ElasticSearch represents the model behind the search form about `app\models\Elastic`.
 */
class Search extends ItemElastic
{
 
   public function Searches($value)
   {

      echo "<h3>This Will Return Info About Item In Details</h3>";
      echo "<hr>";
 /*
          $searchs      = $value['search'];
          $query        = new Query();
          $db           = ItemElastic::getDb();
   
          $queryBuilder = new QueryBuilder($db);


         $match   = [
            'multi_match' => [
                'query' => $searchs,
                'fields' => ['specification','item_name'],
                ]
            ]; 

       $query->query = $match;
       $build        = $queryBuilder->build($query);
       $re           = $query->search($db, $build);
      $dataProvider = new ActiveDataProvider([
          'query'      => $query,
 
      ]);
      $result = $dataProvider->getModels();

      echo count($result);
      print_r($result);
      exit(); */
 
       //return $dataProvider;
      $searchs      = $value['search'];
          $results = ItemElastic::find()->query([
          'multi_match' => [
                'query' => $searchs,
                'fields' => ['specification','item_name','brand'],
                ]
          ])->asArray()->all();


          $returns = array();

          foreach ($results as $result) {

                $returns[] = $result['_source'];

          }

          $brands = array();

          foreach($returns as $return)
          {
              $brands[$return['brand']][] = $return['item_name'];
          }


          $getBrand = array();

          foreach($brands as $brand => $label)
          {

              $getBrand[] = 
              [
                'brand' => $brand,

              ];

          }
          echo "BRAND";
          echo "<br>";
          print "<pre>";
          print_r($getBrand);
          print "</pre>";
          echo "<br>";
          echo "<hr>";

          $groups = array();

          foreach($returns as $return)
          {
              $groups[$return['group']][] = $return['item_name'];
          }

          $getGroup = array();

          foreach($groups as $group => $label)
          {

              $getGroup[] = 
              [
                'group' => $group,

              ];

          }
          echo "GROUP";
          echo "<br>";
          print "<pre>";
          print_r($getGroup);
          print "</pre>";
          echo "<br>";
          echo "<hr>";

          $categorys = array();

          foreach($returns as $return)
          {
              $categorys[$return['category']][] = $return['item_name'];
          }

          $getCategory = array();

          foreach($categorys as $category => $label)
          {

              $getCategory[] = 
              [
                'category' => $category,

              ];

          }

          echo "CATEGORY";
          echo "<br>";
          print "<pre>";
          print_r($getCategory);
          print "</pre>";
          echo "<br>";
          echo "<hr>";


          $sub_categorys = array();

          foreach($returns as $return)
          {
              $sub_categorys[$return['sub_category']][] = $return['item_name'];
          }

          $getSubCategory = array();

          foreach($sub_categorys as $sub_category => $label)
          {

              $getSubCategory[] = 
              [
                'sub_category' => $sub_category,

              ];

          }

          echo "SUB-CATEGORY";
          echo "<br>";
          print "<pre>";
          print_r($getSubCategory);
          print "</pre>";
          echo "<br>";
          echo "<hr>";


          $models = array();

          foreach($returns as $return)
          {
              $models[$return['model']][] = $return['item_name'];
          }

          $getModel = array();

          foreach($models as $model => $label)
          {

              $getModel[] = 
              [
                'model' => $model,

              ];

          }


          echo "MODEL";
          echo "<br>";
          print "<pre>";
          print_r($getModel);
          print "</pre>";
          echo "<br>";
          echo "<hr>";

          exit();

      }
 
}
