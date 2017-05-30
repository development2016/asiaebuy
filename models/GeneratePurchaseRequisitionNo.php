<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "generate_purchase_requisition_no".
 *
 * @property integer $id
 * @property string $purchase_requisition_no
 * @property string $company_id
 * @property string $date_create
 * @property string $date_update
 * @property integer $enter_by
 * @property integer $update_by
 */
class GeneratePurchaseRequisitionNo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'generate_purchase_requisition_no';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_create', 'date_update'], 'safe'],
            [['enter_by', 'update_by'], 'integer'],
            [['purchase_requisition_no', 'company_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_requisition_no' => 'Purchase Requisition No',
            'company_id' => 'Company ID',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
        ];
    }
}
