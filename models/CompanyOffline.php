<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "company-offline".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $company_name
 * @property mixed $company_registeration_no
 * @property mixed $address
 * @property mixed $zip_code
 * @property mixed $country
 * @property mixed $state
 * @property mixed $city
 * @property mixed $telephone_no
 * @property mixed $fax_no
 * @property mixed $email
 * @property mixed $website
 * @property mixed $gst
 */
class CompanyOffline extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['asiaebuy', 'company-offline'];
    }

    /**
     * @return \yii\mongodb\Connection the MongoDB connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('mongo');
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'company_name',
            'company_registeration_no',
            'address',
            'zip_code',
            'country',
            'state',
            'city',
            'telephone_no',
            'fax_no',
            'email',
            'website',
            'gst',
            'warehouses',
            'date_create',
            'enter_by',
            'date_update',
            'update_by',
            'term'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name', 'company_registeration_no', 'address', 'zip_code', 'country', 'state', 'city', 'telephone_no', 'fax_no', 'email', 'website', 'gst','date_create','date_update','enter_by','update_by','term'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'company_name' => 'Company Name',
            'company_registeration_no' => 'Company Registeration No',
            'address' => 'Address',
            'zip_code' => 'Zip Code',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'telephone_no' => 'Telephone No',
            'fax_no' => 'Fax No',
            'email' => 'Email',
            'website' => 'Website',
            'gst' => 'Gst',
        ];
    }
}
