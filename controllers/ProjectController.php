<?php

namespace app\controllers;

use Yii;
use app\models\Project;
use app\models\ProjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LookupTitle;
use app\models\User;
use app\models\Item;
use app\models\UserCompany;
use app\models\Company;
use app\models\GenerateQuotationNo;
use app\models\AsiaebuyCompany;
use app\models\LookupState;
use app\models\LookupRole;
/**
 * ProjectController implements the CRUD actions for Project model.
 */
class ProjectController extends Controller
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
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->query->andWhere(['sellers.seller'=>$id]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
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
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Project();

        $buyer_id = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();

        if ($model->load(Yii::$app->request->post()) ) {

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

                $model->project_no = $project_no;
                $model->type_of_project = 'Sale Lead';
                $model->date_create = date('Y-m-d h:i:s');
                $model->enter_by = Yii::$app->user->identity->id;
                $model->buyer = $buyer_id->account_name;
                $model->sellers = [];
                $model->save();



        
            Yii::$app->getSession()->setFlash('request', 'Your Request Has Been Submit');
            return $this->redirect(['source/index']);

        } else {

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
            
        }




    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $_id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => (string)$model->_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
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
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $_id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCart($item_id,$seller,$path,$item_name)
    {

        $model = new Project();

        $seller_id = User::find()->where(['id'=>(int)$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>(int)$seller])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $buyer_id = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>(int)$buyer_id->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $checkLocSeller = LookupState::find()->where(['id' => $companySeller->state])->one();

        $checkLocBuyer = LookupState::find()->where(['id' => $companyBuyer->state])->one();

        if ($model->load(Yii::$app->request->post()) ) {

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

                $item_id = new \MongoDB\BSON\ObjectID($item_id);
                $collection = Yii::$app->mongo->getCollection('item');
                $items = $collection->aggregate([
                    [
                        '$match' => [

                            '_id' => $item_id
                        ]
                    ],

                ]);

 


                if ($checkLocBuyer == $checkLocSeller) {

                    $newShipping = 'Yes';
                    $newShippingPrice = $items[0]['shippings'][0]['aTa'];

                    $newInstall = $items[0]['installations'][0]['installation'];
                    $newInstallPrice = $items[0]['installations'][0]['installation_price'];

                } else {

                    $newShipping = 'Yes';
                    $newShippingPrice = $items[0]['shippings'][0]['aTb'];

                    $newInstall = $items[0]['installations'][0]['installation'];
                    $newInstallPrice = $items[0]['installations'][0]['installation_price'];

                }



                $itemSerial = serialize($items);



                    $model->project_no = $project_no;
                    $model->type_of_project = 'Guide Buying';
                    $model->date_create = date('Y-m-d h:i:s');
                    $model->enter_by = Yii::$app->user->identity->id;
                    $model->buyer = $buyer_id->account_name;
                    $model->tax_value = $return_asiaebuy->gst_cost;
                    $model->sellers =  [[
                        'type_of_buying' => 'Cart',
                        'seller'=> $seller_id->account_name,
                        'company' => $companySeller->asia_ebuy_no,
                        'quotation_no' => '',
                        'purchase_requisition_no' => '',
                        'purchase_order_no' => '',
                        'delivery_order_no' => '',
                        'invoice_buyer_no' => '',
                        'invoice_seller_no' => '',
                        'status'=>'Quoted',
                        'quantity' => $_POST['Project']['sellers'][0]['quantity'],
                        'items' =>  unserialize($itemSerial),
                        'shipping' => $newShipping,
                        'shipping_price' => $newShippingPrice,
                        'install' => $newInstall,
                        'installation_price' => $newInstallPrice,
                        'date_quotation' => ''


                    ]];
                

       



                if ($model->save()) {


                    foreach ($model->primaryKey as $key => $value) {
                        $mongo_id = $value;
                    }
                     $newProject_id = new \MongoDB\BSON\ObjectID($mongo_id);



                        $collection = Yii::$app->mongo->getCollection('project');
                        $list = $collection->aggregate([
                            [
                                '$unwind' => '$sellers'
                            ],
                            [
                                '$match' => [
                                    '$and' => [
                                        [
                                            '_id' => $newProject_id
                                        ],
                                        [
                                            'sellers.seller' => $seller_id->account_name,
                                        ],
                                    ],

                                    
                                ]
                            ],
                            [
                                '$group' => [
                                    '_id' => '$_id',
                                    'title' => ['$first' => '$title' ],
                                    'due_date' => ['$first' => '$due_date' ],
                                    'project_no' => ['$first' => '$project_no' ],
                                    'buyer' => ['$first' => '$buyer' ],
                                    'sellers' => [
                                        '$push' => [
                                            'quotation_no' => '$sellers.quotation_no',
                                            'seller' => '$sellers.seller',
                                            'shipping' => '$sellers.shipping',
                                            'shipping_price' => '$sellers.shipping_price',
                                            'install' => '$sellers.install',
                                            'installation_price' => '$sellers.installation_price',
                                            'company' => '$sellers.company',
                                            'quantity' => '$sellers.quantity',
                                            'date_quotation' => '$sellers.date_quotation',
                                            'items' => '$sellers.items',
                                        ],
                                        
                                    ],



                                ]
                            ]   

                        ]);

                        foreach ($list as $key => $value) {
           
                            $quotation = $value['sellers'][0]['quotation_no'];
                            $company = $value['sellers'][0]['company'];

                        }

                        if (empty($quotation)) { // check Quotation no exist or not

                            // this will check this company already generate quotation or not, 
  
                            $checkQuotationNo = GenerateQuotationNo::find()->where(['company_id'=>$returnCompanySeller->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

                            if (empty($checkQuotationNo->quotation_no)) {

                                $runninNo = 1000;
                                $quotationTemp = 'QT'.$runninNo;

                                $returnAsiaebuyNo = substr($company, 6);
                                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                                $returnAsiaebuyNo;

                                $quotation = 'S'.$returnAsiaebuyNo.'-'.$quotationTemp;

                                $generateQuotationNo = new GenerateQuotationNo();
                                $generateQuotationNo->quotation_no = $quotationTemp;
                                $generateQuotationNo->company_id = $returnCompanySeller->company;
                                $generateQuotationNo->date_create = date('Y-m-d H:i:s');
                                $generateQuotationNo->enter_by = $seller_id->id;
                                $generateQuotationNo->save();

                            } else {


                                $qt = substr($checkQuotationNo->quotation_no, 2);
                                $new = $qt + 1;
                                $runninNo = $new;

                                $quotationTemp = 'QT'.$runninNo;

                                $returnAsiaebuyNo = substr($company, 6);
                                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                                $returnAsiaebuyNo;

                                $quotation = 'S'.$returnAsiaebuyNo.'-'.$quotationTemp;

                                $generateQuotationNo = new GenerateQuotationNo();
                                $generateQuotationNo->quotation_no = $quotationTemp;
                                $generateQuotationNo->company_id = $returnCompanySeller->company;
                                $generateQuotationNo->date_create = date('Y-m-d H:i:s');
                                $generateQuotationNo->enter_by = $seller_id->id;
                                $generateQuotationNo->save();


                            }


                        $arrUpdate = [
                            '$set' => [
                                'date_update' => date('Y-m-d h:i:s'),
                                'update_by' => Yii::$app->user->identity->id,
                                'sellers.$.quotation_no' => $quotation,
                                'sellers.$.date_quotation' => date('Y-m-d H:i:s'),

                            ]
                        
                        ];
                        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller_id->account_name],$arrUpdate);

                      

                        } else {



                        }


                }

          


            if ($path == 'frontend') {

                Yii::$app->getSession()->setFlash('cart', 'Your Have Added <b>'.$item_name.'</b> To Cart');
                return $this->redirect(['shop/index']);

            } else if ($path == 'product-details') {

                Yii::$app->getSession()->setFlash('cart', 'Your Have Added <b>'.$item_name.'</b> To Cart');
                return $this->redirect(['shop/view','id'=>(string)$item_id]);

            }


        } else {

            return $this->renderAjax('cart', [
                'model' => $model,
            ]);

        }

    }




    public function actionAdd($item_id,$seller,$path)
    {

        $model = new Project();
        $model2 = new LookupTitle();

        $seller_id = User::find()->where(['id'=>(int)$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>(int)$seller])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $buyer_id = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();

        if ($model->load(Yii::$app->request->post()) ) {

            $getP = Project::find()->orderBy(['_id' => SORT_DESC])->limit(1)->one();

            if ($_POST['temp'] == 1) {

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

                $item_id = new \MongoDB\BSON\ObjectID($item_id);
                $collection = Yii::$app->mongo->getCollection('item');
                $items = $collection->aggregate([
                    [
                        '$match' => [

                            '_id' => $item_id
                        ]
                    ],

                ]);

                $itemSerial = serialize($items);
       

                $model->project_no = $project_no;
                $model->type_of_project = 'Guide Buying';
                $model->date_create = date('Y-m-d h:i:s');
                $model->enter_by = Yii::$app->user->identity->id;
                $model->buyer = $buyer_id->account_name;
                $model->sellers =  [[
                    'seller'=> $seller_id->account_name,
                    'company' => $companySeller->asia_ebuy_no,
                    'quotation_no' => '',
                    'purchase_requisition_no' => '',
                    'purchase_order_no' => '',
                    'delivery_order_no' => '',
                    'invoice_buyer_no' => '',
                    'invoice_seller_no' => '',
                    'status'=>'Request',
                    'quantity' => $_POST['Project']['sellers'][0]['quantity'],
                    'items' =>  unserialize($itemSerial),
                    'date_quotation' => '',


                ]];


                $model->save();


            } elseif ($_POST['temp'] == 2) {

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

                $model2->title = $_POST['LookupTitle']['title'];
                $model2->date_create = date('Y-m-d h:i:s');
                $model2->enter_by = Yii::$app->user->identity->id;
                $model2->save();


                $item_id = new \MongoDB\BSON\ObjectID($item_id);
                $collection = Yii::$app->mongo->getCollection('item');
                $items = $collection->aggregate([
                    [
                        '$match' => [

                            '_id' => $item_id
                        ]
                    ],

                ]);

                $itemSerial = serialize($items);

                $model->title = $_POST['LookupTitle']['title'];
                //$model->item = new \MongoDB\BSON\ObjectID($item_id); // this to change string to ObjectID
                $model->project_no = $project_no;
                $model->type_of_project = 'Guide Buying';
                $model->date_create = date('Y-m-d h:i:s');
                $model->enter_by = Yii::$app->user->identity->id;
                $model->buyer = $buyer_id->account_name;
                $model->sellers =  [[
                    'seller'=> $seller_id->account_name,
                    'company' => $companySeller->asia_ebuy_no,
                    'quotation_no' => '',
                    'purchase_requisition_no' => '',
                    'purchase_order_no' => '',
                    'delivery_order_no' => '',
                    'invoice_buyer_no' => '',
                    'invoice_seller_no' => '',
                    'status'=>'Request',
                    'quantity' => $_POST['Project']['sellers'][0]['quantity'],
                    'items' =>  unserialize($itemSerial),
                ]];

                $model->save();



            }

            if ($path == 'frontend') {

                Yii::$app->getSession()->setFlash('request', 'Your Request Has Been Submit');
                return $this->redirect(['shop/index']);

            } else if ($path == 'product-details') {

                Yii::$app->getSession()->setFlash('request', 'Your Request Has Been Submit');
                return $this->redirect(['shop/view','id'=>(string)$item_id]);

            }


        } else {

            return $this->renderAjax('add', [
                'model' => $model,
                'model2' => $model2,
            ]);
            
        }


    }

    public function actionSpot()
    {

        $model = new Project();

        $buyer_id = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();

        if ($model->load(Yii::$app->request->post()) ) {

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

                $model->project_no = $project_no;
                $model->type_of_project = 'MySpot Buy';
                $model->date_create = date('Y-m-d h:i:s');
                $model->enter_by = Yii::$app->user->identity->id;
                $model->buyer = $buyer_id->account_name;
                $model->sellers = [];
                $model->save();


            Yii::$app->getSession()->setFlash('spot', 'Your MySpot Buy Request Has Been Submit');
            return $this->redirect(['source/index']);

        } else {

            return $this->render('spot',[
                'model' => $model,
            ]);

            
        }


    }

    public function actionCurl()
    {
        $url = $_POST['url'];

       // echo "<blockquote class='embedly-card' data-card-controls='0'><h4><a href=".$url."></a></h4></blockquote>";
        echo "<iframe sandbox='allow-same-origin allow-scripts allow-popups allow-forms' src='".$url."'  style='overflow: hidden;height:600px;width:100%;'></iframe>";

   /* $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);

        curl_setopt($ch, CURLOPT_URL, $url);

        $returned = curl_exec($ch);

        curl_close ($ch); */


    }


    public function actionServer()
    {
        return $this->render('server');
    }


}
