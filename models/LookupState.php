<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_state".
 *
 * @property integer $id
 * @property string $state
 * @property integer $country_id
 */
class LookupState extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id'], 'integer'],
            [['state','locate'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => 'State',
            'country_id' => 'Country ID',
            'locate' => 'Locate'
        ];
    }
}
