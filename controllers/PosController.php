<?php

namespace app\controllers;

use Yii;
use app\models\Pos;
use app\models\PosSearch;
use app\models\Supply;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;


/**
 * PosController implements the CRUD actions for Pos model.
 */
class PosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update','delete','products','supply','supplyprint'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update','delete','products','supply','supplyprint'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Pos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pos model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pos_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Pos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->pos_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionProducts($pos_id){
        $model = $this->findModel($pos_id);
        //$dataProvider=$model->getPosProducts();
        $query = $model->getPosProducts();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('products', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionSupply($id){
        $model = $this->findModel($id);
        $dataProvider=Supply::getSupply($id);        
        return $this->render('supply', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionSupplyprint($id){
        $model = $this->findModel($id);
        $dataProvider=Supply::getSupplyPrint($id);        
        return $this->render('supplyprint', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Deletes an existing Pos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Pos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
