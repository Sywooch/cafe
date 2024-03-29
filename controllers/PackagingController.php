<?php

namespace app\controllers;

use Yii;
use app\models\Packaging;
use app\models\PackagingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \yii\db\Exception;

/**
 * PackagingController implements the CRUD actions for Packaging model.
 */
class PackagingController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete', 'updateordering','reorder','batchupdateordering'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'updateordering','reorder','batchupdateordering'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Packaging models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PackagingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    
    public function actionReorder() {
        
        $data = Packaging::find()->orderBy('packaging_ordering ASC')->all();
        return $this->render('reorder', [
               'data' => $data,
        ]);
        
        //$query = Packaging::find()->orderBy('packaging_ordering ASC');
        //
        //$dataProvider = new SqlDataProvider([
        //    'sql' => $query,
        //    'pagination'=>false,
        //]);
        //return $this->render('reorder', [
        //       'dataProvider' => $dataProvider,
        //]);
    }

    
    /**
     * Displays a single Packaging model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Packaging model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Packaging();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = \yii\web\UploadedFile::getInstanceByName('packaging_icon_file');
            if ($file) {
                $iconfilename = 'packaging' . $model->packaging_id . '.' . $file->getExtension();
                if ($file->size > 0 && $file->saveAs(Yii::$app->params['file_root_dir'] . '/' . $iconfilename)) {
                    $model->packaging_icon = $iconfilename;
                    $model->save();
                }
            }
            return $this->redirect(['update', 'id' => $model->packaging_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Packaging model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $file = \yii\web\UploadedFile::getInstanceByName('packaging_icon_file');
            if ($file) {
                $iconfilename = 'packaging' . $model->packaging_id . '.' . $file->getExtension();
                if ($file->size > 0 && $file->saveAs(Yii::$app->params['file_root_dir'] . '/' . $iconfilename)) {
                    $model->packaging_icon = $iconfilename;
                    $model->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->packaging_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Packaging model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $iconfilename = Yii::$app->params['file_root_dir'] . '/' . $model->packaging_icon;

        try {
            $model->delete();
            if (is_file($iconfilename)) {
                unlink($iconfilename);
            }
        } catch (Exception $e) {
            
        }
        return $this->redirect(['index']);
    }

    public function actionProductlist($id) {
        $model = $this->findModel($id);
        $productList = $model->getPackagingProducts()->all();
        //var_dump($productList);
        $result = Array();
        foreach ($productList as $it) {
            $item = Array();
            $product = $it->getProduct()->one();
            //var_dump($product);exit();
            $item['product_id'] = $product->product_id;
            $item['product_title'] = $product->product_title;
            $item['product_unit'] = $product->product_unit;
            $item['product_unit_price'] = $product->product_unit_price;
            $item['packaging_product_quantity'] = $it->packaging_product_quantity;
            $item['packaging_product_price'] = $it->packaging_product_quantity * $product->product_unit_price;
            $result[] = $item;
        }
        //var_dump($result);
        return json_encode($result);
    }

    public function actionUpdateordering() {
        $packaging_id = \Yii::$app->request->post('packaging_id');
        $packaging_ordering = \Yii::$app->request->post('packaging_ordering');

        $model = $this->findModel($packaging_id);
        $model->packaging_ordering = $packaging_ordering;
        $model->update();
        return 'OK';
    }

    
    public function actionBatchupdateordering() {
        
        $ids=array_map(function($v){return (int)trim($v);},explode(',',\Yii::$app->request->post('ids')));
        
        foreach($ids as $packaging_ordering => $packaging_id){
            echo "$packaging_ordering => $packaging_id\n";
            $model = $this->findModel($packaging_id);
            $model->packaging_ordering = $packaging_ordering+1;
            $model->update();            
        }
        return 'OK';
    }

    
    /**
     * Finds the Packaging model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Packaging the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Packaging::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
