<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_category".
 *
 * @property integer $id
 * @property string $category
 * @property integer $group_id
 * @property integer $enter_by
 * @property integer $update_by
 * @property string $date_cretae
 * @property string $date_update
 */
class LookupCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'enter_by', 'update_by'], 'integer'],
            [['date_cretae', 'date_update'], 'safe'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'group_id' => 'Group ID',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_cretae' => 'Date Cretae',
            'date_update' => 'Date Update',
        ];
    }

    public function getGroups()
    {
        return $this->hasOne(LookupGroup::className(), ['id' => 'group_id']);
    }
    
}
