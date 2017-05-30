<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "asiaebuy_company".
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
 */
class AsiaebuyCompany extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['asiaebuy', 'asiaebuy_company'];
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
            'tax_no',
            'gst_cost'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name', 'company_registeration_no', 'address', 'zip_code', 'country', 'state', 'city', 'telephone_no', 'fax_no', 'email', 'website','tax_no','gst_cost'], 'safe']
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
            'tax_no' => 'Tax No',
            'gst_cost' => 'Gst Cost'
        ];
    }


    public function getCountrys()
    {
        return $this->hasOne(LookupCountry::className(), ['id' => 'country']);
    }
    public function getStates()
    {
        return $this->hasOne(LookupState::className(), ['id' => 'state']);
    }


}
