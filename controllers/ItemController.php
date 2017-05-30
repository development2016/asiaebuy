<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\elasticsearch\ActiveDataProvider;
use yii\elasticsearch\Query;
use yii\elasticsearch\QueryBuilder;
use app\models\ItemElastic;

use app\models\LookupGroup;
use app\models\LookupBrand;
use app\models\LookupCategory;
use app\models\LookupSubCategory;
use app\models\LookupModel;
use app\models\LookupState;
use app\models\LookupLeadTime;
use app\models\Company;
use app\models\UserCompany;
use app\models\User;
use app\models\UploadForm;

use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use yii\web\UploadedFile;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['owner_item'=>Yii::$app->user->identity->id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $_id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {


        $user_id = Yii::$app->user->identity->id;
        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$user_id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $checkState = LookupState::find()->where(['id'=>$companySeller->state])->one();


        $model = new Item();
        $model2 = new ItemElastic();

        if ($model->load(Yii::$app->request->post()) ) {


            $model->date_create = date('Y-m-d H:i:s');
            $model->enter_by = (int)Yii::$app->user->identity->id;
            $model->shippings = [[
                'aTa' => $_POST['Item']['shippings']['aTa'],
                'aTb' => $_POST['Item']['shippings']['aTb'],
            ]];

            $model->installations = [[
                'installation' => $_POST['Item']['installations']['installation'],
                'installation_price' => $_POST['Item']['installations']['installation_price'],
            ]];

            $model->owner_item = (int)Yii::$app->user->identity->id;
            $model->save();

            $group = LookupGroup::find()->where(['id'=>$_POST['Item']['group']])->one();
            $brand = LookupBrand::find()->where(['id'=>$_POST['Item']['brand']])->one();
            $category = LookupCategory::find()->where(['id'=>$_POST['Item']['category']])->one();
            $sub_category = LookupSubCategory::find()->where(['id'=>$_POST['Item']['sub_category']])->one();
            $models = LookupModel::find()->where(['id'=>$_POST['Item']['model']])->one();
            $lead_time = LookupLeadTime::find()->where(['id'=>$_POST['Item']['lead_time']])->one();

            foreach ($model->primaryKey as $key => $value) {
                $mongo_id = $value;
            }


            $model2->item_name = $_POST['Item']['item_name'];
            $model2->group = $group->group;
            $model2->brand = $brand->brand;
            $model2->category = $category->category;
            $model2->sub_category = $sub_category->sub_category;
            $model2->model = $models->model;
            $model2->description = $_POST['Item']['description'];
            $model2->specification = $_POST['Item']['specification'];
            $model2->lead_time = $lead_time->lead_time;
            $model2->cost = $_POST['Item']['cost'];
            $model2->stock = $_POST['Item']['stock'];
            $model2->quantity = $_POST['Item']['quantity'];
            $model2->publish = $_POST['Item']['publish'];
            $model2->enter_by = (int)Yii::$app->user->identity->id;
            $model2->date_create = date('Y-m-d H:i:s');
            $model2->owner_item = (int)Yii::$app->user->identity->id;
            $model2->mongo_id = $mongo_id;
            $model2->reviews = '';
            $model2->discount = $_POST['Item']['discount'];
            $model2->others = '';


            $model2->save(); 

        

            return $this->redirect(['image','_id'=>$mongo_id,'company'=>(string)$companySeller->_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'checkState' => $checkState
            ]);
        }
    }


    public function actionImage($_id,$company)
    {
        $model2 = $this->findModel((string)$_id);
        return $this->render('image',[
            '_id'=>$_id,
            'company' => $company,
            'model2' => $model2
        ]);
    }

    public function actionUpload($_id,$company)
    {
        $model2 = $this->findModel((string)$_id);
        $model = new UploadForm();

        $user_id = Yii::$app->user->identity->id;
        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$user_id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();


        if (Yii::$app->request->isPost) {

            if (!file_exists(Yii::getAlias('@webroot/offline/'.$companySeller->_id))) {
                mkdir(Yii::getAlias('@webroot/offline/'.$companySeller->_id), 0777, true);
            }

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {                

                if (!file_exists(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/original'))) {
                    mkdir(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/original'), 0777, true);
                }

                $model->file->saveAs(Yii::getAlias('@webroot/offline/'.$companySeller->_id).'/'.'original/'.$model->file->baseName . '.' . $model->file->extension);


                if (!file_exists(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/thumb'))) {
                    mkdir(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/thumb'), 0777, true);
                }


                Image::getImagine()->open(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/'.'original/'.$model->file->name))->thumbnail(new Box('223', '223'))->save(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/'.'thumb'.'/'.$model->file->baseName.'.'.$model->file->extension) , ['quality' => 90]);


                if (!file_exists(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/back'))) {
                    mkdir(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/back'), 0777, true);
                }


                Image::getImagine()->open(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/'.'original/'.$model->file->name))->thumbnail(new Box('600', '600'))->save(Yii::getAlias('@webroot/offline/'.$companySeller->_id.'/'.'back'.'/'.$model->file->baseName.'.'.$model->file->extension) , ['quality' => 90]);




                $model2->thumbs = '/thumb'.'/'.$model->file->baseName.'.'.$model->file->extension;
                $model2->back =  '/back'.'/'.$model->file->baseName.'.'.$model->file->extension;
                $model2->original = '/original'.'/'.$model->file->baseName.'.'.$model->file->extension;
                

                $model2->save();



            }

             return $this->redirect(['image','_id'=>$_id,'company'=>$company]);



           // Image::getImagine()->open(Yii::getAlias('@webroot/offline/'.$model->file->name))->thumbnail(new Box('300', '200'))->save(Yii::getAlias('@webroot/offline/new.'.$model->file->extension) , ['quality' => 90]);
            
        }

        return $this->renderAjax('upload', [
            'model' => $model,

        ]);
    }





    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $user_id = Yii::$app->user->identity->id;
        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$user_id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $checkState = LookupState::find()->where(['id'=>$companySeller->state])->one();

        $model2 = ItemElastic::find()->where(['mongo_id'=>(string)$model->_id])->one();

        if ($model->load(Yii::$app->request->post())) {




            $model->date_update = date('Y-m-d H:i:s');
            $model->update_by = (int)Yii::$app->user->identity->id;
            $model->shippings = [[
                'aTa' => $_POST['Item']['shippings']['aTa'],
                'aTb' => $_POST['Item']['shippings']['aTb'],
            ]];
            $model->installations = [[
                'installation' => $_POST['Item']['installations']['installation'],
                'installation_price' => $_POST['Item']['installations']['installation_price'],
            ]];



            $model->save();

            $group = LookupGroup::find()->where(['id'=>$_POST['Item']['group_id']])->one();
            $brand = LookupBrand::find()->where(['id'=>$_POST['Item']['brand_id']])->one();
            $category = LookupCategory::find()->where(['id'=>$_POST['Item']['category_id']])->one();
            $sub_category = LookupSubCategory::find()->where(['id'=>$_POST['Item']['sub_category_id']])->one();
            $models = LookupModel::find()->where(['id'=>$_POST['Item']['model_id']])->one();
            $lead_time = LookupLeadTime::find()->where(['id'=>$_POST['Item']['lead_time_id']])->one();

            $model2->item_name = $_POST['Item']['item_name'];
            $model2->group = $group->group;
            $model2->brand = $brand->brand;
            $model2->category = $category->category;
            $model2->sub_category = $sub_category->sub_category;
            $model2->model = $models->model;
            $model2->description = $_POST['Item']['description'];
            $model2->specification = $_POST['Item']['specification'];
            $model2->lead_time = $lead_time->lead_time;
            $model2->cost = $_POST['Item']['cost'];
            $model2->stock = $_POST['Item']['stock'];
            $model2->quantity = $_POST['Item']['quantity'];
            $model2->publish = $_POST['Item']['publish'];
            $model2->date_update = date('Y-m-d H:i:s');
            $model2->update_by = (int)Yii::$app->user->identity->id;
            $model2->save();


            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'checkState' => $checkState
            ]);
        }
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBrand($id)
    {
        $countPosts = LookupModel::find()
        ->where(['brand_id' => $id])
        ->count();

        $posts = LookupModel::find()
        ->where(['brand_id' => $id])
        ->all();

        if($countPosts>0){
            echo "<option value=''>-Please Choose-</option>";
            foreach($posts as $post){
                echo "<option value='".$post->id."'>".$post->model."</option>";
            }
        } else {
                echo "<option></option>";
        }

    }


    public function actionCategory($id)
    {

        //$id = $_POST["id"];

        $countPosts = LookupCategory::find()
        ->where(['group_id' => $id])
        ->count();

        $posts = LookupCategory::find()
        ->where(['group_id' => $id])
        ->all();

        if($countPosts>0){

            foreach($posts as $post){
                echo "<li class='li-category' value='".$post->id."' label='".$post->category."'>".$post->category."</li>";
            }

        } else {
               
        }



        echo "<script type='text/javascript'>";
        echo "$('.li-category').on('click', function(){";
        echo "var category = $(this).val();";
        echo "var label = $(this).attr('label');";
        echo "$.ajax({";
        echo "url: 'sub-category',";
        echo "data: {id: category},";
        echo "success: function(data) {";
        echo "$('ul.ul-sub-category').html(data);";
        echo "$('.info-category').show();";
        echo "$('span.category').html(label);";
        echo "$('#category').val(category);";
        echo "}";
        echo "});";
        echo "});";
        echo "</script>";




    }

    public function actionSubCategory($id)
    {


        //$id = $_POST["id"];


        $countPosts = LookupSubCategory::find()
        ->where(['category_id' => $id])
        ->count();

        $posts = LookupSubCategory::find()
        ->where(['category_id' => $id])
        ->all();

        if($countPosts>0){

            foreach($posts as $post){
                echo "<li class='li-sub-category' value='".$post->id."' label='".$post->sub_category."'>".$post->sub_category."</li>";
            }

        } else {
               
        }


        echo "<script type='text/javascript'>";
        echo "$('.li-sub-category').on('click', function(){";
        echo "var sub_category = $(this).val();";
        echo "var label = $(this).attr('label');";
        echo "$('.info-sub-category').show();";
        echo "$('span.sub-category').html(label);";
        echo "$('#sub_category').val(sub_category);";
        echo "});";
        echo "</script>";



    }



    public function actionSuggestion()
    {
        $word = $_POST['word'];

        $connection = \Yii::$app->db;

        $sql = $connection->createCommand('SELECT 
            lookup_group.id AS group_id,
            lookup_group.group,
            lookup_category.id AS category_id,
            lookup_category.category,
            lookup_sub_category.id AS sub_category_id,
            lookup_sub_category.sub_category 
            FROM lookup_group
            RIGHT JOIN lookup_category ON lookup_group.id = lookup_category.group_id
            RIGHT JOIN lookup_sub_category ON (lookup_group.id = lookup_sub_category.group_id AND lookup_category.id = lookup_sub_category.category_id)
        WHERE lookup_group.group LIKE "%'.$word.'%" OR lookup_category.category LIKE "%'.$word.'%" OR lookup_sub_category.sub_category LIKE "%'.$word.'%"');
        $model = $sql->queryAll();

        $total = 0;
        foreach ($model as $key => $value) { 
            $total++;
        }

        echo '<div class="portlet-title">';
        echo '       <div class="caption">';
        echo '           <i class="icon-share font-dark hide"></i>';
        echo '          <span class="caption-subject ">Search Suggestion : <b>'.$total.'</b> </span>';
        echo '        </div>';
        echo '        <div class="actions">';
        echo '           <a href="#" class="back-normal">Back To Normal</a>';
        echo '        </div>';
        echo '</div>';
        echo '<div class="portlet-body " style="max-height: 300px;height: 300px;overflow-y: auto">';
            echo '<ul class="ul-search" id="ul-search">';
            foreach ($model as $key => $value) {
                echo '<li class="c" data-group="'.$value['group_id'].'" data-ingroup="'.$value['group'].'" data-category="'.$value['category_id'].'" data-incategory="'.$value['category'].'" data-sub="'.$value['sub_category_id'].'" data-insub="'.$value['sub_category'].'">'.$value['group'].' > '.$value['category'].' > '.$value['sub_category'].'</li>';
                echo '<br>';
            }
            echo '</ul>';
        echo '</div>';



        echo "<script type='text/javascript'>";
        echo "$('.c').on('click', function(e){";
            echo "var group = $(this).data('group');";
            echo "var ingroup = $(this).data('ingroup');";
            echo "var category = $(this).data('category');";
            echo "var incategory = $(this).data('incategory');";
            echo "var sub = $(this).data('sub');";
            echo "var insub = $(this).data('insub');";
        echo "$('#group').val(group);";
        echo "$('#category').val(category);";
        echo "$('#sub_category').val(sub);";
        echo "$('span.group').html(ingroup);";
        echo "$('span.category').html(incategory);";
        echo "$('span.sub-category').html(insub);";
        echo "$('.info-group').show();";
        echo "$('.info-category').show();";
        echo "$('.info-sub-category').show();";
        echo "});";


        echo "$('.back-normal').on('click', function () {";
        echo "   $('.show-auto-complete-drill').hide();";
        echo "  $('.show-drill').show();";
        echo "});";




        echo "</script>";



    }





}
