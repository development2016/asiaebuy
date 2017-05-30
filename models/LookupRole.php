<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_role".
 *
 * @property integer $id
 * @property string $role
 * @property string $description
 */
class LookupRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['role'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Role',
            'description' => 'Description',
        ];
    }
}
