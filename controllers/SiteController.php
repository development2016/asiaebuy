<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\User;
use app\models\Password;
use app\models\Acl;
use app\models\UserCompany;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
 /*   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
 */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['error','signup','login','request-password-reset','reset-password','state','register','seller','buyer','comming','company-name','registeration-no','username'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','index','contact','history'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }



    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'login';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionComming()
    {
        $this->layout = 'login';
        return $this->render('comming');
    }



    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {

        $this->layout = 'login';

        return $this->render('register');
    }

    public function actionBuyer()
    {

        $model = new User();
        $model2 = new Company();
        $model3 = new Password();
        $model6 = new Acl();
        $model7 = new UserCompany();

        $this->layout = 'buyer';

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) ) {

            $state = LookupState::find()->where(['id'=>$_POST['Company']['state']])->one();
            $country = LookupCountry::find()->where(['id'=>$_POST['Company']['country']])->one();

            $getR = Company::find()->where(['type'=>'Buyer'])->orderBy(['_id' => SORT_DESC])->limit(1)->one();
            //check wheter ASIAEBUY id have it or not , if not start with 1000 after that +1

            if (empty($getR['asia_ebuy_no'])) {

                $runninNo = 1000;
                $asiano = 'Buyer'.$runninNo;
                
            } else {

                $qt = substr($getR['asia_ebuy_no'], 5);
                $new = $qt + 1;
                $runninNo = $new;

                $asiano = 'Buyer'.$runninNo;
            }

            $model->as_a = 300; // as buyer
            $model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);
            $model->account_name = $_POST['User']['username'].'@'.$asiano;
            
            if ($model->save()) {

                $last_id = Yii::$app->db->getLastInsertID();

                $model2->type = "Buyer";
                $model2->date_create = date('Y-m-d h:i:s');
                $model2->email = $_POST['User']['email'];
                $model2->admin = (int)$last_id;
                $model2->asia_ebuy_no = $asiano.'@'.$_POST['Company']['city'].','.$state->state.'.'.$country->code;
                $model2->warehouses = [];
                $model2->status = "In Progress";

                $model2->save();

                foreach ($model2->primaryKey as $key => $value) {
                    $mongo_id = $value;
                }


                $model7->user_id = (int)$last_id;
                $model7->company = $mongo_id;
                $model7->save();

                $model3->password = $_POST['User']['password_hash'];
                $model3->id_user = (int)$last_id;


                $connection = \Yii::$app->db;
                $sql = $connection->createCommand('SELECT * FROM acl_menu WHERE role_id = 3300');
                $sql_model = $sql->queryAll();


                foreach ($sql_model as $key_acl => $value_acl) { 

                    $model6 =new Acl();
                    $model6->acl_menu_id = $value_acl['id'];
                    $model6->user_id = (int)$last_id;
                    $model6->company_id = $mongo_id;
                    $model6->save();
                   
                }

                $model3->save();

            }
            
            return $this->redirect('login');


        } else {

            return $this->render('buyer',[
                'model' => $model,
                'model2' => $model2,
            ]);

        }



    }


    public function actionSeller()
    {
        $model = new User();
        $model2 = new Company();
        $model3 = new Password();
        $model6 = new Acl();
        $model7 = new UserCompany();

        $this->layout = 'seller';

        if ($model->load(Yii::$app->request->post()) && $model2->load(Yii::$app->request->post()) ) {

            $state = LookupState::find()->where(['id'=>$_POST['Company']['state']])->one();
            $country = LookupCountry::find()->where(['id'=>$_POST['Company']['country']])->one();

            $getR = Company::find()->where(['type'=>'Seller'])->orderBy(['_id' => SORT_DESC])->limit(1)->one();
            //check wheter ASIAEBUY id have it or not , if not start with 1000 after that +1

            if (empty($getR['asia_ebuy_no'])) {

                $runninNo = 1000;
                $asiano = 'Seller'.$runninNo;
                
            } else {

                $qt = substr($getR['asia_ebuy_no'], 6);
                $new = $qt + 1;
                $runninNo = $new;

                $asiano = 'Seller'.$runninNo;
            }

            $model->as_a = 200; // as seller
            $model->password_hash = Yii::$app->security->generatePasswordHash($_POST['User']['password_hash']);
            $model->account_name = $_POST['User']['username'].'@'.$asiano;

            if ($model->save()) {


                $last_id = Yii::$app->db->getLastInsertID();

                $model2->type = "Seller";
                $model2->date_create = date('Y-m-d h:i:s');
                $model2->email = $_POST['User']['email'];
                $model2->admin = (int)$last_id;
                $model2->asia_ebuy_no = $asiano.'@'.$_POST['Company']['city'].','.$state->state.'.'.$country->code;
                $model2->warehouses = [];
                $model2->status = "In Progress";
                $model2->term = (int)$_POST['Company']['term'];

                $model2->save();

                foreach ($model2->primaryKey as $key => $value) {
                    $mongo_id = $value;
                }

                $model7->user_id = (int)$last_id;
                $model7->company = $mongo_id;
                $model7->save();

                $model3->password = $_POST['User']['password_hash'];
                $model3->id_user = (int)$last_id;

                $connection = \Yii::$app->db;
                $sql = $connection->createCommand('SELECT * FROM acl_menu WHERE role_id = 2200');
                $sql_model = $sql->queryAll();


                foreach ($sql_model as $key_acl => $value_acl) { 

                    $model6 =new Acl();
                    $model6->acl_menu_id = $value_acl['id'];
                    $model6->user_id = (int)$last_id;
                    $model6->company_id = $mongo_id;
                    $model6->save();
                   
                }

                $model3->save();


            }

            return $this->redirect('login');



        } else {

            return $this->render('seller',[
                'model' => $model,
                'model2' => $model2,
            ]);

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

    public function actionCompanyName()
    {


        if (!empty($_POST["value"])) {


            $company = Company::find()->where(['company_name'=>strtoupper($_POST['value'])])->one();

            if (empty($company)) {

              
            } else {

    
                echo '<span style="color:red;font-size:10px;">This Company Already Exist</span>';

            }

            

        } else {


        }
    }

    public function actionRegisterationNo()
    {


        if (!empty($_POST["value"])) {


            $company = Company::find()->where(['company_registeration_no'=>$_POST['value']])->one();

            if (empty($company)) {

              
            } else {

    
                echo '<span style="color:red;font-size:10px;">This Company Registeration No Already Exist</span>';

            }

            

        } else {


        }
    }

    public function actionUsername()
    {


        if (!empty($_POST["value"])) {


            $company = User::find()->where(['username'=>$_POST['value']])->one();

            if (empty($company)) {

              
            } else {

    
                echo '<span style="color:red;font-size:10px;">This Username Already Exist</span>';

            }

            

        } else {


        }
    }



}
