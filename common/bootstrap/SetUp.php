<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 02.02.19
 * Time: 21:20
 */
namespace common\bootstrap;

use shop\cart\Cart;
use shop\services\ContactService;
use yii\base\BootstrapInterface;
use yii\mail\MailerInterface;
use yii\base\ErrorHandler;
use yii\rbac\ManagerInterface;
use yii\swiftmailer\Mailer;

class SetUp implements BootstrapInterface
{


    public function bootstrap($app)
    {

        $container = \Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app){

            return $app->mailer;
        });


        $container->setSingleton(ErrorHandler::class, function () use ($app) {

            return $app->errorHandler;
        });



        $container->setSingleton(ManagerInterface::class, function () use ($app) {

            return $app->authManager;
        });


        $container->setSingleton(ContactService::class, [], [$app->params['adminEmail']]);





    }


}