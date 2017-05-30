<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Company;
use app\models\LookupRole;
use app\models\Password;
use app\models\Acl;
use app\models\UserCompany;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionListUser($company_id)
    {
        $connection = \Yii::$app->db;
        $user_id = Yii::$app->user->identity->id;

        $company = Company::find()->where(['_id'=>$company_id])->one();

        $sql = $connection->createCommand('SELECT *,user.id AS id_user FROM user_company 
            RIGHT JOIN user ON user_company.user_id = user.id
            WHERE user_company.company = "'.$company_id.'"');
        $userList = $sql->queryAll();



        return $this->render('list-user',[
            'company' => $company,
            'userList' => $userList,
        ]);
        
    }

    public function actionCreate($company_id,$type)
    {

        $model = new User();
        $model2 = new LookupRole();
        $model3 = new Password();
        $model4 = new UserCompany();
        $model5 = new Acl();
        
        $user_id = Yii::$app->user->identity->id;
        $company = Company::find()->where(['admin'=>$user_id])->one();


        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) ) {


            if ($type == 'Buyer') {

                $model->as_a = 300; // as buyer
                $model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);
                $model->account_name = $_POST['User']['account_name'].'@'.substr($company->asia_ebuy_no,0, strrpos($company->asia_ebuy_no, '@'));


                if ($model->save()) {

                    $last_id = Yii::$app->db->getLastInsertID();


                    $model3->password = $_POST['User']['password_hash'];
                    $model3->id_user = (int)$last_id;

                    $model4->company = $company_id;
                    $model4->user_id = (int)$last_id;

                    $model4->save() && $model3->save();


                    $valueArray = implode(',', array_map('intval', $_POST['LookupRole']['role_id']));


                    $connection = \Yii::$app->db;
                    $sql = $connection->createCommand('SELECT * FROM acl_menu WHERE  role_id IN  ('.$valueArray.')');
                    $sql_model = $sql->queryAll();


                    foreach ($sql_model as $key => $value) { 

                        $model5 =new Acl();
                        $model5->acl_menu_id = $value['id'];
                        $model5->user_id = (int)$last_id;
                        $model5->company_id = $company_id;
                        $model5->save();
                       
                    }


                }


            } elseif ($type == 'Seller') {

                $model->as_a = 200; // as buyer
                $model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);
                $model->account_name = $_POST['User']['account_name'].'@'.substr($company->asia_ebuy_no,0, strrpos($company->asia_ebuy_no, '@'));
                if ($model->save()) {

                    $last_id = Yii::$app->db->getLastInsertID();

                    $model3->password = $_POST['User']['password_hash'];
                    $model3->id_user = (int)$last_id;

                    $model4->company = $company_id;
                    $model4->user_id = (int)$last_id;

                    $model4->save() && $model3->save();


                    $valueArray = implode(',', array_map('intval', $_POST['LookupRole']['role_id']));


                    $connection = \Yii::$app->db;
                    $sql = $connection->createCommand('SELECT * FROM acl_menu WHERE  role_id IN  ('.$valueArray.')');
                    $sql_model = $sql->queryAll();


                    foreach ($sql_model as $key => $value) { 

                        $model5 =new Acl();
                        $model5->acl_menu_id = $value['id'];
                        $model5->user_id = (int)$last_id;
                        $model5->company_id = $company_id;
                        $model5->save();
                       
                    }


                }


            } else {

            }


            return $this->redirect(['user/list-user','company_id'=>$company_id]);

        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'model2' => $model2,
                'type' => $type,
                'company' => $company,
        
            ]);
        }
    }






}
