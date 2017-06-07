<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "project".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $description
 * @property mixed $sellers
 * @property mixed $due_date
 * @property mixed $title
 * @property mixed $project_no
 * @property mixed $type_of_project
 * @property mixed $date_create
 * @property mixed $buyer
 * @property mixed $enter_by
 * @property mixed $date_update
 */
class Project extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['asiaebuy', 'project'];
    }

    /**
     * @return \yii\mongodb\Connection the MongoDB connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('mongo');
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'description',
            'sellers',
            'due_date',
            'title',
            'project_no',
            'type_of_project',
            'date_create',
            'buyers',
            'enter_by',
            'date_update',
            'url_myspot',
            'requester',
            'tax_value',

        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'sellers', 'due_date', 'title', 'project_no', 'type_of_project', 'date_create', 'buyer', 'enter_by', 'date_update','url_myspot','tax_value','requester'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'description' => 'Description',
            'sellers' => 'Sellers',
            'due_date' => 'Due Date',
            'title' => 'Title',
            'project_no' => 'Project No',
            'type_of_project' => 'Type Of Project',
            'date_create' => 'Date Create',
            'buyer' => 'Buyer',
            'enter_by' => 'Enter By',
            'date_update' => 'Date Update',
            'url_myspot' => 'Url',
            'tax_value' => 'Tax Value',
            'requester' => 'Requester',

        ];
    }





}
