<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_country".
 *
 * @property integer $id
 * @property string $country
 * @property string $code
 */
class LookupCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'code' => 'Code',
        ];
    }
}
