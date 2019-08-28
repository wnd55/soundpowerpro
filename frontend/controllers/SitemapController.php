<?php

namespace frontend\controllers;


use shop\entities\shop\Category as ShopCategory;
use shop\entities\shop\Product\Product;
use shop\readModels\shop\CategoryReadRepository as ShopCategoryReadRepository;
use shop\readModels\shop\ProductReadRepository;
use shop\services\sitemap\IndexItem;
use shop\services\sitemap\MapItem;
use shop\services\sitemap\Sitemap;
use yii\caching\Dependency;
use yii\caching\TagDependency;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class SitemapController extends Controller
{
    const ITEMS_PER_PAGE = 100;

    private $sitemap;
    private $shopCategories;
    private $products;

    public function __construct(
        $id,
        $module,
        Sitemap $sitemap,
        ShopCategoryReadRepository $shopCategories,
        ProductReadRepository $products,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->sitemap = $sitemap;
        $this->shopCategories = $shopCategories;
        $this->products = $products;
    }

    public function actionIndex()
    {
        return $this->renderSitemap('sitemap-index', function () {
            return $this->sitemap->generateIndex([
                new IndexItem(Url::to(['shop-categories'], true)),
                new IndexItem(Url::to(['shop-products-index'], true)),
            ]);
        });
    }


    public function actionShopCategories()
    {
        return $this->renderSitemap('sitemap-shop-categories', function () {
            return $this->sitemap->generateMap(array_map(function (ShopCategory $category) {
                return new MapItem(
                    Url::to(['/shop/catalog/category', 'slug' => $category->slug], true),
                    null,
                    MapItem::WEEKLY
                );
            }, $this->shopCategories->getAll()));
        }, new TagDependency(['tags' => ['categories']]));
    }

    public function actionShopProductsIndex()
    {
        return $this->renderSitemap('sitemap-shop-products-index', function () {
            return $this->sitemap->generateIndex(array_map(function ($start) {
                return new IndexItem(Url::to(['shop-products', 'start' => $start * self::ITEMS_PER_PAGE], true));
            }, range(0, (int)($this->products->count() / self::ITEMS_PER_PAGE))));
        }, new TagDependency(['tags' => ['products']]));
    }

    public function actionShopProducts($start = 0)
    {
        return $this->renderSitemap(['sitemap-shop-products', $start], function () use ($start) {
            return $this->sitemap->generateMap(array_map(function (Product $product) {
                return new MapItem(
                    Url::to(['/shop/catalog/product', 'category' =>$product->category->slug, 'slug' => $product->slug], true),
                    null,
                    MapItem::DAILY
                );
            }, $this->products->getAllByRange($start, self::ITEMS_PER_PAGE)));
        }, new TagDependency(['tags' => ['products']]));
    }

    private function renderSitemap($key, callable $callback, Dependency $dependency = null)
    {
        return \Yii::$app->response->sendContentAsFile(\Yii::$app->cache->getOrSet($key, $callback, null, $dependency), Url::canonical(), [
            'mimeType' => 'application/xml',
            'inline' => true
        ]);
    }
}