<?php

namespace frontend\controllers;

use Yii;
use app\models\BacComposition;
use app\models\BacCompositionMember;
use app\models\BacCompositionnSearch;
use ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BacCompositionController implements the CRUD actions for BacComposition model.
 */
class BacCompositionController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all BacComposition models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new BacCompositionnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BacComposition model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BacComposition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertMember($model_id, $employee_id, $position)
    {
        foreach ($employee_id as $i => $val) {
            $item = new BacCompositionMember();
            $item->bac_composition_id = $model_id;
            $item->employee_id = $val;
            $item->bac_position_id = $position[$i];
            if ($item->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new BacComposition();

        if ($model->load(Yii::$app->request->post())) {
            if (empty($_POST['employee_id'])) {
                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
            } else {
                $employee_id = $_POST['employee_id'];
                $position = $_POST['position'];
            }

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag =  $this->insertMember(
                            $model->id,
                            $employee_id,
                            $position


                        );
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('Error');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return var_dump($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BacComposition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (empty($_POST['employee_id'])) {
                return json_encode(['error' => true, 'message' => 'Please Insert Items']);
            } else {
                $employee_id = $_POST['employee_id'];
                $position = $_POST['position'];
            }


            $transaction = Yii::$app->db->beginTransaction();
            Yii::$app->db->createCommand("DELETE FROM bac_composition_member WHERE bac_composition_id = :id")
            ->bindValue(':id',$model->id)
            ->query();
            try {
                if ($flag = true) {

                    if ($model->save(false)) {
                        $flag =  $this->insertMember(
                            $model->id,
                            $employee_id,
                            $position


                        );
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return json_encode('Error');
                }
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return var_dump($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BacComposition model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BacComposition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BacComposition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BacComposition::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
