<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.03.18
 * Time: 9:43
 */

namespace shop\services\manage\shop;


use shop\entities\shop\product\CategoryAssignment;
use shop\entities\shop\product\Stems;
use shop\entities\shop\Tag;
use shop\forms\manage\shop\product\AudioFilesForm;
use shop\forms\manage\shop\product\StemsForm;
use Yii;
use shop\entities\shop\product\AudioFile;
use shop\entities\shop\product\Product;
use shop\entities\shop\product\TagAssignment;
use shop\forms\manage\shop\product\PriceForm;
use shop\forms\manage\shop\product\ProductCreateForm;
use shop\forms\manage\shop\product\ProductEditForm;
use shop\forms\manage\shop\product\QuantityForm;
use shop\repositories\shop\BrandRepository;
use shop\repositories\shop\CategoryRepository;
use shop\repositories\shop\ProductRepository;
use shop\repositories\shop\TagRepository;
use yii\base\Theme;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class ProductManageService
 * @package shop\services\manage\shop
 * @property Product $product
 * @property ProductCreateForm
 * @property ProductEditForm
 */
class ProductManageService
{

    public static $i = 1;

    private $products;
    private $brands;
    private $categories;
    private $tags;
    private $audioFile;
    private $stems;


    public function __construct(ProductRepository $products, BrandRepository $brands, CategoryRepository $categories, TagRepository $tags, AudioFile $audioFile, Stems $stems)
    {

        $this->products = $products;
        $this->brands = $brands;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->audioFile = $audioFile;
        $this->stems = $stems;


    }

    //Create product


    /**
     * @param ProductCreateForm $form
     * @return static
     */

    public function create(ProductCreateForm $form)
    {


        $brand = $this->brands->get($form->brandId);

        $category = $this->categories->get($form->categories->main_category);

        $product = Product::create(

            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            $form->quantity,
            $form->recommended,
            $form->popular,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords


        );

        $product->setPrice($form->price->new, $form->price->old);


        $this->products->save($product);

        //CategoryAssignment

        foreach ($form->categories->additional_categories as $additional_categoryId) {

            $category = $this->categories->get($additional_categoryId);

            CategoryAssignment::create($product->id, $category->id)->save();
        }


        //TagAssignment

        foreach ($form->tags->existing as $tagId) {

            $tag = $this->tags->get($tagId);

            TagAssignment::create($product->id, $tag->id)->save();
        }

        // Add new Tags

        foreach ($form->tags->newNames as $tagName) {

            if (!$tag = $this->tags->findByName($tagName)) {

                if (!empty($tagName)) {

                    Tag::create($tagName)->save();

                    $tag = $this->tags->findNewTag($tagName);

                    TagAssignment::create($product->id, $tag->id)->save();
                }

            }
        }

        // Photos


        $images = UploadedFile::getInstances($form->photos, 'file');

        foreach ($images as $image) {

            // Генерация random

            $randomName = $image->getBaseName() . Yii::$app->getSecurity()->generateRandomString(7);

            $image->saveAs('uploads/mp3/' . $randomName . '.' . $image->getExtension());

            $newPictureRandomName = $randomName . '.' . $image->getExtension();


            $photo = AudioFile::create($product->id, $newPictureRandomName, self::$i++);

            if (!$photo->save()) {

                throw new \RuntimeException('Ошибка сохранения');
            }

        }

        if (isset($photo)) {

            $product->afterSaveAddMainPhoto($product->id);
        }
        return $product;
    }

    /**
     * @param $id
     * @param AudioFilesForm $photosForm
     */
    public function addPhotos($id, AudioFilesForm $photosForm)
    {
        $product = $this->products->get($id);

        $sort = AudioFile::find()->where(['product_id' => $id])->orderBy('sort')->max('sort') + 1;

        $images = UploadedFile::getInstances($photosForm, 'file');


        foreach ($images as $image) {


            // Генерация random

            $randomName = $image->getBaseName() . Yii::$app->getSecurity()->generateRandomString(7);

            $image->saveAs('uploads/mp3/' . $randomName . '.' . $image->getExtension());

            $newPictureRandomName = $randomName . '.' . $image->getExtension();


            AudioFile::create($product->id, $newPictureRandomName, $sort++)->save();


        }

        if (!$product->mainPhoto) {

            $product->afterSaveAddMainPhoto($product->id);
        }


    }

    //Stems

