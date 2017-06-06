<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generate_delivery_order_no".
 *
 * @property integer $id
 * @property string $delivery_order_no
 * @property string $company_id
 * @property string $date_create
 * @property string $date_update
 * @property integer $enter_by
 * @property integer $update_by
 */
class GenerateDeliveryOrderNo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'generate_delivery_order_no';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_create', 'date_update'], 'safe'],
            [['enter_by', 'update_by'], 'integer'],
            [['delivery_order_no', 'company_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_order_no' => 'Delivery Order No',
            'company_id' => 'Company ID',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
        ];
    }
}
