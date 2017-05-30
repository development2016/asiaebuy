<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_sub_category".
 *
 * @property integer $id
 * @property string $sub_category
 * @property integer $category_id
 * @property integer $group_id
 * @property integer $enter_by
 * @property integer $update_by
 * @property string $date_create
 * @property string $date_update
 */
class LookupSubCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_sub_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'group_id', 'enter_by', 'update_by'], 'integer'],
            [['date_create', 'date_update'], 'safe'],
            [['sub_category'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sub_category' => 'Sub Category',
            'category_id' => 'Category ID',
            'group_id' => 'Group ID',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
        ];
    }


    public function getGroups()
    {
        return $this->hasOne(LookupGroup::className(), ['id' => 'group_id']);
    }
    public function getCategorys()
    {
        return $this->hasOne(LookupCategory::className(), ['id' => 'category_id']);
    }



}
