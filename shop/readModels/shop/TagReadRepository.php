<?php

namespace shop\readModels\shop;

use shop\entities\shop\Tag;

class TagReadRepository
{
    public function find($id)
    {
        return Tag::findOne($id);
    }
}