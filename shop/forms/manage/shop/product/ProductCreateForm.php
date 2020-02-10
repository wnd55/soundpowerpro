<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.03.18
 * Time: 22:12
 */

namespace shop\forms\manage\shop\product;


use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\product\Product;
use shop\forms\CompositeForm;
use yii\base\Model;
use yii\helpers\ArrayHelper;


/**
 * @property PriceForm $price
 * @property TagsForm $tags
 * @property CategoryAssignmentForm $categories
 * @property AudioFilesForm $photos
 */
class ProductCreateForm extends CompositeForm
{

    public $brandId;
    public $code;
    public $name;
    public $description;
    public $weight;
    public $quantity;
    public $recommended;
    public $popular;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    /**
     * ProductCreateForm constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->code = rand(10000, 1000000);
        $this->price = new PriceForm();
        $this->categories = new CategoryAssignmentForm();
        $this->tags = new TagsForm();
        $this->photos = new AudioFilesForm();


        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['brandId', 'name', 'meta_title', 'meta_description', 'meta_keywords'], 'required'],
            [['code', 'name', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 255],
            [['brandId','recommended', 'popular'], 'integer'],
            [['code'], 'unique', 'targetClass' => Product::class],
            ['description', 'string'],
            [['weight', 'quantity'], 'integer', 'min' => 0],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Категория ID',
            'brand_id' => 'Автор ID',
            'created_at' => 'Дата создания',
            'code' => 'Код',
            'name' => 'Название трэка',
            'description' => 'Описание',
            'price_old' => 'Старая цена',
            'price_new' => 'Новая цена',
            'weight' => 'Вес',
            'quantity' => 'Количество',
            'recommended' => 'Рукомендованный',
            'popular' => 'Популярный',
            'rating' => 'Рейтинг',
            'status' => 'Статус',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'main_photo_id' => 'Трэк ID',
        ];
    }






    /**
     * @return array
     */
    public function brandsList()
    {
        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');
    }

    /**
     * @return array
     */
    public function categoryList()
    {

        return ArrayHelper::map(Category::find()->orderBy('name')->asArray()->all(), 'id', 'name');


    }

    /**
     * @return array
     */
    protected function internalForms()
    {
        return ['price', 'tags', 'photos','categories'];
    }


}