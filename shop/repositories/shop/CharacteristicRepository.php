<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.03.18
 * Time: 17:39
 */

namespace shop\repositories\shop;


use shop\entities\shop\Characteristic;

class CharacteristicRepository
{

    public function get($id)
    {
        if (!$characteristic = Characteristic::findOne($id)) {

            throw new \DomainException('Characteristic is not found.');
        }
        return $characteristic;
    }

    public function save(Characteristic $characteristic)
    {
        if (!$characteristic->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    public function remove(Characteristic $characteristic)
    {
        if (!$characteristic->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }












}