<?php

namespace app\models;

use Yii;

class ItemElastic extends \yii\elasticsearch\ActiveRecord
{


    public function attributes()
    {
        return [
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
            'mongo_id',
            'discount',
            'others'
        ];
    }





}
