<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 26.05.18
 * Time: 21:21
 */

namespace frontend\controllers;


use Yii;
use shop\forms\ContactForm;
use shop\services\ContactService;
use yii\web\Controller;

class ContactController extends Controller

{

    private $service;

    public function __construct($id, $module, ContactService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }


    public function actionIndex()
    {


        $form = new ContactForm();


        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->send($form);
                Yii::$app->session->setFlash('success', 'Благодарим Вас за обращение к нам. Мы ответим вам как можно скорее.');
                return $this->goHome();
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $form,
        ]);


    }


}