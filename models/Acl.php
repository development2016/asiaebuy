<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acl".
 *
 * @property integer $id
 * @property integer $acl_menu_id
 * @property integer $user_id
 * @property string $company_id
 */
class Acl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acl_menu_id', 'user_id'], 'integer'],
            [['company_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acl_menu_id' => 'Acl Menu ID',
            'user_id' => 'User ID',
            'company_id' => 'Company ID',
        ];
    }
}
