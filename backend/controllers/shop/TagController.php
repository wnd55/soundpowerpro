<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 19.03.18
 * Time: 14:15
 */

namespace backend\controllers\shop;

use backend\forms\shop\TagSearchForm;
use shop\entities\shop\Tag;
use Yii;
use shop\forms\manage\shop\TagForm;
use shop\services\manage\shop\TagManageService;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;

class TagController extends Controller
{

    private $service;

    /**
     * TagController constructor.
     * @param string $id
     * @param \yii\base\Module $module
     * @param TagManageService $service
     * @param array $config
     */

    public function __construct($id, $module, TagManageService $service, array $config = [])
    {

        parent::__construct($id, $module, $config);

        $this->service = $service;
    }

    /**
     * @return array
     */

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * @return mixed
     */

    public function actionIndex()
    {
        $searchTag = new TagSearchForm();

        $dataProvider = $searchTag->search(Yii::$app->request->queryParams);

        return $this->render('index', [

            'searchTag' => $searchTag,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * @return mixed
     */

    public function actionCreate()
    {
        $form = new TagForm();

        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {

            try {

                $tag = $this->service->create($form);
                return $this->redirect(['view', 'id' => $tag->id]);
            } catch (\DomainException $e) {

                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }


        return $this->render('create', ['model' => $form]);


    }

    /**
     * @param $id
     * @return string
     */

    public function actionView($id)
    {

        return $this->render('view', ['tag' => $this->findTag($id)]);


    }

    /**
     * @param $id
     * @return null|static
     */

    protected function findTag($id)
    {
        if(($tag = Tag::findOne($id)) !== null){

            return $tag;
        }

        throw new \DomainException('The requested page does not exist');

    }
    /**
     * @param integer $id
     * @return mixed
     *
     */

    public function actionUpdate($id)
    {
        $tag =$this->findTag($id);

        $form = new TagForm($tag);

        if($form->load(Yii::$app->request->post()) && $form->validate()){

           try {

               $this->service->edit($tag->id, $form);

               return $this->redirect(['view', 'id' => $tag->id]);

           }catch (\DomainException $e){

               Yii::$app->errorHandler->logException($e);
               Yii::$app->session->setFlash('error', $e->getMessage());

           }

        }

        return $this->render('update',[

            'model' => $form,
            'tag'=> $tag
        ]);

    }

    /**
     * @param integer $id
     * @return mixed
     */

    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);


    }


}