<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 27.03.18
 * Time: 21:54
 */

namespace shop\repositories\shop;

use shop\entities\shop\product\Variant;

class VariantRepository
{

    public function get($id)
    {

        if (!$variant = Variant::findOne($id)) {

            throw new \DomainException('Tag is not found');
        }

        return $variant;
    }

    public function save(Variant $variant)
    {

        if (!$variant->save()) {

            throw new \RuntimeException('Saving error');
        }

    }


    public function remove(Variant $variant)
    {


        if (!$variant->delete()) {

            throw new \RuntimeException('Removing error');
        }

    }


}