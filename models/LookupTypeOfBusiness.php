<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_type_of_business".
 *
 * @property integer $id
 * @property string $type_of_business
 */
class LookupTypeOfBusiness extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_type_of_business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_of_business'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_of_business' => 'Type Of Business',
        ];
    }
}
