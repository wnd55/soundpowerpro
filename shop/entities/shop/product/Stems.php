<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.08.19
 * Time: 23:02
 */

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $product_id
 * @property string $file
 * @property integer $status
 * @property integer $sort
 */
class Stems extends ActiveRecord
{


    const STEM_STATUS_DRAFT = 0;
    const STEM_STATUS_ACTIVE = 1;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_audio_stems}}';
    }


    public static function create($id, $stemName, $sort)
    {

        $stem = new static();

        $stem->product_id = $id;
        $stem->file = $stemName;
        $stem->status = self::STEM_STATUS_ACTIVE;
        $stem->sort = $sort;

        return $stem;


    }


    public function removeStem($stemId, $productId)
    {
        $stem = Stems::findOne($stemId);

        unlink(\Yii::getAlias('@webroot/uploads/mp3/stems/' . $stem->file));

        if ($stem->delete() !== false) {

            $stems = Stems::find()->where(['product_id' => $productId])->orderBy('sort')->all();

            $this->updateStems($stems);

        }

    }

    /**
     * @param $stemId
     * @param $productId
     */
    public function moveStemUp($stemId, $productId)
    {
        $stems = Stems::find()->where(['product_id' => $productId])->orderBy('sort')->all();

        foreach ($stems as $i => $stem   ){

            if($stem->isIdEqualTo($stemId)) {

                if($prev = isset($stems[$i -1]) ? $stems[$i -1] : null){


                    $stems[$i -1] = $stem;
                    $stems[$i] = $prev;

                    $this->updateStems($stems);
                }
                return;
            }

        }

        throw new \DomainException('Stems не найден');

    }

    /**
     * @param $stemId
     * @param $productId
     */
    public function moveStemDown($stemId, $productId)
    {

        $stems = Stems::find()->where(['product_id' => $productId])->orderBy('sort')->all();

        foreach ($stems as $i => $stem   ) {

            if($stem->isIdEqualTo($stemId)) {

                if($next = isset($stems[$i + 1]) ? $stems[$i + 1] : null){


                    $stems[$i + 1] = $stem;
                    $stems[$i] = $next;

                    $this->updateStems($stems);
                }
                return;

            }


        }

        throw new \DomainException('Stems не найден');

    }
    /**
     * @param $stems
     */
    public function updateStems($stems)
    {
        foreach ($stems as $i => $stem) {

            $stem->setSort($i);
        }


    }

    /**
     * @param $i
     */
    public function setSort($i)
    {

        $this->sort = $i +1;
        $this->save();


    }

    /**
     * @param $stemId
     * @return bool
     */
    public function isIdEqualTo($stemId)
    {
        return $this->id == $stemId;


    }
}