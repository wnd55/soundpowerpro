<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.01.19
 * Time: 19:13
 */

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\DataProviderInterface */
/* @var $searchForm \shop\forms\shop\search\SearchForm */


use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Поиск';

$this->params['breadcrumbs'][] = $this->title;
?>

<h1 class="_top"><?= Html::encode($this->title) ?></h1>
<div class="panel panel-default">
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['action' => [''], 'method' => 'get', ])?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($searchForm, 'text')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchForm, 'category')->dropDownList($searchForm->categoriesList(), ['prompt' => '']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($searchForm, 'brand')->dropDownList($searchForm->brandsList(), ['prompt' => '',]) ?>

            </div>


        </div>


        <div class="row">
            <div class="col-md-6">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
            </div>
            <div class="col-md-6">
                <?= Html::a('Clear', [''], ['class' => 'btn btn-default btn-lg btn-block']) ?>
            </div>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>


<?= $this->render('_list', [
    'dataProvider' => $dataProvider,


]) ?>

