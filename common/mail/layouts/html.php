<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>


    <style type="text/css">

        body {

            font-family: 'Open Sans', sans-serif;
            font-weight: 400;
            color: #666;
            font-size: 12px;
            line-height: 20px;

        }

        .logo img{

            max-width:200px;
            height: auto;
            margin: auto;

        }

        a {

            color: #40ab76;

        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table {

            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            background-color: transparent;
            border-collapse: collapse;
            border-spacing: 0;
            text-align: left;

        }


        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {

            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;

        }

        .table-striped > tbody > tr:nth-of-type(odd) {

            background-color: #f9f9f9;
        }

        .footer {


            margin-top: 30px;
            padding-top: 30px;
            padding-bottom: 30px;
            /*background-color: #e7e7e7;*/
            border-top: 1px solid #ddd;

        }

        span.intro {
            font-style: italic;

        }
        p.pull-right {

            float: right !important;
        }

    </style>
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>

<div class="footer">
   <p class="pull-right"> С наилучшими пожеланиями, команда <?= Yii::$app->name ?></p>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
