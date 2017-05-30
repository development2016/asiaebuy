<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "password".
 *
 * @property integer $id
 * @property string $password
 * @property integer $id_user
 */
class Password extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'password';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user'], 'integer'],
            [['password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'id_user' => 'Id User',
        ];
    }
}
