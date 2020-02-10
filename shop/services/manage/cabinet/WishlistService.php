<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.03.19
 * Time: 17:25
 */

namespace shop\services\manage\cabinet;


use shop\repositories\shop\ProductRepository;
use shop\repositories\UserRepository;

class WishlistService
{


    private $users;
    private $products;

    public function __construct(UserRepository $users, ProductRepository $products)
    {
        $this->users = $users;
        $this->products = $products;


    }

    public function add($userId, $productId)
    {

        $user = $this->users->get($userId);
        $product = $this->products->get($productId);

        $user->addToWishList($user->id, $product->id)->save();


    }

    public function remove($userId, $productId)
    {
        $user = $this->users->get($userId);
        $product = $this->products->get($productId);

        $user->removeFromWishList($product->id);


    }



}