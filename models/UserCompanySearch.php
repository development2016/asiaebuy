<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company;

/**
 * UserCompanySearch represents the model behind the search form about `app\models\Company`.
 */
class UserCompanySearch extends Company
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'asia_ebuy_no', 'company_name', 'address', 'country', 'state', 'city', 'zip_code', 'type_of_business', 'bank_account_name', 'bank_account_no', 'bank', 'tax_no', 'company_registeration_no', 'keyword', 'date_create', 'date_update', 'enter_by', 'update_by', 'type', 'email', 'website', 'admin', 'warehouses', 'telephone_no', 'fax_no', 'logo', 'term'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Company::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'asia_ebuy_no', $this->asia_ebuy_no])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'type_of_business', $this->type_of_business])
            ->andFilterWhere(['like', 'bank_account_name', $this->bank_account_name])
            ->andFilterWhere(['like', 'bank_account_no', $this->bank_account_no])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'tax_no', $this->tax_no])
            ->andFilterWhere(['like', 'company_registeration_no', $this->company_registeration_no])
            ->andFilterWhere(['like', 'keyword', $this->keyword])
            ->andFilterWhere(['like', 'date_create', $this->date_create])
            ->andFilterWhere(['like', 'date_update', $this->date_update])
            ->andFilterWhere(['like', 'enter_by', $this->enter_by])
            ->andFilterWhere(['like', 'update_by', $this->update_by])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'admin', $this->admin])
            ->andFilterWhere(['like', 'warehouses', $this->warehouses])
            ->andFilterWhere(['like', 'telephone_no', $this->telephone_no])
            ->andFilterWhere(['like', 'fax_no', $this->fax_no])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'term', $this->term]);

        return $dataProvider;
    }
}
