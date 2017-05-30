<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_brand".
 *
 * @property integer $id
 * @property string $brand
 * @property string $logo
 * @property integer $enter_by
 * @property integer $update_by
 * @property string $date_create
 * @property string $date_update
 */
class LookupBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enter_by', 'update_by'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['brand', 'logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand' => 'Brand',
            'logo' => 'Logo',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }
}
