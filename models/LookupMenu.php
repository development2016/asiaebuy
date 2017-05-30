<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup_menu".
 *
 * @property integer $id
 * @property string $menu
 * @property integer $as_a
 */
class LookupMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['as_a'], 'integer'],
            [['menu'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu' => 'Menu',
            'as_a' => 'As A',
        ];
    }
}