    /**
     * @param $id
     * @param StemsForm $model
     */
    public function addStems($id, StemsForm $model)
    {

        $product = $this->products->get($id);

        if ($product->stems) {

            $sort = Stems::find()->where(['product_id' => $product->id])->orderBy('sort')->max('sort') + 1;
            $stems = UploadedFile::getInstances($model, 'file');

            foreach ($stems as $stem) {

                $randomName = $product->name . '_stem_' . Yii::$app->getSecurity()->generateRandomString(7);

                $stem->saveAs('uploads/mp3/stems/' . $randomName . '.' . $stem->getExtension());


                $stemName = $randomName . '.' . $stem->getExtension();

                $stem = Stems::create($product->id, $stemName, $sort++);

                if (!$stem->save()) {

                    throw new \RuntimeException('Ошибка сохранения');
                }


            }
        } else {

            $stems = UploadedFile::getInstances($model, 'file');

            foreach ($stems as $stem) {

                $randomName = $product->name . '_stem_' . Yii::$app->getSecurity()->generateRandomString(7);

                $stem->saveAs('uploads/mp3/stems/' . $randomName . '.' . $stem->getExtension());


                $stemName = $randomName . '.' . $stem->getExtension();

                $stem = Stems::create($product->id, $stemName, self::$i++);

                if (!$stem->save()) {

                    throw new \RuntimeException('Ошибка сохранения');
                }


            }

        }

    }
    //Remove stem

    /**
     * @param $stemId
     * @param $productId
     */
    public function removeStem($stemId, $productId)
    {

        $this->stems->removeStem($stemId, $productId);


    }

    /**
     * @param $stemId
     * @param $productId
     */
    public function moveStemUp($stemId, $productId)
    {

        $this->stems->moveStemUp($stemId, $productId);

    }

    /**
     * @param $stemId
     * @param $productId
     */
    public function moveStemDown($stemId, $productId)
    {

        $this->stems->moveStemDown($stemId, $productId);


    }
    //Edit product

    /**
     * @param $id
     * @param ProductEditForm $form
     * @var $product  \shop\entities\shop\product\Product
     */

    public function edit($id, ProductEditForm $form)
    {
        /**
         * @var $product  \shop\entities\shop\product\Product
         *
         */
        $product = $this->products->get($id);

        $product->unlinkCategoryAssignment($id);
        $product->unlinkTagAssignment($id);


        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main_category);

        $product->edit(

            $brand->id,
            $category->id,
            $form->code,
            $form->name,
            $form->description,
            $form->weight,
            $form->recommended,
            $form->popular,
            $form->meta_title,
            $form->meta_description,
            $form->meta_keywords


        );

        $this->products->save($product);


        //CategoryAssignment

        foreach ($form->categories->additional_categories as $additional_categoryId) {

            $category = $this->categories->get($additional_categoryId);

            CategoryAssignment::create($product->id, $category->id)->save();
        }


        //TagAssignment

        foreach ($form->tags->existing as $tagId) {

            $tag = $this->tags->get($tagId);

            TagAssignment::create($product->id, $tag->id)->save();
        }

        // Add new Tags

        foreach ($form->tags->newNames as $tagName) {

            if (!$tag = $this->tags->findByName($tagName)) {

                if (!empty($tagName)) {

                    Tag::create($tagName)->save();

                    $tag = $this->tags->findNewTag($tagName);

                    TagAssignment::create($product->id, $tag->id)->save();
                }
            }

        }

    }


    // Remove product


    public function remove($id)
    {

        $this->audioFile->unlinkPhotos($id);

        $product = $this->products->get($id);

        $this->products->remove($product);


    }


    public function activate($id)
    {
        $product = $this->products->get($id);
        $product->activate();
        $this->products->save($product);
    }


    public function draft($id)
    {
        $product = $this->products->get($id);
        $product->draft();
        $this->products->save($product);
    }




///////////////////////////////////////////////////////////////


    //Price

    public function changePrice($id, PriceForm $form)
    {
        $product = $this->products->get($id);
        $product->setPrice($form->new, $form->old);
        $this->products->save($product);
    }


    //Quantity

    public function changeQuantity($id, QuantityForm $form)
    {
        $product = $this->products->get($id);

        $product->setQuantity($form->quantity);

        $this->products->save($product);


    }

////////////////////////////////////////////////////////////////

//AudioFile




    public function removeAudio($productId, $photoId)
    {

        $this->audioFile->removeAudio($productId, $photoId);

    }
}