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
use kartik\mpdf\Pdf;
use app\models\Message;
use app\models\Company;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\AsiaebuyCompany;

class QuoteController extends Controller
{

    public function actionIndex()
    {

        $user_id = Yii::$app->user->identity->id;
        $user = User::find()->where(['id'=>$user_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $process = $collection->aggregate([
            [
                '$unwind' => '$sellers'
            ],
            [
                '$match' => [
                    '$or' => [
                        [
                            'sellers.status' => 'Quoted'
                        ],
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
                            'sellers.status' => 'Revise'
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
                    'quotation_file' => ['$first' => '$quotation_file' ],
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer' ],
                    'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'status' => '$sellers.status',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'quotation_no' => '$sellers.quotation_no',
     
                            
                        ],
                        
                    ],

                ]
            ]   

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
                                'sellers.seller' => $user->account_name
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
                    'description' => ['$first' => '$description' ],
                    'type_of_project' => ['$first' => '$type_of_project' ],
                    'quotation_file' => ['$first' => '$quotation_file' ],
                    'project_no' => ['$first' => '$project_no' ],
                    'sellers' => [
                        '$push' => [
                            'quotation_no' => '$sellers.quotation_no',
                            'seller' => '$sellers.seller',
                            'revise' => '$sellers.revise',
                            'history' => '$sellers.history',

                        ],
                        
                    ],


            
                ]
            ]

        ]);



        return $this->render('index', [
            'process' => $process,
            'history' => $history,

        ]);
    }

    public function actionGuideModalRevise($project,$seller,$buyer)
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
            ]

        ]);

       return $this->renderAjax('guide-modal-revise',[
            'model' => $model,
            'project'=> $project,
            'seller' => $seller,
            'buyer' => $buyer

        ]);
    }

    public function actionGuideRevise($project,$seller,$buyer)
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

                    '_id' => $newProject_id,
                    'sellers.seller' => $seller,

                ]
            ]

        ]);

       if (empty($check[0]['sellers']['revise'])) {

            $count = 1;

            $newQuotation_no = $check[0]['sellers']['quotation_no'].'-R'.$count;

            $collection->update(
                ['_id' => $newProject_id,'sellers.seller' => $seller],
                [
                    '$push' => [ // $push to add items in array 
                        'sellers.$.revise' => [
                         
                            'quotation_no' => $check[0]['sellers']['quotation_no'],
                            'quantity' => $check[0]['sellers']['quantity'],
                            'date_quotation' => $check[0]['sellers']['date_quotation'],
                            'shipping' => $check[0]['sellers']['shipping'],
                            'shipping_price' => $check[0]['sellers']['shipping_price'],
                            'install' => $check[0]['sellers']['install'],
                            'installation_price' => $check[0]['sellers']['installation_price'],
                            'cost' => $check[0]['sellers']['items'][0]['cost'],
                            'date_revise' => date('Y-m-d H:i:s')
                        
                        ]
                    ],
                    '$set' => [
                        'sellers.$.quotation_no' => $newQuotation_no,
                        'sellers.$.date_quotation' => date('Y-m-d H:i:s'),
                        'sellers.$.status' => 'Revise'
                    ]
                ]

            );

        } else {

            if ($check[0]['sellers']['status'] == 'Revise') {



            } else {

                $count = count($check[0]['sellers']['revise']);

                $newCount = ++$count;

                $qt = strtok($check[0]['sellers']['quotation_no'], '-');

                $newQuotation_no = $qt.'-R'.$newCount;
        
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        '$push' => [ // $push to add items in array 
                            'sellers.$.revise' => [
                             
                                'quotation_no' => $check[0]['sellers']['quotation_no'],
                                'quantity' => $check[0]['sellers']['quantity'],
                                'date_quotation' => $check[0]['sellers']['date_quotation'],
                                'shipping' => $check[0]['sellers']['shipping'],
                                'shipping_price' => $check[0]['sellers']['shipping_price'],
                                'install' => $check[0]['sellers']['install'],
                                'installation_price' => $check[0]['sellers']['installation_price'],
                                'cost' => $check[0]['sellers'][0]['items'][0]['cost'],
                                'date_revise' => date('Y-m-d H:i:s')

                            
                            ]
                        ],
                        '$set' => [
                            'sellers.$.quotation_no' => $newQuotation_no,
                            'sellers.$.date_quotation' => date('Y-m-d H:i:s'),
                            'sellers.$.status' => 'Revise'
                        ]
                    ]

                );



            }

        }

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

         $return_asiaebuy = AsiaebuyCompany::find()->one();

        return $this->render('revise-guide-quotation',[
            'return_asiaebuy' => $return_asiaebuy,
            'list' => $list,
            'companySeller' => $companySeller,
            'seller' => $seller,
            'project' => $project
        ]);

    }




    public function actionSaleModalRevise($project,$seller)
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
            ]

        ]);

       return $this->renderAjax('sale-modal-revise',[
            'model' => $model,
            'project'=> $project,
            'seller' => $seller

        ]);
    }






}