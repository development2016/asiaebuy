<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Item;

/**
 * ItemSearch represents the model behind the search form about `app\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'item_name', 'group', 'brand', 'category', 'sub_category', 'model', 'description', 'specification', 'lead_time', 'cost', 'stock', 'quantity', 'publish', 'enter_by', 'update_by', 'date_create', 'date_update', 'owner_item', 'reviews', 'discount', 'catalogs', 'others'], 'safe'],
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
        $query = Item::find();

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
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'sub_category', $this->sub_category])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'specification', $this->specification])
            ->andFilterWhere(['like', 'lead_time', $this->lead_time])
            ->andFilterWhere(['like', 'cost', $this->cost])
            ->andFilterWhere(['like', 'stock', $this->stock])
            ->andFilterWhere(['like', 'quantity', $this->quantity])
            ->andFilterWhere(['like', 'publish', $this->publish])
            ->andFilterWhere(['like', 'enter_by', $this->enter_by])
            ->andFilterWhere(['like', 'update_by', $this->update_by])
            ->andFilterWhere(['like', 'date_create', $this->date_create])
            ->andFilterWhere(['like', 'date_update', $this->date_update])
            ->andFilterWhere(['like', 'owner_item', $this->owner_item])
            ->andFilterWhere(['like', 'reviews', $this->reviews])
            ->andFilterWhere(['like', 'discount', $this->discount])
            ->andFilterWhere(['like', 'catalogs', $this->catalogs])
            ->andFilterWhere(['like', 'others', $this->others]);

        return $dataProvider;
    }
}
