<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\Company;
use app\models\AsiaebuyCompany;
use app\models\UserCompany;
use app\models\User;
use app\models\LookupState;

class HtmlController extends Controller
{
	public function actionGuideQuotationHtml($project,$seller,$buyer)
	{
        $this->layout = 'html';

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $stateBuyer = LookupState::find()->where(['id' => (int)$companyBuyer->state])->one();

        $stateSeller = LookupState::find()->where(['id' => (int)$companySeller->state])->one();


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
                            'type_of_buying' => '$sellers.type_of_buying',
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


        return $this->render('guide-quotation-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'seller' => $seller,
            'project' => $project,
            'stateSeller' => $stateSeller,
            'stateBuyer' => $stateBuyer,

        ]);

	}


    public function actionGuidePurchaseRequisitionHtml($project,$seller,$buyer)
    {
        $this->layout = 'html';

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $stateBuyer = LookupState::find()->where(['id' => (int)$companyBuyer->state])->one();

        $stateSeller = LookupState::find()->where(['id' => (int)$companySeller->state])->one();


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
                            'type_of_buying' => '$sellers.type_of_buying',
                            'quotation_no' => '$sellers.quotation_no',
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'date_purchase_requisition' => '$sellers.date_purchase_requisition',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_quotation' => '$sellers.date_quotation',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        return $this->render('guide-purchase-requisition-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'stateSeller' => $stateSeller,
            'stateBuyer' => $stateBuyer,

        ]);

    }

    public function actionGuidePurchaseOrderHtml($project,$seller,$buyer)
    {
        $this->layout = 'html';

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        $stateBuyer = LookupState::find()->where(['id' => (int)$companyBuyer->state])->one();

        $stateSeller = LookupState::find()->where(['id' => (int)$companySeller->state])->one();


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
                            'type_of_buying' => '$sellers.type_of_buying',
                            'quotation_no' => '$sellers.quotation_no',
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'date_purchase_order' => '$sellers.date_purchase_order',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_quotation' => '$sellers.date_quotation',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        return $this->render('guide-purchase-order-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'stateSeller' => $stateSeller,
            'stateBuyer' => $stateBuyer,

        ]);

    }




    // SALE LEAD



    public function actionSaleQuotationHtml($project,$seller,$buyer)
    {
        $this->layout = 'html';

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

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


        return $this->render('sale-quotation-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'seller' => $seller,
            'project' => $project
        ]);



    }

    public function actionSalePurchaseRequisitionHtml($project,$seller,$buyer)
    {
        $this->layout = 'html';

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

        return $this->render('sale-purchase-requisition-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project
        ]);

    }




    // SPOT BUYING


    public function actionSpotQuotationHtml($project,$seller,$buyer)
    {
        $this->layout = 'html';

        $return_asiaebuy = AsiaebuyCompany::find()->one();

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $seller_info = User::find()->where(['account_name'=>$seller])->one();

        $returnCompanySeller = UserCompany::find()->where(['user_id'=>$seller_info->id])->one();

        $companySeller = Company::find()->where(['_id'=>$returnCompanySeller->company])->one();

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


        return $this->render('spot-quotation-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'seller' => $seller,
            'project' => $project
        ]);



    }

    public function actionSpotPurchaseRequisitionHtml($project,$seller,$buyer)
    {
        $this->layout = 'html';
        
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

        return $this->render('spot-purchase-requisition-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project
        ]);

    }



    // DIRECT PURCHASE 

    public function actionDirectPurchaseRequisitionHtml($project,$buyer,$seller)
    {
        $this->layout = 'html';

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

        return $this->render('direct-purchase-requisition-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer
        ]);


    }


    public function actionDirectPurchaseOrderHtml($project,$buyer,$seller)
    {
        $this->layout = 'html';

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



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('direct-purchase-order-html',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companyBuyer' => $companyBuyer,
            'seller' => $seller,
            'project' => $project,
            'buyer'=> $buyer
        ]);


    }








}