<?php

namespace shop\entities\shop\queries;

use paulzi\nestedsets\NestedSetsQueryTrait;
use yii\db\ActiveQuery;

/**
 * Class CategoryQuery
 * @package shop\entities\shop\queries
 */
class CategoryQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;
}