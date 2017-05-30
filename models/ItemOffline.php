<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "item-offline".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $item_name
 * @property mixed $brand
 * @property mixed $model
 * @property mixed $description
 * @property mixed $specification
 * @property mixed $lead_time
 * @property mixed $validity
 * @property mixed $cost
 * @property mixed $quantity
 * @property mixed $cit
 * @property mixed $shipping
 * @property mixed $date_create
 * @property mixed $enter_by
 * @property mixed $update_by
 * @property mixed $date_update
 */
class ItemOffline extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['asiaebuy', 'item-offline'];
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
            'item_name',
            'brand',
            'model',
            'description',
            'specification',
            'lead_time',
            'validity',
            'cost',
            'quantity',
            'cit',
            'shipping',
            'date_create',
            'enter_by',
            'update_by',
            'date_update',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'brand', 'model', 'description', 'specification', 'lead_time', 'validity', 'cost', 'quantity', 'cit', 'shipping', 'date_create', 'enter_by', 'update_by', 'date_update'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'item_name' => 'Item Name',
            'brand' => 'Brand',
            'model' => 'Model',
            'description' => 'Description',
            'specification' => 'Specification',
            'lead_time' => 'Lead Time',
            'validity' => 'Validity',
            'cost' => 'Cost',
            'quantity' => 'Quantity',
            'cit' => 'Cit',
            'shipping' => 'Shipping',
            'date_create' => 'Date Create',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_update' => 'Date Update',
        ];
    }
}
