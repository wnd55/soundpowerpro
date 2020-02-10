<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 14:16
 */
namespace shop\forms\manage\shop\product;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\Characteristic;
use shop\entities\shop\product\Product;
use shop\forms\CompositeForm;
use yii\helpers\ArrayHelper;


/**
 * @property PriceForm $price
 * @property TagsForm $tags
 * @property CategoryAssignmentForm $categories
 * @property AudioFilesForm $photos
 */

class ProductEditForm extends CompositeForm
{

    public $brandId;
    public $code;
    public $name;
    public $description;
    public $weight;
    public $recommended;
    public $popular;
    public $meta_title;
    public $meta_description;
    public $meta_keywords;

    private $_product;

    /**
     * ProductEditForm constructor.
     * @param Product $product
     * @param array $config
     */
    public function __construct(Product $product, $config = [])
    {
        $this->brandId = $product->brand_id;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->weight = $product->weight;
        $this->recommended = $product->recommended;
        $this->popular = $product->popular;
        $this->meta_title = $product->meta_title;
        $this->meta_description = $product->meta_description;
        $this->meta_keywords = $product->meta_keywords;
        $this->categories = new CategoryAssignmentForm($product);
        $this->tags = new TagsForm($product);


        $this->_product = $product;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['brandId', 'code', 'name', 'meta_title', 'meta_description', 'meta_keywords'], 'required'],
            [['brandId', 'weight', 'recommended', 'popular'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique', 'targetClass' => Product::class, 'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
            ['description', 'string'],
            [['weight',],'integer', 'min' => 0],
        ];
    }


    public function brandList()
    {

        return ArrayHelper::map(Brand::find()->orderBy('name')->asArray()->all(), 'id', 'name');

    }

    public function categoryList()
    {

        return ArrayHelper::map(Category::find()->orderBy('name')->asArray()->all(), 'id', 'name');


    }


    protected function internalForms()
    {
        return ['tags', 'categories'];
    }

}