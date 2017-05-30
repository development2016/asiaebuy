<?php

namespace app\controllers;

use Yii;
use app\models\ItemElastic;
use app\models\Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\Query;
use yii\elasticsearch\QueryBuilder;
/**
 * ElasticController implements the CRUD actions for ItemElastic model.
 */
class ItemElasticController extends Controller
{

    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => ItemElastic::find(),
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionUpdate($id)
    {
    	 $model = $this->findModel($id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    protected function findModel($id)
    {
        if (($model = ItemElastic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionSearch()
    {
 
        $elastic = new Search();
        $result  = $elastic->Searches(Yii::$app->request->queryParams);
        $query = Yii::$app->request->queryParams;
        return $this->render('search', [
            'searchModel'  => $elastic,
            'dataProvider' => $result,
            'query'        => $query['search'],
        ]);
 
    }


    public function actionAjax()
    {


        if (!empty($_POST["value"])) {

            $searchs = $_POST["value"];

            $searchs      = $_POST["value"];
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
           print_r($re);
         /*  $dataProvider = new ActiveDataProvider([
               'query'      => $query,
     
           ]);
         
            $result = $dataProvider->getModels();

            foreach ($result as $key) {
                echo "<div class='portlet light '>";
                foreach ($key['_source'] as $key => $value) {
                    if ($key == "item_name") {

                        echo "<div class='portlet light'>
                            <span class='caption-subject  bold uppercase'>
                                <h3><b>$value</b></h3>
                            </span>
                        </div>";

                    }
                    if ($key == "specification") {
                    echo "<div class='portlet-body'>
                        <div class='well'>$value</div>
                        <div class='well well-large bg-red-sunglo'>$value</div>
                        </div>";
                    }
                }
                echo "</div>";
            } */


        } else {


            echo "No Item Found";


        }





    }


}