<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_group".
 *
 * @property integer $id
 * @property string $group
 * @property integer $enter_by
 * @property integer $update_by
 * @property string $date_create
 * @property string $date_update
 */
class LookupGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enter_by', 'update_by'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['group'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group' => 'Group',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }
}
