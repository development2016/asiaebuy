<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_model".
 *
 * @property integer $id
 * @property string $model
 * @property integer $brand_id
 * @property integer $enter_by
 * @property integer $update_by
 * @property string $date_create
 * @property string $date_update
 */
class LookupModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_model';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brand_id', 'enter_by', 'update_by'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'brand_id' => 'Brand ID',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }

    public function getBrands()
    {
        return $this->hasOne(LookupBrand::className(), ['id' => 'brand_id']);
    }
    
}
