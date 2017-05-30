<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "item".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $images
 * @property mixed $item_name
 * @property mixed $group
 * @property mixed $brand
 * @property mixed $category
 * @property mixed $sub_category
 * @property mixed $model
 * @property mixed $description
 * @property mixed $specification
 * @property mixed $lead_time
 * @property mixed $cost
 * @property mixed $stock
 * @property mixed $quantity
 * @property mixed $publish
 * @property mixed $enter_by
 * @property mixed $update_by
 * @property mixed $date_create
 * @property mixed $date_update
 * @property mixed $owner_item
 * @property mixed $reviews
 * @property mixed $discount
 * @property mixed $catalogs
 * @property mixed $others
 */
class Item extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['asiaebuy', 'item'];
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
            'group',
            'brand',
            'category',
            'sub_category',
            'model',
            'description',
            'specification',
            'lead_time',
            'cost',
            'stock',
            'quantity',
            'publish',
            'enter_by',
            'update_by',
            'date_create',
            'date_update',
            'owner_item',
            'reviews',
            'discount',
            'catalogs',
            'others',
            'shippings',
            'installations',
            'thumbs',
            'back',
            'original'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'group', 'brand', 'category', 'sub_category', 'model', 'description', 'specification', 'lead_time', 'cost', 'stock', 'quantity', 'publish', 'enter_by', 'update_by', 'date_create', 'date_update', 'owner_item', 'reviews', 'discount', 'catalogs', 'others', 'shippings', 'installations','thumbs','back','original'], 'safe']
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
            'group' => 'Group',
            'brand' => 'Brand',
            'category' => 'Category',
            'sub_category' => 'Sub Category',
            'model' => 'Model',
            'description' => 'Description',
            'specification' => 'Specification',
            'lead_time' => 'Lead Time',
            'cost' => 'Cost',
            'stock' => 'Stock',
            'quantity' => 'Quantity',
            'publish' => 'Publish',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'owner_item' => 'Owner Item',
            'reviews' => 'Reviews',
            'discount' => 'Discount',
            'catalogs' => 'Catalogs',
            'others' => 'Others',
            'shippings' => 'Shippings',
            'installations' => 'Installations',
            'thumbs' => 'Thumbs',
            'back' => 'Back',
            'original' => 'Original'
        ];
    }

    public function getGroups()
    {
        return $this->hasOne(LookupGroup::className(), ['id' => 'group']);
    }
    public function getBrands()
    {
        return $this->hasOne(LookupBrand::className(), ['id' => 'brand']);
    }
    public function getModels()
    {
        return $this->hasOne(LookupModel::className(), ['id' => 'model']);
    }
    public function getLeads()
    {
        return $this->hasOne(LookupLeadTime::className(), ['id' => 'lead_time']);
    }
    public function getCategorys()
    {
        return $this->hasOne(LookupCategory::className(), ['id' => 'category']);
    }
    public function getSubs()
    {
        return $this->hasOne(LookupSubCategory::className(), ['id' => 'sub_category']);
    }



}
