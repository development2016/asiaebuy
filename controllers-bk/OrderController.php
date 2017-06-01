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





}