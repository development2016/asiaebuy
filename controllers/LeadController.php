<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\LookupTitle;
use app\models\User;
use app\models\Item;
use app\models\Company;
use kartik\mpdf\Pdf;
use app\models\LookupTerm;
use app\models\GenerateQuotationNo;
use app\models\UserCompany;
use app\models\AsiaebuyCompany;

class LeadController extends Controller
{

    public function actionIndex()
    {
        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $sales = $collection->aggregate([
            [
                '$match' => [
                    '$or' => [
                        [
                            'sellers' => [
                                '$size' => 0
                            ]
                        ],
                        [
                            'sellers.seller' => [
                                '$ne' => $user->account_name
                            ]
                        ],
                        [
                            'sellers.seller' => null
                        ],



                    ],
                    '$and' => [
                        [
                            'type_of_project' => 'Sale Lead'
                        ],

                    ],
                ]
            ],
            [
                '$sort' => [
                    'project_no' => -1
                ]
            ]


        ]);


        $guide = $collection->aggregate([
            [
                '$match' => [
                    '$and' => [
                        [
                            'sellers.status' => 'Request'
                        ],
                        [
                            'sellers.seller' =>  $user->account_name
                        ],
                        [
                            'type_of_project' => 'Guide Buying'
                        ]

                    ],
                ]
            ],
            [
                '$sort' => [
                    'project_no' => -1
                ]
            ]


    
        ]);

        $spot = $collection->aggregate([
            [
                '$match' => [
                    '$or' => [
                        [
                            'sellers' => [
                                '$size' => 0
                            ]
                        ],
                        [
                            'sellers.seller' => [
                                '$ne' => $user->account_name
                            ]
                        ],
                        [
                            'sellers.seller' => null
                        ],



                    ],
                    '$and' => [
                        [
                            'type_of_project' => 'MySpot Buy'
                        ],

                    ],
                ]
            ],
            [
                '$sort' => [
                    'project_no' => -1
                ]
            ]


        ]);






        $process = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$or' => [
                        [
                            'sellers.status' => 'Responded'
                        ],
                        [
                            'sellers.status' => 'Waiting Quotation'
                        ],



                    ],
                    'sellers.seller' => $user->account_name,
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
          
                ]
            ],
            [
                '$sort' => [
                    'project_no' => -1
                ]
            ]


        ]); 


        return $this->render('index', [
            'sales' => $sales,
            'guide' => $guide,
            'process' => $process,
            'spot' => $spot,
        ]);
    }

    public function actionGuideQuotation($project,$seller,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>(int)$buyer_info->id])->one();

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
                            'sellers.seller' => $seller,
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
                $generateQuotationNo->enter_by = Yii::$app->user->identity->id;
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
                $generateQuotationNo->enter_by = Yii::$app->user->identity->id;
                $generateQuotationNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Waiting Quotation',
                'sellers.$.quotation_no' => $quotation,
                'sellers.$.date_quotation' => date('Y-m-d H:i:s'),

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

      

        } else {



        }

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
                            'sellers.seller' => $seller,
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


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('guide-quotation',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'seller' => $seller,
            'project' => $project
        ]);



    }


    public function actionRespond($project)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $seller = User::find()->where(['id'=>(int)Yii::$app->user->identity->id])->one();// this to return hex user id

        if ($model->load(Yii::$app->request->post()) ) {

            $status = $_POST['Project']['sellers']['status']; // this to get the status from respond page

            $tempProject = Project::find()->where(['_id'=> (string)$newProject_id])->one();

                $sellers = $tempProject['sellers'];

                $count = count($sellers);

                if ($count == 0) {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [ 
                        'sellers' => [
                            [
                                'seller' => $seller->account_name,
                                'status' => $status,
                                'items' => []
                            ]

                        ],
                    
                    ];


                    $collection->update(['_id' => (string)$newProject_id],$arrUpdate);
                   
                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => (string)$newProject_id],
                        [
                            '$addToSet' => [
                                'sellers' => [
                                    'seller' => $seller->account_name,
                                    'status' => $status,
                                    'items' => []
                                ]
                            ]
                        ]

                    );



                }

                
    
           Yii::$app->getSession()->setFlash('respond', 'Responded');
           return $this->redirect('index');


        } else {


            return $this->renderAjax('respond',[
                'seller' => $seller,
                'model' => $model,

            ]);


        }

    }


    public function actionSaleQuotation($project,$seller,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>(int)$buyer_info->id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $check = $collection->aggregate([
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
                            'sellers.seller' => $seller,
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
                            'company' => '$sellers.company',

                        ],
                        
                    ],



                ]
            ]   

        ]);

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        foreach ($check as $key => $value) {
           
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
                $generateQuotationNo->enter_by = Yii::$app->user->identity->id;
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
                $generateQuotationNo->enter_by = Yii::$app->user->identity->id;
                $generateQuotationNo->save();


            }


            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'Waiting Quotation',
                    'sellers.$.quotation_no' => $quotation,
                    'sellers.$.date_quotation' => date('Y-m-d H:i:s'),

                ]
            
            ];
            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);
            



        } else {


        }


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
                            'sellers.seller' => $seller,
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
                    'tax_value' => ['$first' => '$tax_value' ],
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



            return $this->render('sale-quotation',[
                'return_asiaebuy' => $return_asiaebuy,
                'list' => $list,
                'companySeller' => $companySeller,
                'seller' => $seller,
                'project' => $project
            ]);


    }

    public function actionSpotQuotation($project,$seller,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>(int)$buyer_info->id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $check = $collection->aggregate([
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
                            'sellers.seller' => $seller,
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
                            'company' => '$sellers.company',

                        ],
                        
                    ],



                ]
            ]   

        ]);

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        foreach ($check as $key => $value) {
           
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
                $generateQuotationNo->enter_by = Yii::$app->user->identity->id;
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
                $generateQuotationNo->enter_by = Yii::$app->user->identity->id;
                $generateQuotationNo->save();


            }


            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'Waiting Quotation',
                    'sellers.$.quotation_no' => $quotation,
                    'sellers.$.date_quotation' => date('Y-m-d H:i:s'),

                ]
            
            ];
            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);
            



        } else {


        }


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
                            'sellers.seller' => $seller,
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



            return $this->render('spot-quotation',[
                'return_asiaebuy' => $return_asiaebuy,
                'list' => $list,
                'companySeller' => $companySeller,
                'seller' => $seller,
                'project' => $project
            ]);


    }







    public function actionItem($seller,$project,$path)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $process = $collection->aggregate([
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
                            'sellers.seller' => $seller,
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
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        $items = $process[0]['sellers'][0]['items'];

        $countitem = count($items); // this to count how many items for specific sellers */


        if ($model->load(Yii::$app->request->post())) {

            if ($countitem == 0) {


                if ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                } elseif ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'No') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [

                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);





                } elseif ($_POST['Project']['sellers']['items']['install'] == 'No' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [

                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $arrUpdate = [
                        '$set' => [
                            'sellers.$.items' => [
                                [
           
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,

                                ]
                            ],

                        ]
                    
                        ];
                    $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



                }

            } else {

                if ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [

                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                
                                ]
                            ]
                        ]

                    );

                } elseif ($_POST['Project']['sellers']['items']['install'] == 'Yes' && $_POST['Project']['sellers']['items']['shipping'] == 'No') {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => $_POST['Project']['sellers']['items']['installation_price'],
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                
                                ]
                            ]
                        ]

                    );


                } elseif ($_POST['Project']['sellers']['items']['install'] == 'No' && $_POST['Project']['sellers']['items']['shipping'] == 'Yes') {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => $_POST['Project']['sellers']['items']['shipping_price'],
                                
                                ]
                            ]
                        ]

                    );



                } else {



                    $collection = Yii::$app->mongo->getCollection('project');
                    $collection->update(
                        ['_id' => $newProject_id,'sellers.seller' => $seller],
                        [
                            '$push' => [ // $push to add items in array 
                                'sellers.$.items' => [
                                    'item_name' => $_POST['Project']['sellers']['items']['item_name'],
                                    'brand' => $_POST['Project']['sellers']['items']['brand'],
                                    'model' => $_POST['Project']['sellers']['items']['model'],
                                    'description' => $_POST['Project']['sellers']['items']['description'],
                                    'specification' => $_POST['Project']['sellers']['items']['specification'],
                                    'lead_time' => $_POST['Project']['sellers']['items']['lead_time'],
                                    'validity' => $_POST['Project']['sellers']['items']['validity'],
                                    'cost' => $_POST['Project']['sellers']['items']['cost'],
                                    'quantity' => $_POST['Project']['sellers']['items']['quantity'],
                                    'install' => $_POST['Project']['sellers']['items']['install'],
                                    'installation_price' => 0,
                                    'shipping' => $_POST['Project']['sellers']['items']['shipping'],
                                    'shipping_price' => 0,
                                
                                ]
                            ]
                        ]

                    );



                }




            }



            if ($path == 'revise') {
               return $this->redirect([
                'quote/sale-revise', 
                'project' => (string)$newProject_id,
                'seller'=>$seller,
                'buyer' => $process[0]['buyer']
                ]);
            } else if ($path == 'lead') {
                return $this->redirect([
                    'lead/sale-quotation', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyer']
                ]);
            }


           
  
        } else {
            return $this->renderAjax('item', [
                'model' => $model,
            ]);
        }
    }




}