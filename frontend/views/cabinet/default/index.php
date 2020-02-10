<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <h1 class="_top"><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           [
               'attribute' => 'username',
                'label' => 'Логин'


               ],
            'email',

            [
                'attribute' => 'status',
                'value' => \shop\helpers\UserHelper::statusLabel($model->status),
                'format' => 'raw',
                'label' => 'Статус'
            ],

            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'label' => 'Дата регистрации'
            ],


        ]


    ]) ?>

    <p>
        <?= Html::a('Редактировать профиль', ['cabinet/profile/edit'], ['class' => 'btn btn-default']) ?>
    </p>

</div>
