<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.19
 * Time: 16:45
 */

namespace shop\cart;

use Yii;

class Cart
{

    public $cartItems;


    public function addCart($product, $quantity)
    {

        $this->loadCartItems();

        $id = md5(serialize([$product->id, $product->code]));

        foreach ($this->cartItems as $i => $item) {

            if ($item['id'] == $id) {

                throw new \DomainException('Трек уже выбран.');
            }
        }

        $this->cartItems[] = [

            'id' => $id,
            'product' => $product,
            'quantity' => $quantity


        ];


        $this->saveCartItems();


    }


    public function addQuantity($id, $quantity)
    {
        $this->loadCartItems();

        foreach ($this->cartItems as $i => $item) {

            if($item['id'] == $id) {

                $this->cartItems[$i]['quantity'] = $quantity;

                $this->saveCartItems();
                return;
            }
        }


    }

    /**
     * @return int
     */
    public function totalCount()
    {

        $this->loadCartItems();

        $totalCount = 0;

        foreach ($this->cartItems as $i => $item) {

            $totalCount += (int)$this->cartItems[$i]['quantity'] * (int)$item['product']->price_new;
        }

        return $totalCount;

    }

    /**
     * @return int
     */
    public function getAmount()
    {
        $this->loadCartItems();

        return count($this->cartItems);
    }



    /**
     *
     */

    public function clear()
    {
        $this->items = [];

        $this->saveCartItems();
    }


    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->loadCartItems();

        foreach ($this->cartItems as $i => $current) {
            if($current['id'] == $id) {

                unset($this->cartItems[$i]);
                $this->saveCartItems();
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    /**
     *
     */

    public function saveCartItems()
    {

        Yii::$app->session->set('cart', $this->cartItems);

    }

    /**
     * @return mixed
     */

    public function loadCartItems()
    {
        if ($this->cartItems === null) {


            return $this->cartItems = \Yii::$app->session->get('cart', []);
        }

    }
}