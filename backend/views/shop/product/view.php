<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 21.03.18
 * Time: 10:52
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use shop\helpers\PriceHelper;
use shop\helpers\ProductHelper;
use shop\helpers\WeightHelper;


/* @var $this yii\web\View */
/* @var $product shop\entities\shop\product\Product */
/* @var $photosForm shop\forms\manage\shop\product\AudioFilesForm */
/* @var $modificationsProvider yii\data\ActiveDataProvider */
$this->registerJsFile('@web/js/addJS1.js');

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!empty($product->main_photo_id)) {
    $path = $product->mainPhoto->getAdminAudioFile($product->id, $product->main_photo_id);
}

?>
<div class="user-view">

    <p>
        <?php if ($product->isActive()): ?>
            <?= Html::a('Draft', ['draft', 'id' => $product->id], ['class' => 'btn btn-primary', 'data-method' => 'post']) ?>
        <?php else: ?>
            <?= Html::a('Activate', ['activate', 'id' => $product->id], ['class' => 'btn btn-success', 'data-method' => 'post']) ?>
        <?php endif; ?>
        <?= Html::a('Update', ['update', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $product->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Common</div>
                <div class="box-body">

                    <?= DetailView::widget([
                        'model' => $product,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'Статус',
                                'value' => ProductHelper::statusLabel($product->status),
                                'format' => 'raw',
                            ],

                            [
                                'attribute' => 'Бренд',
                                'value' => ArrayHelper::getValue($product, 'brand.name'),
                            ],
                            'code',
                            'name',
                            'slug',
                            [
                                'attribute' => 'Основная категория',
                                'value' => ArrayHelper::getValue($product, 'category.name'),
                            ],

                            [
                                'label' => 'Дополнительные категории',
                                'value' => implode(', ', ArrayHelper::getColumn($product->categories, 'name')),
                            ],

                            [
                                'label' => 'Тэги',
                                'value' => implode(', ', ArrayHelper::getColumn($product->tags, 'name')),
                            ],
                            [
                                'attribute' => 'Вес',
                                'value' => WeightHelper::format($product->weight),
                            ],

                            'quantity',
                            [
                                'attribute' => 'Цена',
                                'value' => PriceHelper::format($product->price_new),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'Старая старая',
                                'value' => PriceHelper::format($product->price_old),
                                'format' => 'raw',
                            ],

                            [
                                'attribute' => 'recommended',
                                'format' => 'boolean',
                            ],

                            [
                                'attribute' => 'popular',
                                'format' => 'boolean',
                            ],


                        ],

                    ]); ?>

                    <br/>
                    <p>
                        <?= Html::a('Изменить цену', ['price', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>


                        <?= Html::a('Изменить количество', ['quantity', 'id' => $product->id], ['class' => 'btn btn-primary']) ?>

                    </p>

                </div>
            </div>
        </div>

    </div>
</div>


<div class="box">
    <div class="box-header with-border">Description</div>
    <div class="box-body">
        <?= Yii::$app->formatter->asHtml($product->description, [
            'Attr.AllowedRel' => array('nofollow'),
            'HTML.SafeObject' => true,
            'Output.FlashCompat' => true,
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
        ]) ?>
    </div>
</div>


<div class="box">
    <div class="box-header with-border">SEO</div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $product,
            'attributes' => [
                [
                    'attribute' => 'meta_title',
                    'value' => $product->meta_title,
                ],
                [
                    'attribute' => 'meta_description',
                    'value' => $product->meta_description,
                ],
                [
                    'attribute' => 'meta_keywords',
                    'value' => $product->meta_keywords,
                ],
            ],
        ]) ?>
    </div>
</div>


<div class="box" id="photos">
    <div class="box-header with-border">mp3</div>
    <div class="box-body">
        <img src="<?= Yii::getAlias('@web/images/logo_mp3.png') ?>" title="Your Store" alt="Your Store"
             class="img-responsive" style="margin: auto;"/>
        <div class="row">

            <?php if (!empty($path)): ?>
                <div class="container">
                    <div class="row">


                        <div id="waveform">
                            <div id="music" data-about="<?= $path ?>">

                            </div>
                        </div>

                        <button class="btn btn-default" onclick="wavesurfer.playPause()">
                            <i class="fa fa-play"></i>
                            Play
                            /
                            <i class="fa fa-pause"></i>
                            Pause
                        </button>
                        <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-audio', 'productId' => $product->id, 'photoId' => $product->main_photo_id], [
                            'class' => 'btn btn-default',
                            'data-method' => 'post',
                            'data-confirm' => 'Удалить трэк?',
                        ]); ?>

                    </div>
                </div>

            <?php endif; ?>
        </div>

    </div>


    <?php if (empty($path)): ?>
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <?= $form->field($photosForm, 'file')->fileInput(['accept' => 'audio/*']) ?>

        <div class="form-group">
            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php else: ?>

    <div class="box">
        <div class="box-header with-border">Загрузить Stems</div>
        <div class="box-body">


        </div>
    </div>
    <?php endif; ?>


</div>
</div>

</div>

