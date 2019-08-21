<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 22.03.18
 * Time: 19:26
 */

namespace shop\entities\shop\product;


use yii\db\ActiveRecord;
use yii\helpers\Url;


/**
 * @property integer $id
 * @property integer $product_id
 * @property string $file
 * @property integer $sort
 */
class AudioFile extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_audio}}';
    }


    /**
     * @param $productId
     * @param $file
     * @param $i
     * @return static
     */
    public static function create($productId, $file, $i)
    {
        $photo = new static();
        $photo->product_id = $productId;
        $photo->file = $file;
        $photo->sort = $i;

        return $photo;


    }

    /**
     * @param $id
     */

    public function unlinkPhotos($id)
    {
        $audios = AudioFile::findAll(['product_id' => $id]);

        foreach ($audios as $audio) {

            if ($audio->file) {

                unlink((\Yii::getAlias('@webroot/uploads/mp3/' . $audio->file)));

            }

        }
    }

    /**
     * @param $productId
     * @param $photoId
     * @return string
     */

    public function getAudioFile($productId, $photoId)
    {
        $audio = AudioFile::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->one();

        return Url::toRoute(@backend . '/web/uploads/mp3/' . $audio->file);

    }

    /**
     * @param $productId
     * @param $photoId
     * @return string
     */
    public function getAdminAudioFile($productId, $photoId)
    {

        $audio = AudioFile::find()->where(['product_id' => $productId])->andWhere(['id' => $photoId])->one();

        return Url::toRoute('/uploads/mp3/' . $audio->file);


    }

    /**
     * @param $audioId
     * @return bool
     */
    public function isIdEqualTo($audioId)
    {

        return $this->id == $audioId;


    }

    /**
     * @param $sort
     */

    public function setSort($sort)
    {
        $this->sort = $sort + 1;

        $this->save();

    }


    /**
     * @param array $audios
     */

    private function updatePhotos($audios)
    {
        foreach ($audios as $i => $audio) {
            $audio->setSort($i);
        }

    }

    /**
     * @param $productId
     * @param $audioId
     */
    public function removeAudio($productId, $audioId)
    {
        $audio = AudioFile::find()->where(['product_id' => $productId])->andWhere(['id' => $audioId])->orderBy('sort')->one();

        unlink((\Yii::getAlias('@webroot/uploads/mp3/' . $audio->file)));


        if ($audio->delete() !== false) {

            $audios = AudioFile::find()->where(['product_id' => $productId])->orderBy('sort')->all();


            $this->updatePhotos($audios);

        }


    }

}