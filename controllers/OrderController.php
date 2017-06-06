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


class OrderController extends Controller
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
                                'sellers.status' => 'Waiting Purchase Order Confirmation'
                            ],
                            [
                                'sellers.status' => 'Complete'
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



    public function actionOrder()
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
                                'sellers.status' => 'Waiting Purchase Order Confirmation'
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

        return $this->render('order',[
            'model' => $model,

        ]);


    }

    public function actionGuideConfirm($project,$seller,$buyer)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        if ($model->load(Yii::$app->request->post()) ) {

            if ($_POST['Project']['sellers'][0]['status_confirm'] == 'Confirm All') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' => Yii::$app->user->identity->id,
                        'sellers.$.status' => 'Agree',
                        'sellers.$.status_of_accept' => $_POST['Project']['sellers'][0]['status_confirm'],
                        'sellers.$.status_of_do' => 'Waiting DO',
                        'sellers.$.estimate_shipping_date' => $_POST['Project']['sellers'][0]['estimate_shipping_date'],
                        'sellers.$.estimate_arrival_date' => $_POST['Project']['sellers'][0]['estimate_arrival_date'],
                    ]
                
                ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate); 


            } elseif ($_POST['Project']['sellers'][0]['status_confirm'] == 'Confirm With Status') {



            } elseif ($_POST['Project']['sellers'][0]['status_confirm'] == 'Reject') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' => $id_user,
                        'sellers.$.status' => $_POST['Project']['sellers'][0]['status_confirm'],
                        'sellers.$.remark' => $_POST['Project']['sellers'][0]['remark'],
                    ]
                
                ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate); 




            } else {

            }

            return $this->redirect(['order-confirm/index']);




        } else {
            return $this->renderAjax('guide-confirm', [
                'model' => $model,
            ]);
        }




    }



}