<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\LookupTitle;
use app\models\Chat;
use app\models\User;
use app\models\UserCompany;
use app\models\Item;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use kartik\mpdf\Pdf;
use app\models\AsiaebuyCompany;
use app\models\LookupTerm;
use app\models\GeneratePurchaseOrderNo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class RequestController extends Controller
{



    public function actionIndex()
    {

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();


         $connection = \Yii::$app->db;
         $sql = $connection->createCommand('SELECT lookup_role.role AS role FROM acl 
          RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
          RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
          RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
          WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
        $getRole = $sql->queryAll(); 

        // this function will check whether use have 'Buyer' role or not
            function in_array_r($needle, $haystack, $strict = false) {
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                        return true;
                    }
                }

                return false;
            }


        $info_role = in_array_r('Buyer', $getRole) ? 'Found' : 'Not Found';
        $info_role_2 = in_array_r('User', $getRole) ? 'Found' : 'Not Found';


       /* echo $info_role;
        echo "<br>";
        echo $info_role_2;
        exit(); */


        $collection = Yii::$app->mongo->getCollection('project');



        $model = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ], 
            [
                '$match' => [
                    '$or' => [
                            [
                                'sellers.status' => 'Request Approval'
                            ],
                            [
                                'sellers.status' => 'Approve'
                            ],
                            [
                                'sellers.status' => 'Pending Approval'
                            ],
                            [
                                'sellers.status' => 'Pass PR to Buyer To Proceed PO'
                            ],
                            [
                                'sellers.temp_status' => 'Change Buyer'
                            ],
                            [
                                'sellers.status' => 'PO In Progress'
                            ],
                            [
                                'sellers.status' => 'Request Approval Next'
                            ],


                    ],
                    '$and' => [
                            [
                                'buyers.buyer' => $user->account_name
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
                    'buyers' => ['$first' => '$buyers' ],
                    'requester' => ['$first'=> '$requester'],
                    'project_no' => ['$first' => '$project_no' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'status' => '$sellers.status',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'items' => '$sellers.items',
                            'approver' => '$sellers.approver',
                            'temp_status' => '$sellers.temp_status',
                            'approve_by' => '$sellers.approve_by',
                            'approver_level' => '$sellers.approver_level',
                            'PO_process_by' => '$sellers.PO_process_by',
                            'approver_next' => '$sellers.approver_next',
                            'approval_next' => '$sellers.approval_next',
                            'has_second_approval' => '$sellers.has_second_approval'
                            
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




        return $this->render('index',[
            'model' => $model,
            'info_role' => $info_role,
            'info_role_2' => $info_role_2,
            'user'=> $user


        ]);

    }






    public function actionRequest()
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
    							'sellers.approval.approval' => $user->account_name
    						],
                            [
                                'sellers.approval_next.approval_next' => $user->account_name
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
                    'buyers' => ['$first' => '$buyers' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'status' => '$sellers.status',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'approver' => '$sellers.approver',
                            'items' => '$sellers.items',
                            'approver_level' => '$sellers.approver_level',
                            'approver_next' => '$sellers.approver_next',
                            'approval_next' => '$sellers.approval_next',
                            'approver_level_next' => '$sellers.approver_level_next',


                            
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



        return $this->render('request',[
        	'model' => $model,
            'user' => $user,

        ]);

    }

    public function actionGuidePurchaseRequisition($project,$seller,$buyer)
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
            
        ]);

    }

    public function actionGuidePurchaseRequisitionApprove($project,$seller,$buyer,$approver)
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


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('guide-purchase-requisition-approve',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
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


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('sale-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project
        ]);

    }


    public function actionSalePurchaseRequisitionApprove($project,$seller,$buyer)
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


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('sale-purchase-requisition-approve',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project
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


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('spot-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project
        ]);

    }

    public function actionSpotPurchaseRequisitionApprove($project,$seller,$buyer)
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


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('spot-purchase-requisition-approve',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project
        ]);

    }


    public function actionDirectPurchaseRequisition($project,$buyer,$seller,$approver)
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



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver' => $approver
        ]);




}

    public function actionDirectPurchaseRequisitionApprove($project,$buyer,$seller,$approver)
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



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-approve',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver' => $approver
        ]);


    }


    public function actionDirectPurchaseRequisitionApproveNext($project,$buyer,$seller,$approver_next)
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



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-requisition-approve-next',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer,
             'approver_next' => $approver_next
        ]);


    }







    public function actionGuidePurchaseOrder($project,$seller,$buyer)
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
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        foreach ($list as $key => $value) {
           
            $purchase_order_no = $value['sellers'][0]['purchase_order_no'];
            $buyer = $value['buyer'];


        }

        if (empty($purchase_order_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseOrderNo = GeneratePurchaseOrderNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;




            if (empty($checkPurchaseOrderNo->purchase_order_no)) {

                $runninNo = 1000;
                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();

            } else {


                $po = substr($checkPurchaseOrderNo->purchase_order_no, 2);
                $new = $po + 1;
                $runninNo = $new;

                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_order_no' => $purchase_order_no,
                'sellers.$.date_purchase_order' => date('Y-m-d H:i:s'),

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
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('guide-purchase-order',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer' => $buyer
        ]);

    }

    public function actionSalePurchaseOrder($project,$seller,$buyer)
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
                            'purchase_order_no' => '$sellers.purchase_order_no',
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
           
            $purchase_order_no = $value['sellers'][0]['purchase_order_no'];
            $buyer = $value['buyer'];

        }

        if (empty($purchase_order_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseOrderNo = GeneratePurchaseOrderNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;


            if (empty($checkPurchaseOrderNo->purchase_order_no)) {

                $runninNo = 1000;
                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();

            } else {


                $po = substr($checkPurchaseOrderNo->purchase_order_no, 2);
                $new = $po + 1;
                $runninNo = $new;

                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_order_no' => $purchase_order_no,
                'sellers.$.date_purchase_order' => date('Y-m-d H:i:s'),

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
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();


        return $this->render('sale-purchase-order',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer' => $buyer,
        ]);


    }

    public function actionSpotPurchaseOrder($project,$seller,$buyer)
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
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $purchase_order_no = $value['sellers'][0]['purchase_order_no'];
            $buyer = $value['buyer'];

        }

        if (empty($purchase_order_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseOrderNo = GeneratePurchaseOrderNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();


            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;



            if (empty($checkPurchaseOrderNo->purchase_order_no)) {

                $runninNo = 1000;
                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();

            } else {


                $po = substr($checkPurchaseOrderNo->purchase_order_no, 2);
                $new = $po + 1;
                $runninNo = $new;

                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_order_no' => $purchase_order_no,
                'sellers.$.date_purchase_order' => date('Y-m-d H:i:s'),

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
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_purchase_order' => '$sellers.date_purchase_order',
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

        return $this->render('spot-purchase-order',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer' => $buyer,
        ]);

    }







    public function actionDirectPurchaseOrder($project,$buyer,$seller)
    {

        $getUser = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();


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
                    'buyers' => ['$first' => '$buyers' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
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
           
            $purchase_order_no = $value['sellers'][0]['purchase_order_no'];
            $buyer = $buyer;

        }

        if (empty($purchase_order_no)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkPurchaseOrderNo = GeneratePurchaseOrderNo::find()->where(['company_id'=>$returnCompanyBuyer->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();

            $buyer_id = User::find()->where(['account_name'=>$buyer])->one();

            $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_id->id])->one();

            $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

            $company = $companyBuyer->asia_ebuy_no;

            if (empty($checkPurchaseOrderNo->purchase_order_no)) {

                $runninNo = 1000;
                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();

            } else {


                $po = substr($checkPurchaseOrderNo->purchase_order_no, 2);
                $new = $po + 1;
                $runninNo = $new;

                $purchaseOrderTemp = 'PO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 5);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $purchase_order_no = 'B'.$returnAsiaebuyNo.'-'.$purchaseOrderTemp;

                $generatePurchaseOrderNo = new GeneratePurchaseOrderNo();
                $generatePurchaseOrderNo->purchase_order_no = $purchaseOrderTemp;
                $generatePurchaseOrderNo->company_id = $returnCompanyBuyer->company;
                $generatePurchaseOrderNo->date_create = date('Y-m-d H:i:s');
                $generatePurchaseOrderNo->enter_by = Yii::$app->user->identity->id;
                $generatePurchaseOrderNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.purchase_order_no' => $purchase_order_no,
                'sellers.$.date_purchase_order' => date('Y-m-d H:i:s'),
                'sellers.$.status' => 'PO In Progress',
                'sellers.$.PO_process_by' => $buyer_info->account_name,

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
                    'buyers' => ['$first' => '$buyers' ],
                    'sellers' => [
                        '$push' => [
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'term' => '$sellers.term',
                            'items' => '$sellers.items',
                            'seller' => '$sellers.seller',
                            'tax' => '$sellers.tax',
                            'warehouses' => '$sellers.warehouses',
                            'PO_process_by' => '$sellers.PO_process_by'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-order',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'getUser' => $getUser,
            'buyer'=> $buyer
        ]);

    }




    public function actionApprove($project,$seller,$approver)
    {


        $approval_info = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        if ($approver == 'level') {

                $collection = Yii::$app->mongo->getCollection('project');
                $checkApprover = $collection->aggregate([
                    [
                        '$unwind' => '$sellers'
                    ], 
                    [
                        '$unwind' => '$sellers.approval'
                    ], 
                    [
                        '$match' => [
                            '$and' => [
                                    [
                                        'sellers.approval.status' => ''
                                    ],
                            ],


                            '_id' => $newProject_id,
                            'sellers.seller' => $seller,
                        ]
                    ],


                    [
                        '$group' => [
                            '_id' => '$_id',
                            'title' => ['$first' => '$title' ],
                            'sellers' => [
                                '$push' => [
                                    'approval' => '$sellers.approval',

                                    
                                ],
                                
                            ],


                    
                        ]
                    ],



                ]);



                if (empty($checkApprover[0]['sellers'])) {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndex = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);



                    foreach ($checkIndex[0]['sellers'][0]['approval'] as $key => $value) {

                        if ($value['status'] == 'Waiting Approval') {

                            $getKey =  $key;
              
                
                        }
                    }


                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval.'.$getKey.'.status' => 'Approve',
                                'sellers.$.status' => 'Approve',
                                'sellers.$.approver_level' => ''

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);




                        // update status to approve
                        // update status approver to approve

                    
                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndexs = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],



                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);







                    foreach ($checkIndexs[0]['sellers'][0]['approval'] as $key => $value) {


                        if ($value['status'] == 'Waiting Approval') {

                            $getKey =  $key;
                            $newKey = $getKey+1;
                
                        }

                        
                    }



                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval.'.$getKey.'.status' => 'Approve',
                                'sellers.$.approval.'.$newKey.'.status' => 'Waiting Approval',

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);

                        // check next approver
                    $checkApproverNext = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$unwind' => '$sellers.approval'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval.status' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],


                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval' => '$sellers.approval',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);

                    $checkApproverNext[0]['sellers'][0]['approval']['approval'];

                    // save status to next approver to approve
                    $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approver_level' => $checkApproverNext[0]['sellers'][0]['approval']['approval'],


                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);




                }



        } else {

            $collection = Yii::$app->mongo->getCollection('project');
            $model = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '_id' => $newProject_id,
                        'sellers.seller' => $seller,
                    ]
                ],

            ]); 


            $purchase_requisition_no = $model[0]['sellers']['purchase_requisition_no'];

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'Approve',
                    'sellers.$.approve_by' => $approval_info->account_name,

                ],

                            
            ];

            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



        }


        return $this->redirect(['request/request']);
    }

    public function actionApproveNext($project,$seller,$approver_next)
    {


        $approval_info = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();


        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        if ($approver_next == 'level') {

                $collection = Yii::$app->mongo->getCollection('project');
                $checkApprover = $collection->aggregate([
                    [
                        '$unwind' => '$sellers'
                    ], 
                    [
                        '$unwind' => '$sellers.approval_next'
                    ], 
                    [
                        '$match' => [
                            '$and' => [
                                    [
                                        'sellers.approval_next.status_next' => ''
                                    ],
                            ],


                            '_id' => $newProject_id,
                            'sellers.seller' => $seller,
                        ]
                    ],


                    [
                        '$group' => [
                            '_id' => '$_id',
                            'title' => ['$first' => '$title' ],
                            'sellers' => [
                                '$push' => [
                                    'approval_next' => '$sellers.approval_next',

                                    
                                ],
                                
                            ],


                    
                        ]
                    ],



                ]);



                if (empty($checkApprover[0]['sellers'])) {


                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndex = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval_next.status_next' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],
                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval_next' => '$sellers.approval_next',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);



                    foreach ($checkIndex[0]['sellers'][0]['approval_next'] as $key => $value) {

                        if ($value['status_next'] == 'Waiting Approval') {

                            $getKey =  $key;
              
                
                        }
                    }


                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval_next.'.$getKey.'.status_next' => 'Approve',
                                'sellers.$.status' => 'Approve',
                                'sellers.$.approver_level_next' => '',
                                'sellers.$.has_second_approval' => 'Yes'

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);




                        // update status to approve
                        // update status approver to approve

                    
                } else {

                    $collection = Yii::$app->mongo->getCollection('project');
                    $checkIndexs = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval_next.status_next' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],



                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval_next' => '$sellers.approval_next',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);




                    foreach ($checkIndexs[0]['sellers'][0]['approval_next'] as $key => $value) {


                        if ($value['status_next'] == 'Waiting Approval') {

                            $getKey =  $key;
                            $newKey = $getKey+1;
                
                        }

                        
                    }



                
                        // update status approver
                        $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approval_next.'.$getKey.'.status_next' => 'Approve',
                                'sellers.$.approval_next.'.$newKey.'.status_next' => 'Waiting Approval',

                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);

                        // check next approver
                    $checkApproverNext = $collection->aggregate([
                        [
                            '$unwind' => '$sellers'
                        ], 
                        [
                            '$unwind' => '$sellers.approval_next'
                        ], 
                        [
                            '$match' => [
                                '$and' => [
                                        [
                                            'sellers.approval_next.status_next' => 'Waiting Approval'
                                        ],
                                ],


                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,
                            ]
                        ],


                        [
                            '$group' => [
                                '_id' => '$_id',
                                'title' => ['$first' => '$title' ],
                                'sellers' => [
                                    '$push' => [
                                        'approval_next' => '$sellers.approval_next',

                                        
                                    ],
                                    
                                ],


                        
                            ]
                        ],



                    ]);

                    $checkApproverNext[0]['sellers'][0]['approval_next']['approval_next'];

                    // save status to next approver to approve
                    $collection = Yii::$app->mongo->getCollection('project');
                        $arrUpdate = [
                            '$set' => [
                                'sellers.$.approver_level_next' => $checkApproverNext[0]['sellers'][0]['approval_next']['approval_next'],


                            ]
                        
                        ];
                        $collection->update(
                            [
                                '_id' => $newProject_id,
                                'sellers.seller' => $seller,

                        ],$arrUpdate);




                }



        } else {

            $collection = Yii::$app->mongo->getCollection('project');
            $model = $collection->aggregate([
                [
                    '$unwind' => '$sellers'
                ],
                [
                    '$match' => [
                        '_id' => $newProject_id,
                        'sellers.seller' => $seller,
                    ]
                ],

            ]); 


            $purchase_requisition_no = $model[0]['sellers']['purchase_requisition_no'];

            $collection = Yii::$app->mongo->getCollection('project');
            $arrUpdate = [
                '$set' => [
                    'date_update' => date('Y-m-d h:i:s'),
                    'update_by' => Yii::$app->user->identity->id,
                    'sellers.$.status' => 'Approve',
                    'sellers.$.approve_by_next' => $approval_info->account_name,


                ],

                            
            ];

            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



        }


        return $this->redirect(['request/request']);
    }












    public function actionChooseBuyer($project,$seller,$buyer,$role)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $connection = \Yii::$app->db;

        if ($role == 'user') {

            $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                RIGHT JOIN acl_menu ON acl_menu.id = acl.acl_menu_id
                WHERE acl.company_id ='".$returnCompanyBuyer->company."' AND acl_menu.role_id = 3100

                GROUP BY user.username");
            $buyer_list = $sql->queryAll();


        } elseif ($role == 'buyer') {

            $sql = $connection->createCommand("SELECT * FROM acl 
                RIGHT JOIN user ON acl.user_id = user.id
                RIGHT JOIN acl_menu ON acl_menu.id = acl.acl_menu_id
                WHERE acl.company_id ='".$returnCompanyBuyer->company."' AND acl_menu.role_id = 3100 AND acl.user_id != '".Yii::$app->user->identity->id."'

                GROUP BY user.username");
            $buyer_list = $sql->queryAll();




        }



        if ($model->load(Yii::$app->request->post()) ) {

            if ($role == 'user') {

                foreach ($_POST['Project']['sellers']['buyer'] as $key => $value) {
                    
                    $tempApp[] = [
                        'buyer' => $value,
            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'buyers' =>  $tempApp,
                        'sellers.$.status' => 'Pass PR to Buyer To Proceed PO'

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif ($role == 'buyer') {

                foreach ($_POST['Project']['sellers']['buyer'] as $key => $value) {
                    
                    $tempApp[] = [
                        'buyer' => $value,
            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'buyers' =>  $tempApp,
                        'from_buyer' => $buyer,
                        'sellers.$.temp_status' => 'Change Buyer'

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);



            }

            

             return $this->redirect(['request/index']);

        } else {

           return $this->renderAjax('choose-buyer',[
                'buyer_list' => $buyer_list,
                'model' => $model,

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
                        'approval_next' => $value,

            
                    ];

                   

                }


                $collection = Yii::$app->mongo->getCollection('project');

                $arrUpdate = [
                    '$set' => [
                        'sellers.$.approver_next' => 'normal',
                        'sellers.$.approval_next' =>  $tempApp,
                        'sellers.$.status' => 'Request Approval Next',
                        'buyers'=> [[
                            'buyer' => $buyer
                        ]],

                    ]
                
                ];


            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


             return $this->redirect(['request/index']);
            

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


        $form = ActiveForm::begin(['action' =>['request/approver'], 'id' => 'forum_post', 'method' => 'post',]);

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

        $buyer_info = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();



        foreach ($_POST['approval'] as $key => $value) {
            
            $tempApp[] = [
                'approval_next' => $value,
                'status_next' => ''
    
            ];

           

        }



        $collection = Yii::$app->mongo->getCollection('project');

        $arrUpdate = [
            '$set' => [
                'sellers.$.approver_next' => 'level',
                'sellers.$.approval_next' =>  $tempApp,
                'sellers.$.status' => 'Request Approval Next',
                'buyers'=> [[
                    'buyer' => $buyer_info->account_name
                ]],



            ]
        
        ];


        $collection->update(['_id' => $_POST['project'],'sellers.seller' => $_POST['seller']],$arrUpdate);


        $model = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '_id' => $_POST['project'],
                    'sellers.seller' => $_POST['seller'],
                ]
            ],

        ]); 


        $arrUpdateNext = [
            '$set' => [
                'sellers.$.approval_next.0.status_next' => 'Waiting Approval',
                'sellers.$.approver_level_next' => $model[0]['sellers']['approval_next'][0]['approval_next'],

            ]
        
        ];


        $collection->update(['_id' => $_POST['project'],'sellers.seller' => $_POST['seller']],$arrUpdateNext);





         return $this->redirect(['request/index']);



    }









}