<?php

namespace app\controllers;

use Yii;
use app\models\Company;
use app\models\UserCompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\UploadForm;
use app\models\LogoUpload;
use yii\web\UploadedFile;
use app\models\UserCompany;
/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Deletes an existing Company model.
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
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionState($id)
    {
        $countPosts = LookupState::find()
        ->where(['country_id' => $id])
        ->count();

        $posts = LookupState::find()
        ->where(['country_id' => $id])
        ->all();

        if($countPosts>0){
            echo "<option value=''>-Please Choose-</option>";
            foreach($posts as $post){
                echo "<option value='".$post->id."'>".$post->state."</option>";
            }
        } else {
                echo "<option></option>";
        }

    }


    public function actionManageCompany($company_id)
    {   


        $model = $this->findModel($company_id);
        $model->scenario = 'manage-company';

        if ($model->load(Yii::$app->request->post()) ) {

            $model->update_by = (int)Yii::$app->user->identity->id;
            $model->date_update = date('Y-m-d H:i:s');
            $model->status = 'Complete';
            $model->save();

            return $this->redirect(['/site/index']);
        } else {
            return $this->render('manage-company',[
                'model' => $model,
                'company_id'=> $company_id
            ]);
   
        }

    }

    public function actionManageWarehouse($company_id)
    {

        $newCompanyid = new \MongoDB\BSON\ObjectID($company_id);

        $collection = Yii::$app->mongo->getCollection('company');
        $process = $collection->aggregate([
            [
                '$unwind' => '$warehouses'
            ],

            [
                '$match' => [
                    '_id' => $newCompanyid,
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'warehouses' => [
                        '$push' => '$warehouses'
                    ],
                ]
            ]



        ]); 


        return $this->render('manage-warehouse',[
            'process' => $process,
            'newCompanyid' => $newCompanyid,
        ]);

    }

    public function actionWarehouse($company_id)
    {
        $newCompanyid = new \MongoDB\BSON\ObjectID($company_id);

        $model = Company::find()->where(['_id'=>$newCompanyid])->one();


        if ($model->load(Yii::$app->request->post())) {

             $collection = Yii::$app->mongo->getCollection('company');


            if (empty($_POST['Company']['warehouses']['latitude']) || empty($_POST['Company']['warehouses']['longitude'])) {


                $collection->update(
                    ['_id' => $newCompanyid],
                    [
                        '$push' => [ // $push to add items in array 
                            'warehouses' => [
                             
                                'person_in_charge' => $_POST['Company']['warehouses']['person_in_charge'],
                                'contact' => $_POST['Company']['warehouses']['contact'],
                                'country' =>$_POST['Company']['warehouses']['country'],
                                'state' => $_POST['Company']['warehouses']['state'],
                                'location' => $_POST['Company']['warehouses']['location'],
                                'warehouse_name' => $_POST['Company']['warehouses']['warehouse_name'],
                                'address' => $_POST['Company']['warehouses']['address'],
                                'latitude' => 0,
                                'longitude' => 0,
                            
                            ]
                        ]
                    ]

                );


              
            } else {


                $collection->update(
                    ['_id' => $newCompanyid],
                    [
                        '$push' => [ // $push to add items in array 
                            'warehouses' => [
                             
                                'person_in_charge' => $_POST['Company']['warehouses']['person_in_charge'],
                                'contact' => $_POST['Company']['warehouses']['contact'],
                                'country' =>$_POST['Company']['warehouses']['country'],
                                'state' => $_POST['Company']['warehouses']['state'],
                                'location' => $_POST['Company']['warehouses']['location'],
                                'warehouse_name' => $_POST['Company']['warehouses']['warehouse_name'],
                                'address' => $_POST['Company']['warehouses']['address'],
                                'latitude' => $_POST['Company']['warehouses']['latitude'],
                                'longitude' => $_POST['Company']['warehouses']['longitude'],
                            
                            ]
                        ]
                    ]

                );


            }


            return $this->redirect(['company/manage-warehouse', 'company_id' => (string)$newCompanyid]);


        } else {

            return $this->renderAjax('warehouse',[
                'model' => $model,

            ]);


        }

    }


    public function actionUpload($company_id)
    {
        $model = new UploadForm();
        $model2 = new LogoUpload();
        $model3 = $this->findModel((string)$company_id);

        $returnCompany = UserCompany::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();

        $company = Company::find()->where(['_id'=>$returnCompany->company])->one();

        if (Yii::$app->request->isPost) {

            if (!file_exists(Yii::getAlias('@webroot/offline/'.$company->_id))) {
                mkdir(Yii::getAlias('@webroot/offline/'.$company->_id), 0777, true);
            }

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {   

                if (!file_exists(Yii::getAlias('@webroot/company-logo/'.$company->_id.'/logo'))) {
                    mkdir(Yii::getAlias('@webroot/company-logo/'.$company->_id.'/logo'), 0777, true);
                }

                $model->file->saveAs(Yii::getAlias('@webroot/company-logo/'.$company->_id).'/'.'logo/'.$model->file->baseName . '.' . $model->file->extension);


                $model2->filename = $model->file->name;
                $model2->path = '/company-logo'.'/'.$model->file->baseName.'.'.$model->file->extension;
                $model2->company_id = (string)$company->_id;
                $model2->enter_by = Yii::$app->user->identity->id;
                $model2->date_create = date('Y-m-d H:i:s');

                $model3->logo = '/company-logo'.'/'.$company->_id.'/logo/'.$model->file->baseName.'.'.$model->file->extension;

     
                $model2->save() && $model3->save();

                return $this->redirect(['company/manage-company','company_id'=>(string)$company_id]);


            }
            

        }

        return $this->renderAjax('upload', [
            'model' => $model,
        ]);
    }



}
