<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\db\Exception;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                'only' => ['index', 'view', 'create', 'update','delete','updateproductquantity'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update','delete','updateproductquantity'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = \yii\web\UploadedFile::getInstanceByName('product_icon_file');
            if($file){
                $iconfilename=$model->product_id.'.'.$file->getExtension();
                if($file->saveAs(Yii::$app->params['file_root_dir'].'/'.$iconfilename)){
                    $model->product_icon=$iconfilename;
                    $model->save();                
                }
            }
            return $this->redirect(['view', 'id' => $model->product_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = \yii\web\UploadedFile::getInstanceByName('product_icon_file');
            if($file){
                $iconfilename='product'.$model->product_id.'.'.$file->getExtension();
                if($file->size>0 && $file->saveAs(Yii::$app->params['file_root_dir'].'/'.$iconfilename)){
                    $model->product_icon=$iconfilename;
                    $model->save();                
                }
            }
            return $this->redirect(['view', 'id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $iconfilename=Yii::$app->params['file_root_dir'].'/'.$model->product_icon;
        try{
            $model->delete();
            if(is_file($iconfilename)){
                unlink($iconfilename);
            }            
        }catch (Exception $e){
            
        }
        return $this->redirect(['index']);
    }

    
    public function actionUpdateproductquantity(){
        $product_id = \Yii::$app->request->post('product_id');
        $product_quantity = \Yii::$app->request->post('product_quantity');
        $model = $this->findModel($product_id); 
        $model->product_quantity = $product_quantity;
        $model->update();
        return '{"status":"success"}';
    }
    
    
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
