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
use app\models\UserCompany;
use app\models\Item;
use app\models\Message;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\AsiaebuyCompany;
use kartik\mpdf\Pdf;
use app\models\LookupTerm;
use app\models\GenerateDeliveryOrderNo;

class OrderConfirmController extends Controller
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
                                'sellers.status' => 'Agree'
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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'status' => '$sellers.status',
                            'status_of_accept' => '$sellers.status_of_accept',
                            'status_of_do' => '$sellers.status_of_do',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'items' => '$sellers.items',
                            
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

        ]);


	}

    public function actionOrderConfirm()
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
                                'sellers.status' => 'Agree'
                            ],
                    ],
                    '$and' => [
                            [
                                'sellers.seller' => $user->account_name
                            ]
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
                            'purchase_requisition_no' => '$sellers.purchase_requisition_no',
                            'purchase_order_no' => '$sellers.purchase_order_no',
                            'status' => '$sellers.status',
                            'status_of_accept' => '$sellers.status_of_accept',
                            'status_of_do' => '$sellers.status_of_do',
                            'approval' => '$sellers.approval',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'items' => '$sellers.items',
                            
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

        return $this->render('order-confirm',[
            'model' => $model,

        ]);


    }



    public function actionGuideDeliveryOrder($project,$seller,$buyer)
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
                            'delivery_order_no' => '$sellers.delivery_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_delivery_order' => '$sellers.date_delivery_order',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);


        foreach ($list as $key => $value) {
           
            $delivery_order = $value['sellers'][0]['delivery_order_no'];
            $company = $value['sellers'][0]['company'];

        }

        if (empty($delivery_order)) { // check Quotation no exist or not

            // this will check this company already generate quotation or not, 

            $checkDeliveryOrderNo = GenerateDeliveryOrderNo::find()->where(['company_id'=>$returnCompanySeller->company])->orderBy(['id' => SORT_DESC])->limit(1)->one();



            if (empty($checkDeliveryOrderNo->delivery_order_no)) {

                $runninNo = 1000;
                $deliveryOrderTemp = 'DO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 6);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $delivery_order = 'S'.$returnAsiaebuyNo.'-'.$deliveryOrderTemp;

                $generateDeliveryOrderNo = new GenerateDeliveryOrderNo();
                $generateDeliveryOrderNo->delivery_order_no = $deliveryOrderTemp;
                $generateDeliveryOrderNo->company_id = $returnCompanySeller->company;
                $generateDeliveryOrderNo->date_create = date('Y-m-d H:i:s');
                $generateDeliveryOrderNo->enter_by = Yii::$app->user->identity->id;
                $generateDeliveryOrderNo->save();

            } else {


                $do = substr($checkDeliveryOrderNo->delivery_order_no, 2);
                $new = $do + 1;
                $runninNo = $new;

                $deliveryOrderTemp = 'DO'.$runninNo;

                $returnAsiaebuyNo = substr($company, 6);
                list($returnAsiaebuyNo) = explode('@', $returnAsiaebuyNo);
                $returnAsiaebuyNo;

                $delivery_order = 'S'.$returnAsiaebuyNo.'-'.$deliveryOrderTemp;

                $generateDeliveryOrderNo = new GenerateDeliveryOrderNo();
                $generateDeliveryOrderNo->delivery_order_no = $deliveryOrderTemp;
                $generateDeliveryOrderNo->company_id = $returnCompanySeller->company;
                $generateDeliveryOrderNo->date_create = date('Y-m-d H:i:s');
                $generateDeliveryOrderNo->enter_by = Yii::$app->user->identity->id;
                $generateDeliveryOrderNo->save();


            }


        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.delivery_order_no' => $delivery_order,
                'sellers.$.date_delivery_order' => date('Y-m-d H:i:s'),

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
                            'delivery_order_no' => '$sellers.delivery_order_no',
                            'seller' => '$sellers.seller',
                            'shipping' => '$sellers.shipping',
                            'shipping_price' => '$sellers.shipping_price',
                            'install' => '$sellers.install',
                            'installation_price' => '$sellers.installation_price',
                            'company' => '$sellers.company',
                            'quantity' => '$sellers.quantity',
                            'date_delivery_order' => '$sellers.date_delivery_order',
                            'items' => '$sellers.items',
                            'warehouses' => '$sellers.warehouses'
                        ],
                        
                    ],



                ]
            ]   

        ]);



        $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('guide-delivery-order',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'seller' => $seller,
            'project' => $project
        ]);








    }



}