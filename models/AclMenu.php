<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acl_menu".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property integer $role_id
 */
class AclMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acl_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id', 'role_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'role_id' => 'Role ID',
        ];
    }
}
