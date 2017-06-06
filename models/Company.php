<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "company".
 *
 * @property \MongoDB\BSON\ObjectID|string $_id
 * @property mixed $asia_ebuy_no
 * @property mixed $company_name
 * @property mixed $address
 * @property mixed $country
 * @property mixed $state
 * @property mixed $city
 * @property mixed $zip_code
 * @property mixed $type_of_business
 * @property mixed $bank_account_name
 * @property mixed $bank_account_no
 * @property mixed $bank
 * @property mixed $tax_no
 * @property mixed $company_registeration_no
 * @property mixed $keyword
 * @property mixed $date_create
 * @property mixed $date_update
 * @property mixed $enter_by
 * @property mixed $update_by
 * @property mixed $type
 * @property mixed $email
 * @property mixed $website
 * @property mixed $admin
 * @property mixed $warehouses
 * @property mixed $telephone_no
 * @property mixed $fax_no
 * @property mixed $logo
 * @property mixed $term
 */
class Company extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['asiaebuy', 'company'];
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
            'asia_ebuy_no',
            'company_name',
            'address',
            'country',
            'state',
            'city',
            'zip_code',
            'type_of_business',
            'bank_account_name',
            'bank_account_no',
            'bank',
            'tax_no',
            'company_registeration_no',
            'keyword',
            'date_create',
            'date_update',
            'enter_by',
            'update_by',
            'type',
            'email',
            'website',
            'admin',
            'warehouses',
            'telephone_no',
            'fax_no',
            'logo',
            'term',
            'status'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asia_ebuy_no', 'company_name', 'address', 'country', 'state', 'city', 'zip_code', 'type_of_business', 'bank_account_name', 'bank_account_no', 'bank', 'tax_no', 'company_registeration_no', 'keyword', 'date_create', 'date_update', 'enter_by', 'update_by', 'type', 'email', 'website', 'admin', 'warehouses', 'telephone_no', 'fax_no', 'logo', 'term','status'], 'safe'],

            ['company_name', 'required', 'message' => 'Please Fill Company Name','on'=>'manage-company'],
            ['address', 'required', 'message' => 'Please Fill Company Address','on'=>'manage-company'],
            ['country', 'required', 'message' => 'Please Choose Country','on'=>'manage-company'],
            ['state', 'required', 'message' => 'Please Choose State','on'=>'manage-company'],
            ['city', 'required', 'message' => 'Please Fill City','on'=>'manage-company'],
            ['telephone_no', 'required', 'message' => 'Please Fill Telephone No','on'=>'manage-company'],
            //['term', 'required', 'message' => 'Please Choose Term','on'=>'manage-company'],
           // ['bank', 'required', 'message' => 'Please Choose Bank','on'=>'manage-company'],
           // ['bank_account_no', 'required', 'message' => 'Please Fill Bank Account No','on'=>'manage-company'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'asia_ebuy_no' => 'Asia Ebuy No',
            'company_name' => 'Company Name',
            'address' => 'Address',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'zip_code' => 'Zip Code',
            'type_of_business' => 'Type Of Business',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_no' => 'Bank Account No',
            'bank' => 'Bank',
            'tax_no' => 'Tax No',
            'company_registeration_no' => 'Company Registeration No',
            'keyword' => 'Keyword',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'enter_by' => 'Enter By',
            'update_by' => 'Update By',
            'type' => 'Type',
            'email' => 'Email',
            'website' => 'Website',
            'admin' => 'Admin',
            'warehouses' => 'Warehouses',
            'telephone_no' => 'Telephone No',
            'fax_no' => 'Fax No',
            'logo' => 'Logo',
            'term' => 'Term',
            'status' => 'Status'
        ];
    }

    public static function compid()
    {

        $user_id = Yii::$app->user->identity->id;

        $data = UserCompany::find()->where(['user_id'=>$user_id])->one();
        
        return $data;

    }

    public function getCountrys()
    {
        return $this->hasOne(LookupCountry::className(), ['id' => 'country']);
    }
    public function getStates()
    {
        return $this->hasOne(LookupState::className(), ['id' => 'state']);
    }

/*

    

    public static function Company()
    {

        $user_id = Yii::$app->user->identity->id;

        $data = Company::find()->where(['admin'=>$user_id])->one();
        
        return $data;

    }

    public static function User()
    {

        $user_id = Yii::$app->user->identity->id;

        $company = Company::find()->where(['admin'=>$user_id])->one();

       $data = UserCompany::find()->where('company = :company and user_id != :user_id', ['company'=> $company->_id, 'user_id' => $user_id])->all();

    return $data;

    } 



*/



}
