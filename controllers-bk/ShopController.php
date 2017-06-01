<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use app\models\ItemElastic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LookupGroup;
use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\Query;
use yii\elasticsearch\QueryBuilder;
use app\models\UserCompany;
use app\models\User;
use app\models\Company;
use app\models\LookupState;

class ShopController extends Controller
{

    public function actionIndex()
    {
    	$this->layout = 'shop';

        $model = Item::find()->where(['publish'=>'Publish'])->all();

        return $this->render('index',[
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'shop';
        $model2 = $this->findModel($id);
        $model = $this->findModel($id);


        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$model->owner_item])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $checkState = LookupState::find()->where(['id'=>$companySeller->state])->one();



        return $this->render('view', [
            'model' => $model,
            'model2' => $model2,
            'checkState' => $checkState
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    public function actionSearch()
    {
        if (!empty($_POST["value"])) {

            $searchs = $_POST["value"];

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

echo '<div class="blog-page blog-content-2">';
echo '     <div class="blog-single-sidebar bordered blog-container">';
echo '     <div class="blog-single-sidebar-recent">';
echo ' <h4>BRAND</h4>';
echo '      <ul>';
      foreach ($getBrand as $key => $value) {
      echo '           <li>';
      echo '             <a href="javascript:;">'.$value['brand'].'</a>';
      echo '           </li>';
      }
echo '      </ul>';
echo '      </div>';
echo '<div class="more">View More ..</div>';
echo '     <div class="blog-single-sidebar-recent">';
echo ' <h4>GROUP</h4>';
echo '      <ul>';
      foreach ($getGroup as $key => $value) {
      echo '           <li>';
      echo '             <a href="javascript:;">'.$value['group'].'</a>';
      echo '           </li>';
      }
echo '      </ul>';
echo '      </div>';
echo '<div class="more">View More ..</div>';
echo '     <div class="blog-single-sidebar-recent">';
echo ' <h4>CATEGORY</h4>';
echo '      <ul>';
      foreach ($getCategory as $key => $value) {
      echo '           <li>';
      echo '             <a href="javascript:;">'.$value['category'].'</a>';
      echo '           </li>';
      }
echo '      </ul>';
echo '      </div>';
echo '<div class="more">View More ..</div>';
echo '     <div class="blog-single-sidebar-recent">';
echo ' <h4>SUB CATEGORY</h4>';
echo '      <ul>';
      foreach ($getSubCategory as $key => $value) {
      echo '           <li>';
      echo '             <a href="javascript:;">'.$value['sub_category'].'</a>';
      echo '           </li>';
      }
echo '      </ul>';
echo '      </div>';
echo '<div class="more">View More ..</div>';
echo '     <div class="blog-single-sidebar-recent">';
echo ' <h4>MODEL</h4>';
echo '      <ul>';
      foreach ($getModel as $key => $value) {
      echo '           <li>';
      echo '             <a href="javascript:;">'.$value['model'].'</a>';
      echo '           </li>';
      }
echo '      </ul>';
echo '      </div>';
echo '<div class="more">View More ..</div>';
echo ' </div>';
echo ' </div>';
                                 




       



        } else {  

echo '<div class="blog-page blog-content-2">';
echo '     <div class="blog-single-sidebar bordered blog-container">';
echo '     <div class="blog-single-sidebar-recent">';
echo "No Item Found";
echo '  </div>';
echo ' </div>';
echo ' </div>';
                   


            
        }
    }

}
