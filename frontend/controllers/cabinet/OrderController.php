<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.06.18
 * Time: 13:46
 */

namespace frontend\controllers\cabinet;

use shop\readModels\shop\OrderReadRepository;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{

    public $layout = 'cabinet';
    private $orders;




    public function __construct($id,  $module, OrderReadRepository $orders,  array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->orders = $orders;




    }


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return string
     */

    public function actionIndex()
    {

        $dataProvider = $this->orders->getOwnOrder(\Yii::$app->user->id);


        return $this->render('index', ['dataProvider' => $dataProvider]);


    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */

    public function actionView($id)
    {

        if (!$order = $this->orders->findOwnOrder(\Yii::$app->user->id, $id)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'order' => $order,
        ]);
    }

}