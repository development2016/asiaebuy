<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_bank".
 *
 * @property integer $id
 * @property string $bank
 */
class LookupBank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bank'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank' => 'Bank',
        ];
    }
}
