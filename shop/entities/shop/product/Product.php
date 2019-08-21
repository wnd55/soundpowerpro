<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 12:46
 */

namespace shop\entities\shop\product;


use shop\entities\shop\product\queries\ProductQuery;
use shop\entities\user\WishlistItem;
use Yii;
use shop\entities\shop\Brand;
use shop\entities\shop\Category;
use shop\entities\shop\Tag;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;



/**
 * @property integer $id
 * @property integer $created_at
 * @property string $code
 * @property string $name
 * @property string $slug
 * @property string $short_description
 * @property string $description
 * @property integer $category_id
 * @property integer $brand_id
 * @property integer $price_old
 * @property integer $price_new
 * @property integer $rating
 * @property integer $main_photo_id
 * @property integer $status
 * @property integer $weight
 * @property integer $quantity
 * @property integer recommended
 * @property integer popular
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property Brand $brand
 * @property Category $category
 * @property CategoryAssignment[] $categoryAssignments
 * @property Category[] $categories
 * @property TagAssignment[] $tagAssignments
 * @property Tag[] $tags
 * @property AudioFile[] $photos
 * @property AudioFile $mainPhoto
 * @property AudioFile $mainAudio
 * @property AudioFile $file

 */
class Product extends ActiveRecord
{



    const STATUS_DRAFT = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%shop_products}}';
    }


    public function behaviors()
    {
        return [



            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => false

            ]

        ];
    }



    public static function create($brandId, $categoryId, $code, $name, $description, $weight, $quantity, $recommended, $popular, $meta_title, $meta_description, $meta_keywords)
    {
        $product = new static();

        $product->brand_id = $brandId;
        $product->category_id = $categoryId;
        $product->code = $code;
        $product->name = $name;
        $product->description = $description;
        $product->weight = $weight;
        $product->quantity = $quantity;
        $product->status = self::STATUS_DRAFT;
        $product->created_at = time();
        $product->recommended = $recommended;
        $product->popular = $popular;
        $product->meta_title = $meta_title;
        $product->meta_description = $meta_description;
        $product->meta_keywords = $meta_keywords;

        return $product;

    }

    public function setPrice($new, $old)
    {
        $this->price_new = $new;
        $this->price_old = $old;
    }

    public function edit($brandId, $categoryId, $code, $name, $description, $weight, $recommended, $popular, $meta_title, $meta_description, $meta_keywords)
    {

        $this->brand_id = $brandId;
        $this->category_id = $categoryId;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
        $this->weight = $weight;
        $this->recommended = $recommended;
        $this->popular = $popular;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
        $this->meta_keywords = $meta_keywords;


    }


    public function activate()
    {
        if ($this->isActive()) {
            throw new \DomainException('Product is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function draft()
    {
        if ($this->isDraft()) {
            throw new \DomainException('Product is already draft.');
        }
        $this->status = self::STATUS_DRAFT;
    }


    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }


    public function isDraft()
    {
        return $this->status == self::STATUS_DRAFT;
    }



    public function isAvailable()

    {

        return $this->quantity > 0;

    }

    /**
     * @param $modificationId
     * @param $quantity
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */


    public function checkout($modificationId, $quantity)
    {
        if ($modificationId) {
            $modifications = $this->modifications;
            foreach ($modifications as $i => $modification) {
                if ($modification->isIdEqualTo($modificationId)) {
                    $modification->changeOrderModificationQuantity($quantity);

                    return;
                }
            }
        }
        if ($quantity > $this->quantity) {
            throw new \DomainException('Only ' . $this->quantity . ' items are available.');
        }

        $this->changeOrderQuantity($quantity);


    }

    /**
     * @param $quantity
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */

    public function changeOrderQuantity($quantity)
    {


        if ($this->quantity == 0 && $quantity > 0) {

            $this->recordEvent(new ProductAppearedInStock($this));
        }

        $this->quantity -= $quantity;
        $this->update();

    }



    public function setQuantity($quantity)
    {
//        if ($this->quantity == 0 && $quantity > 0) {
//
//            $this->recordEvent(new ProductAppearedInStock($this));
//        }

        $this->quantity = $quantity;

    }



    public function changeQuantity($quantity)
    {
        if ($this->modifications) {
            throw new \DomainException('Change modifications quantity.');
        }
        $this->setQuantity($quantity);
    }






    public function canBeCheckout($modificationId, $quantity)
    {

        if ($modificationId) {

            return $quantity <= $this->getModification($modificationId)->quantity;
        }

        return $quantity <= $this->quantity;

    }


    ################



    //Unlink CategoryAssignment


    public function unlinkCategoryAssignment($id)
    {
        $categories = CategoryAssignment::find()->where(['product_id' => $id])->all();

        foreach ($categories as $category) {

            $category->delete();
        }

    }



    //Unlink TagAssignment

    public function unlinkTagAssignment($id)
    {

        $tags = TagAssignment::find()->where(['product_id' => $id])->all();

        foreach ($tags as $tag) {

            $tag->delete();

        }
    }


    public function getProductAudioFile($productId, $photoId)
    {
        $photo = AudioFile::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->one();

        return Yii::getAlias('@web/uploads/mp3/' . $photo->file);
    }

 #############################################################################################################

    public function getBrand()
    {
        return $this->hasOne(Brand::class, ['id' => 'brand_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCategoryAssignments()
    {

        return $this->hasMany(CategoryAssignment::class,['product_id' => 'id'] );
    }

    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->via('categoryAssignments');

    }

    public function getTagAssignments()
    {
        return $this->hasMany(TagAssignment::class, ['product_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    public function getPhotos()
    {
        return $this->hasMany(AudioFile::class, ['product_id' => 'id'])->orderBy('sort');
    }

    public function getMainPhoto()
    {
        return $this->hasOne(AudioFile::class, ['id' => 'main_photo_id']);

    }

    public function getMainAudio()
    {

        return $this->hasOne(AudioFile::class, ['id' => 'main_photo_id']);
    }

    public function getStems()
    {

        return $this->hasMany(Stems::class, ['product_id' => 'id'])->orderBy('sort');

    }

    public function getWishlistItems()
    {
        return $this->hasMany(WishlistItem::class, ['product_id' => 'id']);
    }

  ##########################################################################################################

    public static function find()
    {
        return new ProductQuery(static::class);
    }


    public function afterSaveAddMainPhoto($id)
    {

        $photo = AudioFile::find()->where(['product_id' => $id])->orderBy('sort')->one();

        $this->updateAttributes(['main_photo_id' => $photo->id]);

    }


    public function ChangeMainPhoto($photoId)
    {

        $this->updateAttributes(['main_photo_id' => $photoId]);


    }
}