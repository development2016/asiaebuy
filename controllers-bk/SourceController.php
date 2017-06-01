<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\LookupTitle;
use app\models\Message;
use app\models\User;
use app\models\UserCompany;
use app\models\Item;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\AsiaebuyCompany;
use app\models\GeneratePurchaseRequisitionNo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ItemOffline;

class SourceController extends Controller
{

    public function actionIndex()
    {

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();

    	$collection = Yii::$app->mongo->getCollection('project');
		$model = $collection->aggregate([
			[
				'$unwind' => '$sellers'
			], 
			[
				'$match' => [
					'$or' => [
						[
							'sellers' => [
								'$size' =>0
							]
						],
						[
							'sellers.status' => 'Quoted'
						],
						[
							'sellers.status' => 'Responded'
						],
						[
							'sellers.status' => 'Request'
						],
						[
							'sellers.status' => 'Waiting Quotation'
						],
						[
							'sellers.status' => 'Generate PR'
						],
                        [
                            'sellers.status' => 'Revise'
                        ],
                        [
                            'sellers.status' => 'Quotation Uploaded'
                        ],



					],
                    '$and' => [
                            [
                                'buyer' => $user->account_name
                            ],

                            
                    ],

					
				]
			],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date'],
                    'date_create' => ['$first' => '$date_create'],
                    'description' => ['$first' => '$description' ],
                    'url_myspot' => ['$first' => '$url_myspot' ],
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'quotation_file' => ['$first' => '$quotation_file' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'status' => '$sellers.status',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'items' => '$sellers.items',
                            'approver' => '$sellers.approver'
                            
                        ],
                        
                    ],


            
                ]
            ],
            [
                '$sort' => [
                    '_id' => -1
                ]
            ],


		]);

 

        $history = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ], 
            [
                '$match' => [
                    '$or' => [
                        [
                            'sellers.history.quotation_no' => [
                                '$exists' => true
                            ]
                        ],

                    ],
                    '$and' => [
                            [
                                'buyer' => $user->account_name
                            ]
                    ],

                    
                ]
            ],
            [
                '$sort' => [
                    'project_no' => 1
                ]
            ],
            [
                '$group' => [
                    '_id' => '$_id',
                    'title' => ['$first' => '$title' ],
                    'due_date' => ['$first' => '$due_date'],
                    'date_create' => ['$first' => '$date_create'],
                    'description' => ['$first' => '$description' ],
                    'url_myspot' => ['$first' => '$url_myspot' ],
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'quotation_file' => ['$first' => '$quotation_file' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'status' => '$sellers.status',
                            'history' => '$sellers.history',
                            'items' => '$sellers.items',

                        ],
                        
                    ],


            
                ]
            ]

        ]);




        return $this->render('index',[
        	'model' => $model,
            'history' => $history,
        ]);
    }

    public function actionRequest($project,$seller)
    {

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'tax_value' => $return_asiaebuy->gst_cost,
                    'sellers.$.status' => "Waiting Quotation", // $ this for specific item
                    'sellers.$.date_update' => date('Y-m-d h:i:s'),
                    'sellers.$.update_by' => Yii::$app->user->identity->id,
                    'sellers.$.items' => [],
                    'sellers.$.company' => $companySeller->asia_ebuy_no,
                    'sellers.$.quotation_no' => '',
                    'sellers.$.purchase_requisition_no' => '',
                    'sellers.$.purchase_order_no' => '',
                    'sellers.$.delivery_order_no' => '',
                    'sellers.$.invoice_buyer_no' => '',
                    'sellers.$.invoice_seller_no' => '',

                ]
            
                ];
        $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


       Yii::$app->getSession()->setFlash('request', 'Request Quotation');
       return $this->redirect('index');


    }




    public function actionGuidePurchaseRequisition($project,$seller,$buyer,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


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
                    'tax_value' => ['$first' => '$tax_value' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_requisition_no = $value['sellers'][0]['purchase_requisition_no'];
            $buyer = $value['buyer'];


        }

        if (empty($purchase_requisition_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseRequisitionNo = GeneratePurchaseRequisitionNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;




            if (empty($checkPurchaseRequisitionNo->purchase_requisition_no)) {

                $runninNo = 1000;
                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();

            } else {


                $pr = substr($checkPurchaseRequisitionNo->purchase_requisition_no, 2);
                $new = $pr + 1;
                $runninNo = $new;

                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_requisition_no' => $purchase_requisition_no,
                'sellers.$.date_purchase_requisition' => date('Y-m-d H:i:s'),

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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('guide-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer' => $buyer,
            'approver' => $approver
        ]);

    }

    public function actionSalePurchaseRequisition($project,$seller,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


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
                    'tax_value' => ['$first' => '$tax_value' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_requisition_no = $value['sellers'][0]['purchase_requisition_no'];
            $buyer = $value['buyer'];

        }

        if (empty($purchase_requisition_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseRequisitionNo = GeneratePurchaseRequisitionNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;





            if (empty($checkPurchaseRequisitionNo->purchase_requisition_no)) {

                $runninNo = 1000;
                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();

            } else {


                $pr = substr($checkPurchaseRequisitionNo->purchase_requisition_no, 2);
                $new = $pr + 1;
                $runninNo = $new;

                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_requisition_no' => $purchase_requisition_no,
                'sellers.$.date_purchase_requisition' => date('Y-m-d H:i:s'),

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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();


        return $this->render('sale-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer' => $buyer,
        ]);


    }

    public function actionSpotPurchaseRequisition($project,$seller,$buyer)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


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
                    'tax_value' => ['$first' => '$tax_value' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_requisition_no = $value['sellers'][0]['purchase_requisition_no'];
            $buyer = $value['buyer'];

        }

        if (empty($purchase_requisition_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseRequisitionNo = GeneratePurchaseRequisitionNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();


            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;



            if (empty($checkPurchaseRequisitionNo->purchase_requisition_no)) {

                $runninNo = 1000;
                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();

            } else {


                $pr = substr($checkPurchaseRequisitionNo->purchase_requisition_no, 2);
                $new = $pr + 1;
                $runninNo = $new;

                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_requisition_no' => $purchase_requisition_no,
                'sellers.$.date_purchase_requisition' => date('Y-m-d H:i:s'),

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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);

       // print_r($list);
       // exit();


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('spot-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer' => $buyer,
        ]);

    }

    public function actionDirectPurchaseRequisition($project,$buyer,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();


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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_requisition_no = $value['sellers'][0]['purchase_requisition_no'];
            $buyer = $value['buyer'];

        }

        if (empty($purchase_requisition_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseRequisitionNo = GeneratePurchaseRequisitionNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;

            if (empty($checkPurchaseRequisitionNo->purchase_requisition_no)) {

                $runninNo = 1000;
                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();

            } else {


                $pr = substr($checkPurchaseRequisitionNo->purchase_requisition_no, 2);
                $new = $pr + 1;
                $runninNo = $new;

                $purchaseRequisitionTemp = 'PR'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_requisition_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseRequisitionTemp;

                $generatePurchaseRequisitionNo = new GeneratePurchaseRequisitionNo();
                $generatePurchaseRequisitionNo->purchase_requisition_no = $purchaseRequisitionTemp;
                $generatePurchaseRequisitionNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseRequisitionNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseRequisitionNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseRequisitionNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_requisition_no' => $purchase_requisition_no,
                'sellers.$.date_purchase_requisition' => date('Y-m-d H:i:s'),

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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                             'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer
        ]);

    }

    public function actionItem($seller,$project,$path)
    {
        $offline = new ItemOffline();

        $data = ItemOffline::find()->all();

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


                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();


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

                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  0;
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();



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

                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();





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

                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  0;
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();






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

                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();




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

                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  $_POST['Project']['sellers']['items']['installation_price'];
                    $offline->shipping =  0;
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();




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

                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  $_POST['Project']['sellers']['items']['shipping_price'];
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();





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


                    $offline->item_name =  $_POST['Project']['sellers']['items']['item_name'];
                    $offline->brand =  $_POST['Project']['sellers']['items']['brand'];
                    $offline->model =  $_POST['Project']['sellers']['items']['model'];
                    $offline->description =  $_POST['Project']['sellers']['items']['description'];
                    $offline->specification =  $_POST['Project']['sellers']['items']['specification'];
                    $offline->lead_time =  $_POST['Project']['sellers']['items']['lead_time'];
                    $offline->validity =  $_POST['Project']['sellers']['items']['validity'];
                    $offline->cost =  $_POST['Project']['sellers']['items']['cost'];
                    $offline->quantity =  $_POST['Project']['sellers']['items']['quantity'];
                    $offline->cit =  0;
                    $offline->shipping =  0;
                    $offline->date_create = date('Y-m-d H:i:s');
                    $offline->enter_by = Yii::$app->user->identity->id;
                    $offline->save();




                }




            }




                return $this->redirect([
                    'source/direct-purchase-requisition', 
                    'project' => (string)$newProject_id,
                    'seller'=>$seller,
                    'buyer' => $process[0]['buyer']
                ]);
            


           
  
        } else {
            return $this->renderAjax('item', [
                'model' => $model,
                'data' => $data,
            ]);
        }
    }


    public function actionChooseApproval($project,$seller,$buyer,$type)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;
        $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                WHERE acl.company_id = '".$returnCompanyBuyer->company."' AND acl.acl_menu_id = 26
        ");
        $approval = $sql->queryAll();

        if ($model->load(Yii::$app->request->post()) ) {

                foreach ($_POST['Project']['sellers']['approval'] as $key => $value) {
                    
                    $tempApp[] = [
                        'approval' => $value,

            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'sellers.$.approver' => 'normal',
                        'sellers.$.approval' =>  $tempApp

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);
            

            if ($type == 'guide') {

                return $this->redirect(['source/guide-purchase-requisition','project'=>$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>'normal']);

            } elseif ($type == 'sale') {

                return $this->redirect(['source/sale-purchase-requisition','project'=>$project,'seller'=>$seller,'buyer'=>$buyer]);
             
            } elseif ($type == 'spot') {

                return $this->redirect(['source/spot-purchase-requisition','project'=>$project,'seller'=>$seller,'buyer'=>$buyer]);
           
            } elseif ($type == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition','project'=>$project,'seller'=>$seller,'buyer'=>$buyer]);
           
            }



        } else {

           return $this->renderAjax('choose-approval',[
                'approval' => $approval,
                'model' => $model,

            ]);

        }



    }


    public function actionChooseApprovalLevel($project,$seller,$buyer,$type)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;
        $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                WHERE acl.company_id = '".$returnCompanyBuyer->company."' AND acl.acl_menu_id = 26
        ");
        $approval = $sql->queryAll();


       return $this->renderAjax('choose-approval-level',[
            'approval' => $approval,
            'model' => $model,
            'buyer' => $buyer,
            'project' => $project,
            'seller' => $seller,
            'type' => $type

        ]);

        


    }




    public function actionLevel()
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($_POST['project']);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$_POST['buyer']])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;
        $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                WHERE acl.company_id = '".$returnCompanyBuyer->company."' AND acl.acl_menu_id = 26
        ");
        $approval = $sql->queryAll();


        $form = ActiveForm::begin(['action' =>['source/approver'], 'id' => 'forum_post', 'method' => 'post',]);

        echo "<input type='hidden' name='project' value='".$_POST['project']."' />";
        echo "<input type='hidden' name='seller' value='".$_POST['seller']."' />";
        echo "<input type='hidden' name='type' value='".$_POST['type']."' />";
        echo "<input type='hidden' name='buyer' value='".$_POST['buyer']."' />";

        $a=0;
        for ($i=0; $i < $_POST['level']; $i++) { $a++;

            echo "<label>Approver Level ".$a."</label>";
            echo "<select class='form-control' name='approval[approver_no_".$a."]'>";
            foreach ($approval as $key => $value) {
                echo "<option>".$value['account_name']."</option>";
            }
        
            echo "</select>";
            echo "<br>";

        }

        echo "<div class='form-group'>";
        echo Html::submitButton($model->isNewRecord ? 'Choose' : 'Choose', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
        echo "</div>";

        ActiveForm::end();



    }

    public function actionApprover()
    {

        foreach ($_POST['approval'] as $key => $value) {
            
            $tempApp[] = [
                'approval' => $value,
                'status' => 'Waiting Approval'
    
            ];

           

        }



        $collection = Yii::$app->mongo->getCollection('project');

        $arrUpdate = [
            '$set' => [
                'sellers.$.approver' => 'level',
                'sellers.$.approval' =>  $tempApp,

            ]
        
        ];


        $collection->update(['_id' => $_POST['project'],'sellers.seller' => $_POST['seller']],$arrUpdate);


        if ($_POST['type'] == 'guide') {

            return $this->redirect(['source/guide-purchase-requisition','project'=>$_POST['project'],'seller'=>$_POST['seller'],'buyer'=>$_POST['buyer'],'approver'=>'level']);

        } elseif ($_POST['type'] == 'sale') {

            return $this->redirect(['source/sale-purchase-requisition','project'=>$_POST['project'],'seller'=>$_POST['seller'],'buyer'=>$_POST['buyer']]);
         
        } elseif ($_POST['type'] == 'spot') {

            return $this->redirect(['source/spot-purchase-requisition','project'=>$_POST['project'],'seller'=>$_POST['seller'],'buyer'=>$_POST['buyer']]);
       
        } elseif ($_POST['type'] == 'direct') {

            return $this->redirect(['source/direct-purchase-requisition','project'=>$_POST['project'],'seller'=>$_POST['seller'],'buyer'=>$_POST['buyer']]);
       
        }



    }


}