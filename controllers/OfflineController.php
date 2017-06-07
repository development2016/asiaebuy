<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CompanyOffline;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\Company;
use app\models\AsiaebuyCompany;
use app\models\UserCompany;
use app\models\User;
use app\models\LookupState;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Uploads;

class OfflineController extends Controller
{

	public function actionIndex()
	{	
        $today = date('Y-m-d');

		$model = new CompanyOffline();

        $model2 = Uploads::find()->where(
            [
                'enter_by'=>Yii::$app->user->identity->id,

            ]
        )
        ->orderBy(['id' => SORT_DESC])->limit(1)->one();


        $model3 = new Project();

        $requester_id = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();

        if ($model3->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) ) {

            $getP = Project::find()->orderBy(['_id' => SORT_DESC])->limit(1)->one();


                if (empty($getP['project_no'])) {

                    $runninNo = 1000;
                    $day = date('d');
                    $month = date('m');
                    $year = date('y');
                    $project_no  = 'MY'.$day.$month.$year.$runninNo;


                } else {

                    $qt = substr($getP['project_no'], 8);
                    $new = $qt + 1;
                    $runninNo = $new;

                    $day = date('d');
                    $month = date('m');
                    $year = date('y');
                    $project_no  = 'MY'.$day.$month.$year.$runninNo;

                }

                $model3->project_no = $project_no;
                $model3->type_of_project = 'Direct Purchase';
                $model3->date_create = date('Y-m-d h:i:s');
                $model3->enter_by = Yii::$app->user->identity->id;
                $model3->requester = $requester_id->account_name;
                $model3->buyers = [
                
                    ['buyer' => $requester_id->account_name]
                ];
                $model3->sellers = [[
                    'seller' => $_POST['CompanyOffline']['company_name'],
                    'company_registeration_no' => $_POST['CompanyOffline']['company_registeration_no'],
                    'address' => $_POST['CompanyOffline']['address'],
                    'zip_code' => $_POST['CompanyOffline']['zip_code'],
                    'country' => $_POST['CompanyOffline']['country'],
                    'state' => $_POST['CompanyOffline']['state'],
                    'city' => $_POST['CompanyOffline']['city'],
                    'telephone_no' => $_POST['CompanyOffline']['telephone_no'],
                    'fax_no' => $_POST['CompanyOffline']['fax_no'],
                    'email' => $_POST['CompanyOffline']['email'],
                    'website' => $_POST['CompanyOffline']['website'],
                    'tax' => $_POST['CompanyOffline']['gst'],
                    'term' => $_POST['CompanyOffline']['term'],
                    'status' => 'Quotation Uploaded',
                    'quotation' => $_POST['Project']['sellers']['quotation'],
                    'direct_purchase' => [
                        'company_id' => $_POST['Project']['direct_purchase']['company_id'],
                        'filename' => $_POST['Project']['direct_purchase']['filename'],
                        'path' => $_POST['Project']['direct_purchase']['path'],
                        'enter_by' => $_POST['Project']['direct_purchase']['enter_by'],

                    ],
                    'purchase_requisition_no' => '',
                    'purchase_order_no' => '',
                    'items'=> [],


                ]];

                $model->date_create = date('Y-m-d H:i:s');
                $model->enter_by = Yii::$app->user->identity->id;

                $model3->save() && $model->save();
                Uploads::deleteAll(['id'=>$_POST['Project']['direct_purchase']['id']]);

            Yii::$app->getSession()->setFlash('direct', 'Your Direct Purchase Has Been Submit');
            return $this->redirect(['source/index']);

        } else {

            return $this->render('index',[
                'model' => $model,
                'model2' => $model2,
                'model3' => $model3,
            ]);

            
        }


	}


    public function actionUpload()
    {
        $model = new UploadForm();
        $model2 = new Uploads();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();

        $company = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        if (Yii::$app->request->isPost) {

            if (!file_exists(Yii::getAlias('@webroot/offline/'.$company->_id))) {
                mkdir(Yii::getAlias('@webroot/offline/'.$company->_id), 0777, true);
            }

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate()) {   

                if (!file_exists(Yii::getAlias('@webroot/offline/'.$company->_id.'/direct_purchase'))) {
                    mkdir(Yii::getAlias('@webroot/offline/'.$company->_id.'/direct_purchase'), 0777, true);
                }

                $model->file->saveAs(Yii::getAlias('@webroot/offline/'.$company->_id).'/'.'direct_purchase/'.$model->file->baseName . '.' . $model->file->extension);


                $model2->filename = $model->file->name;
                $model2->path = '/direct_purchase'.'/'.$model->file->baseName.'.'.$model->file->extension;
                $model2->company_id = (string)$company->_id;
                $model2->enter_by = Yii::$app->user->identity->id;
                $model2->date_create = date('Y-m-d H:i:s');
                
                
                $model2->save();

                return $this->redirect(['offline/index']);


            }
            

        }

        return $this->renderAjax('upload', [
            'model' => $model,
        ]);
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



	
}