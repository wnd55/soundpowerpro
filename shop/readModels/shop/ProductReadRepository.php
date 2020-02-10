<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 01.04.18
 * Time: 20:56
 */

namespace shop\readModels\shop;

use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\entities\shop\Tag;
use shop\forms\shop\search\SearchForm;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;


class ProductReadRepository
{


    /**
     * @return int|string
     */
    public function count()
    {
        return Product::find()->active()->count();
    }

    /**
     * @param $offset
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */

    public function getAllByRange($offset, $limit)
    {
        return Product::find()->alias('p')->active('p')->orderBy(['id' => SORT_ASC])->limit($limit)->offset($offset)->all();
    }


    /**
     * @return ActiveDataProvider
     */
    public function getAll()
    {

        $query = Product::find()->alias('p')->andWhere(['p.' . 'status' => Product::STATUS_ACTIVE,])->with('mainPhoto');

        return $this->getProvider($query);

    }

    /**
     * @param $id
     * @return array|null|Product|\yii\db\ActiveRecord
     */
    public function find($id)
    {
        return Product::find()->active()->andWhere(['id' => $id])->one();


    }


    /**
     * @param $slug
     * @return array|null|Product|\yii\db\ActiveRecord
     */

    public function findBySlug($slug)
    {
        return Product::find()->active()->andWhere(['slug' => $slug])->one();


    }


    /**
     * @param ActiveQuery $query
     * @return ActiveDataProvider
     */

    private function getProvider(ActiveQuery $query)
    {

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 13,
            ],
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],

                ],
            ],

        ]);


    }

    /**
     * @param Category $category
     * @return ActiveDataProvider
     */

    public function getAllByCategory(Category $category)
    {

        $query = Product::find()->alias('p')->andWhere(['p.' . 'status' => Product::STATUS_ACTIVE,])->with('mainPhoto', 'category');

        $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id');


        return $this->getProvider($query);

    }


    /**
     * @param Category $category
     * @param $sort
     * @return ActiveDataProvider
     */
    public function getAllByCategoryWithSort(Category $category, $sort)
    {

        $query = Product::find()->alias('p')->andWhere(['p.' . 'status' => Product::STATUS_ACTIVE,])->with('mainPhoto', 'category');

        $ids = ArrayHelper::merge([$category->id], $category->getDescendants()->select('id')->column());
        $query->joinWith(['categoryAssignments ca'], false);
        $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);
        $query->groupBy('p.id')->orderBy($sort->orders);


        $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'pagination' => [
                'pageSize' => 7,
            ],
        ]);

        return $dataProvider;

    }


    /**
     * @param $limit
     * @param $category
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFeatured($limit, $category)
    {

        return Product::find()->with('mainPhoto')->active()->joinWith('categoryAssignments ca')->andWhere(['ca.category_id' => $category])->orderBy(['id' => SORT_DESC])->limit($limit)->all();


    }

    /**
     * @param Brand $brand
     * @return ActiveDataProvider
     */
    public function getAllByBrand(Brand $brand)
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');

        $query->andWhere(['p.brand_id' => $brand->id]);

        return $this->getProvider($query);


    }

    /**
     * @param Brand $brand
     * @param $sort
     * @return ActiveDataProvider
     */
    public function getAllByBrandWithSort(Brand $brand, $sort)
    {

        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');

        $query->andWhere(['p.brand_id' => $brand->id])->orderBy($sort->orders);

        $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'pagination' => [
                'pageSize' => 7,
            ],
        ]);

        return $dataProvider;

    }


    public function getAllByTag(Tag $tag)
    {
        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id]);
        return $this->getProvider($query);

    }


    /**
     * @param Tag $tag
     * @param $sort
     * @return ActiveDataProvider
     */
    public function getAllByTagWithSort(Tag $tag, $sort)
    {

        $query = Product::find()->alias('p')->active('p')->with('mainPhoto');
        $query->joinWith(['tagAssignments ta'], false);
        $query->andWhere(['ta.tag_id' => $tag->id])->orderBy($sort->orders);

        $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'pagination' => [
                'pageSize' => 7,
            ],
        ]);

        return $dataProvider;

    }


    /**
     * @param SearchForm $form
     * @return ActiveDataProvider
     */

    public function search(SearchForm $form)
    {


        $query = Product::find()->alias('p')->active('p')->with('mainPhoto', 'category');


        if ($form->brand) {

            $query->andWhere(['p.brand_id' => $form->brand]);
        }


        if ($form->category) {

            if ($category = Category::findOne($form->category)) {

                $ids = ArrayHelper::merge([$form->category], $category->getChildren()->select('id')->column());
                $query->joinWith(['categoryAssignments ca'], false);
                $query->andWhere(['or', ['p.category_id' => $ids], ['ca.category_id' => $ids]]);

            } else {

                $query->andWhere(['p.id' => 0]);
            }
        }


        if (!empty($form->text)) {

            $query->andWhere(['or', ['like', 'code', $form->text], ['like', 'name', $form->text], ['like', 'description', $form->text]]);
        }

        $query->groupBy('p.id');


        return $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'price' => [
                        'asc' => ['p.price_new' => SORT_ASC],
                        'desc' => ['p.price_new' => SORT_DESC],
                    ],

                ],
            ],
            'pagination' => [
                'pageSizeLimit' => [15, 100],
            ]

        ]);


    }

    /**
     * @param $userId
     * @return ActiveDataProvider
     */

    public function getWishList($userId)
    {
        return new ActiveDataProvider([
            'query' => Product::find()
                ->alias('p')->active('p')
                ->joinWith('wishlistItems w', false, 'INNER JOIN')
                ->andWhere(['w.user_id' => $userId]),
            'sort' => false,
        ]);
    }


}