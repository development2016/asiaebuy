<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\ProjectSearch;
use app\models\LookupTitle;
use app\models\Item;
use app\models\Company;
use kartik\mpdf\Pdf;
use app\models\LookupTerm;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\AsiaebuyCompany;

class GenerateController extends Controller
{

	public function actionGenerateGuideQuotation($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $return_asiaebuy = AsiaebuyCompany::find()->one();

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'tax_value' => $return_asiaebuy->gst_cost,
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Quoted',
                'sellers.$.type_of_buying' => 'Guide Buying',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['quote/index']);


    }

    public function actionGenerateGuidePurchaseRequisition($project,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        if ($approver == 'level') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' => date('Y-m-d h:i:s'),
                        'update_by' => Yii::$app->user->identity->id,
                        'sellers.$.status' => 'Request Approval',
                        'sellers.$.approval.0.status' => 'Waiting Approval',
                        'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],
                        'sellers.$.history' => [
                            [
                                'quotation_no' => $quotation_no = $model[0]['sellers']['quotation_no'],
                            ]
                            
                        ]

                    ]
                
                ];
                $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);




        } else {

                         $connection = \Yii::$app->db;
                         $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                          RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                          RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                          RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                          WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
                        $getRole = $sql->queryAll(); 

                        if ($getRole[0]['role'] == 'User') {


                            $collection = Yii::$app->mongo->getCollection('project');
                            $arrUpdate = [
                                '$set' => [
                                    'date_update' => date('Y-m-d h:i:s'),
                                    'update_by' => Yii::$app->user->identity->id,
                                    'sellers.$.requester' => '',
                                    'sellers.$.approve_by' => '',
                                    'sellers.$.status' => 'Request Approval',
                                    'sellers.$.history' => [
                                        [
                                            'quotation_no' => $quotation_no = $model[0]['sellers']['quotation_no'],
                                        ]
                                        
                                    ]

                                ]
                            
                            ];
                            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

                         
                        } else {


                            $collection = Yii::$app->mongo->getCollection('project');
                            $arrUpdate = [
                                '$set' => [
                                    'date_update' => date('Y-m-d h:i:s'),
                                    'update_by' => Yii::$app->user->identity->id,
                                    'sellers.$.status' => 'Request Approval',
                                    'sellers.$.approve_by' => '',
                                    'sellers.$.history' => [
                                        [
                                            'quotation_no' => $quotation_no = $model[0]['sellers']['quotation_no'],
                                        ]
                                        
                                    ]

                                ]
                            
                            ];
                            $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


                        }



        }




        return $this->redirect(['request/index']);


    }


    public function actionGenerateGuidePurchaseOrder($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Waiting Purchase Order Confirmation',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['order/index']);


    }






    // START FOR SALE LEAD GENERATE QUOTATION / PURCHASE REQUISITION / PURCHASE ORDER / DELIVERY ORDER / INVOICE

    public function actionGenerateSaleQuotation($project,$seller)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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

        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Quoted',
                'sellers.$.history' => [
                    [
                        'quotation_no' => $quotation_no = $model[0]['sellers']['quotation_no'],
                    ]
                    
                ]
                
            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate); 

        return $this->redirect(['quote/index']);

    }



    public function actionGenerateSalePurchaseRequisition($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Request Approval',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['request/index']);


    }

    public function actionGenerateSalePurchaseOrder($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Waiting Purchase Order Confirmation',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['order/index']);


    }





 // START FOR SPOT GENERATE QUOTATION / PURCHASE REQUISITION / PURCHASE ORDER / DELIVERY ORDER / INVOICE


    public function actionGenerateSpotPurchaseRequisition($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Request Approval',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['request/index']);


    }

    public function actionGenerateSpotPurchaseOrder($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Waiting Purchase Order Confirmation',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['order/index']);


    }




 // START FOR DIRECT PURCHASE GENERATE QUOTATION / PURCHASE REQUISITION / PURCHASE ORDER / DELIVERY ORDER / INVOICE


    public function actionGenerateDirectPurchaseRequisition($project,$seller,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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

        if ($approver == 'level') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' => date('Y-m-d h:i:s'),
                        'update_by' => Yii::$app->user->identity->id,
                        'sellers.$.status' => 'Request Approval',
                        'sellers.$.approval.0.status' => 'Waiting Approval',
                        'sellers.$.approver_level' => $model[0]['sellers']['approval'][0]['approval'],


                    ]
                
                ];
                $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        } else {

             $connection = \Yii::$app->db;
             $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
              RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
              RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
              RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
              WHERE acl.user_id = "'.(int)Yii::$app->user->identity->id.'" GROUP BY lookup_role.role');
            $getRole = $sql->queryAll(); 


            if ($getRole[0]['role'] == 'User') {


                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' => date('Y-m-d h:i:s'),
                        'update_by' => Yii::$app->user->identity->id,
                        'sellers.$.requester' => '',
                        'sellers.$.approve_by' => '',
                        'sellers.$.status' => 'Request Approval',


                    ]
                
                ];
                $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

             
            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' => date('Y-m-d h:i:s'),
                        'update_by' => Yii::$app->user->identity->id,
                        'sellers.$.status' => 'Request Approval',
                        'sellers.$.approve_by' => '',


                    ]
                
                ];
                $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);


            }





        }

        return $this->redirect(['request/index']);

    }


    public function actionGenerateDirectPurchaseOrder($project,$seller)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

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


        $collection = Yii::$app->mongo->getCollection('project');
        $arrUpdate = [
            '$set' => [
                'date_update' => date('Y-m-d h:i:s'),
                'update_by' => Yii::$app->user->identity->id,
                'sellers.$.status' => 'Complete',

            ]
        
        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        return $this->redirect(['order/index']);


    }





}