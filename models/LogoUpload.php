<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logo_upload".
 *
 * @property integer $id
 * @property string $filename
 * @property string $path
 * @property string $company_id
 * @property integer $enter_by
 * @property string $date_create
 */
class LogoUpload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logo_upload';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enter_by'], 'integer'],
            [['date_create'], 'safe'],
            [['filename', 'path', 'company_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'path' => 'Path',
            'company_id' => 'Company ID',
            'enter_by' => 'Enter By',
            'date_create' => 'Date Create',
        ];
    }
}
