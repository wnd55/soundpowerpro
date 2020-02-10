<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 31.03.18
 * Time: 19:22
 */

namespace frontend\controllers\shop;


use shop\entities\shop\product\Product;
use shop\forms\shop\AddToCartForm;
use shop\forms\shop\search\SearchForm;
use shop\readModels\shop\BrandReadRepository;
use shop\readModels\shop\CategoryReadRepository;
use shop\readModels\shop\ProductReadRepository;
use shop\readModels\shop\TagReadRepository;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class CatalogController extends Controller
{


    private $products;
    private $categories;
    private $brands;
    private $tags;

    public $sort;


    public function __construct(
        $id,
        $module,
        ProductReadRepository $products,
        CategoryReadRepository $categories,
        BrandReadRepository $brands,
        TagReadRepository $tags,

        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->products = $products;
        $this->categories = $categories;
        $this->brands = $brands;
        $this->tags = $tags;
        $this->sort = new Sort([

            'attributes' => [
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                ],
                'Трек' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                ],
                'Стоимость' => [
                    'asc' => ['price_new' => SORT_ASC, 'name' => SORT_ASC],
                    'desc' => ['price_new' => SORT_DESC, 'name' => SORT_ASC],
                ],
            ]

        ]);

    }


    public function behaviors()
    {
        return [

            [
                'class' => 'yii\filters\HttpCache',
                'sessionCacheLimiter' => 'public',
            ],

        ];

    }


    /**
     * @return mixed
     */
    public function actionIndex()
    {


        $query = Product::find()->andWhere(['status' => Product::STATUS_ACTIVE,])->with('mainPhoto')->orderBy($this->sort->orders);

        $dataProvider = new ActiveDataProvider([

            'query' => $query,
            'pagination' => [
                'pageSize' => 7,
            ],
        ]);
        $category = $this->categories->getRoot();

        return $this->render('index', [
            'category' => $category,
            'dataProvider' => $dataProvider,
            'sort' => $this->sort,
        ]);
    }


    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */


    public function actionCategory($slug)
    {


        if (!$category = $this->categories->findBySlug($slug)) {

            throw new NotFoundHttpException('The requested page does not exist.');

        }

        $dataProvider = $this->products->getAllByCategoryWithSort($category, $this->sort);

        return $this->render('category', [
            'category' => $category,
            'dataProvider' => $dataProvider,
            'sort' => $this->sort,
        ]);

    }


    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */

    public function actionProduct($slug)
    {

        if (!$product = $this->products->findBySlug($slug)) {


            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $this->layout = 'blank';

        $cartForm = new AddToCartForm($product);
        //$reviewForm = new  ReviewForm();


        return $this->render('product', [

            'product' => $product,
            'cartForm' => $cartForm,

        ]);

    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionBrand($slug)
    {

        if (!$brand = $this->brands->findBySlug($slug)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByBrandWithSort($brand, $this->sort);

        return $this->render('brand', [
            'brand' => $brand,
            'dataProvider' => $dataProvider,
            'sort' => $this->sort,
        ]);
    }


    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionTag($id)
    {
        if (!$tag = $this->tags->find($id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $dataProvider = $this->products->getAllByTagWithSort($tag, $this->sort);

        return $this->render('tag', [
            'tag' => $tag,
            'dataProvider' => $dataProvider,
            'sort' => $this->sort,
        ]);
    }

    /**
     * @return string
     */

    public function actionSearch()
    {

        $form = new SearchForm();
        $form->load(\Yii::$app->request->queryParams);
        $form->validate();


        $dataProvider = $this->products->search($form);


        return $this->render('search', [
            'searchForm' => $form,
            'dataProvider' => $dataProvider,

        ]);


    }

}