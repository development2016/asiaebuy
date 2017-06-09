<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Project;
use app\models\LookupCountry;
use app\models\LookupState;
use app\models\User;
use app\models\UserCompany;
use app\models\Company;


class InformationController extends Controller
{

	// guide buying
    public function actionGuideShippingCreate($project,$seller,$path)
    {
    	$newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'shipping_price' => '$sellers.shipping_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 



        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['shipping_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['shipping_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'No',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }


            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }

            

        } else {
            return $this->renderAjax('guide-shipping-create', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }

    public function actionGuideShippingUpdate($project,$seller,$path)
    {
    	$newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'shipping_price' => '$sellers.shipping_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['shipping_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['shipping_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'No',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.shipping' => 'Yes',
                        'sellers.0.shipping_price' => $_POST['Project']['sellers'][0]['shipping_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }

            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }


        } else {
            return $this->renderAjax('guide-shipping-update', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }

    public function actionGuideInstallationCreate($project,$seller,$path)
    {
    	$newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer'],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'installation_price' => '$sellers.installation_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['installation_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['installation_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'No',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }

            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }

        } else {
            return $this->renderAjax('guide-installation-create', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }

    public function actionGuideInstallationUpdate($project,$seller,$path)
    {
    	$newProject_id = new \MongoDB\BSON\ObjectID($project);
        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyer' => ['$first' => '$buyer' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => [
                            'seller' => '$sellers.seller',
                            'installation_price' => '$sellers.installation_price'
                            
                        ],
                        
                    ],


                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


            if ($_POST['Project']['sellers'][0]['installation_price'] == '0') {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);


            } elseif (empty($_POST['Project']['sellers'][0]['installation_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'No',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);
                
            } else {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'sellers.0.install' => 'Yes',
                        'sellers.0.installation_price' => $_POST['Project']['sellers'][0]['installation_price'],
                    ]
                
                    ];
                $collection->update(['_id' => (string)$newProject_id,'sellers.seller' => $seller],$arrUpdate);

            }

            if ($path == 'lead') {

                return $this->redirect([
                	'lead/guide-quotation', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                ]);
            
            } else if ($path == 'revise') {

                return $this->redirect([
                	'quote/guide-revise', 
                	'project' => (string)$newProject_id,
                	'seller'=>$seller,
                	'buyer' => $data[0]['buyer'],
                	]);
            
            }

        } else {
            return $this->renderAjax('guide-installation-update', [
                'model' => $model,
                'data' => $data
            ]);
        }


    }


    public function actionSaleItemUpdate($project,$seller,$path,$arrayItem,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.item_name' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['item_name'],
                        ]
                        
                    ]

                );


            if ($path == 'revise') {

                return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
   
            } else if ($path == 'lead') {

                return $this->redirect(['lead/sale-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'spot') {

                return $this->redirect(['lead/spot-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }
            

        } else {
            return $this->renderAjax('sale-item-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data
            ]);
        }


    }

    public function actionSaleDetailUpdate($project,$seller,$path,$arrayItem,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 


        if ($model->load(Yii::$app->request->post())) {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.brand' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['brand'],
                            'sellers.$.items.'.$_POST['arrayItem'].'.model' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['model'],
                            'sellers.$.items.'.$_POST['arrayItem'].'.specification' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['specification'],
                        ]
                        
                    ]

                );

            if ($path == 'revise') {

                return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
   
            } else if ($path == 'lead') {

                return $this->redirect(['lead/sale-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
                
            } else if ($path == 'spot') {

                return $this->redirect(['lead/spot-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }


        } else {
            return $this->renderAjax('sale-detail-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data
            ]);
        }


    }

    public function actionSaleInstallationUpdate($project,$seller,$path,$arrayItem,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 

       

        if ($model->load(Yii::$app->request->post())) {


            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['installation_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.install' => 'No',
                            'sellers.$.items.'.$_POST['arrayItem'].'.installation_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['installation_price'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.install' => 'Yes',
                                'sellers.$.items.'.$_POST['arrayItem'].'.installation_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['installation_price'],
                            ]
                        
                    ]

                );

            }


            if ($path == 'revise') {

                return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
   
            } else if ($path == 'lead') {

                return $this->redirect(['lead/sale-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }
            

        } else {
            return $this->renderAjax('sale-installation-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data,
            ]);
        }


    }

    public function actionSaleShippingUpdate($project,$seller,$path,$arrayItem,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        if ($model->load(Yii::$app->request->post())) {

            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['shipping_price'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.shipping' => 'No',
                            'sellers.$.items.'.$_POST['arrayItem'].'.shipping_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['shipping_price'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.shipping' => 'Yes',
                                'sellers.$.items.'.$_POST['arrayItem'].'.shipping_price' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['shipping_price'],
                            ]
                        
                    ]

                );

            }


            if ($path == 'revise') {

                return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
   
            } else if ($path == 'lead') {

                return $this->redirect(['lead/sale-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }


            

        } else {
            return $this->renderAjax('sale-shipping-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' =>$data,
            ]);
        }


    }




    public function actionSaleQuantityUpdate($project,$seller,$path,$arrayItem,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        if ($model->load(Yii::$app->request->post())) {

            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['quantity'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.quantity' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['quantity'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.quantity' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['quantity'],
                            ]
                        
                    ]

                );

            }

            if ($path == 'revise') {

                return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
   
            } else if ($path == 'lead') {

                return $this->redirect(['lead/sale-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
            
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }




        } else {
            return $this->renderAjax('sale-quantity-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data
            ]);
        }


    }


    public function actionSaleCostUpdate($project,$seller,$path,$arrayItem,$approver)
    {
        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();


        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        if ($model->load(Yii::$app->request->post())) {

            if (empty($_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['cost'])) {

                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                        '$set' => [
                            'sellers.$.items.'.$_POST['arrayItem'].'.cost' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['cost'],
                        ]
                        
                    ]

                );

            } else {


                $collection = Yii::$app->mongo->getCollection('project');
                $collection->update(
                    ['_id' => $newProject_id,'sellers.seller' => $seller],
                    [
                        
                            '$set' => [
                                'sellers.$.items.'.$_POST['arrayItem'].'.cost' => $_POST['Project']['sellers'][0]['items'][$_POST['arrayItem']]['cost'],
                            ]
                        
                    ]

                );

            }


            if ($path == 'revise') {

                return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
   
            } else if ($path == 'lead') {

                return $this->redirect(['lead/sale-quotation', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);
            
            } else if ($path == 'direct') {

                return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
            }

            


        } else {
            return $this->renderAjax('sale-cost-update', [
                'model' => $model,
                'arrayItem' => $arrayItem,
                'data' => $data,
            ]);
        }


    }

    public function actionSaleRemove($seller,$project,$path,$arrayItem,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $collection = Yii::$app->mongo->getCollection('project');
        $data = $collection->aggregate([
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
                    //'title' => ['$first' => '$title' ],
                   // 'due_date' => ['$first' => '$due_date' ],
                    //'project_no' => ['$first' => '$project_no' ],
                    //'type_of_project' => ['$first' => '$type_of_project' ],
                    'buyers' => ['$first' => '$buyers' ],
                    //'description' => ['$first' => '$description' ],
                    'sellers' => [
                        '$push' => '$sellers'
                    ],
                ]
            ]   

        ]); 



        $arrUpdate = [
            '$pull' => [
                'sellers.$.items' => [
                    'item_id' => (int)$arrayItem

                ]

               /* [
                    'item_id' => (int)$item_id
                ] */

            ]

        ];
        $collection->update(['_id' => $newProject_id,'sellers.seller' => $seller],$arrUpdate);

        if ($path == 'revise') {

            return $this->redirect(['quote/sale-revise', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer']]);

        } else if ($path == 'lead') {


        } else if ($path == 'direct') {


            return $this->redirect(['source/direct-purchase-requisition', 'project' =>(string)$newProject_id,'seller'=>$seller,'buyer' => $data[0]['buyers'][0]['buyer'],'approver'=>$approver]);
        }


        
    }


    public function actionAddDelivery($project,$seller,$buyer,$path,$approver)
    {

        $newProject_id = new \MongoDB\BSON\ObjectID($project);

        $model = Project::find()->where(['_id'=>$newProject_id])->one();

        $buyer_info = User::find()->where(['account_name'=>$buyer])->one();

        $returnCompanyBuyer = UserCompany::find()->where(['user_id'=>$buyer_info->id])->one();

        $companyBuyer = Company::find()->where(['_id'=>$returnCompanyBuyer->company])->one();

        if ($model->load(Yii::$app->request->post())) {

                $collection = Yii::$app->mongo->getCollection('project');
                $arrUpdate = [
                    '$set' => [
                        'date_update' =>  date('Y-m-d h:i:s'),
                        'update_by' =>  Yii::$app->user->identity->id,
                        'sellers.$.warehouses' => [[
                            'person_in_charge' => $_POST['Project']['sellers']['warehouses']['person_in_charge'],
                            'contact' => $_POST['Project']['sellers']['warehouses']['contact'],
                            'country' => $_POST['Project']['sellers']['warehouses']['country'],
                            'state' => $_POST['Project']['sellers']['warehouses']['state'],
                            'location' => $_POST['Project']['sellers']['warehouses']['location'],
                            'warehouse_name' => $_POST['Project']['sellers']['warehouses']['warehouse_name'],
                            'address' => $_POST['Project']['sellers']['warehouses']['address'],
                            'latitude' => $_POST['Project']['sellers']['warehouses']['latitude'],
                            'longitude' => $_POST['Project']['sellers']['warehouses']['longitude'],


                        ]]

                    ]
                
                ];
                $collection->update(['_id' => (string)$project,'sellers.seller' => $seller],$arrUpdate);


             
            if ($path == 'spot') {

                return $this->redirect(['source/spot-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
           
            } elseif ($path == 'sale') {

                 return $this->redirect(['source/sale-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);

            } elseif ($path == 'guide') {

                 return $this->redirect(['source/guide-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
               
            } elseif ($path == 'direct') {

                 return $this->redirect(['source/direct-purchase-requisition','project'=>(string)$project,'seller'=>$seller,'buyer'=>$buyer,'approver'=>$approver]);
               
            }
            

        } else {

            return $this->renderAjax('add-delivery',[
                'companyBuyer' => $companyBuyer,
                'project' => $project,
                'seller' => $seller,
                'model' => $model,
                'buyer' => $buyer
            ]);
        }


    }


    public function actionEditDelivery($project,$seller,$buyer,$path,$approver)
    {

    }







}