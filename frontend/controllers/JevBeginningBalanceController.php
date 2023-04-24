<?php

namespace frontend\controllers;

use Yii;
use app\models\JevBeginningBalance;
use app\models\JevBeginningBalanceItem;
use app\models\JevBeginningBalanceSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * JevBeginningBalanceController implements the CRUD actions for JevBeginningBalance model.
 */
class JevBeginningBalanceController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all JevBeginningBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JevBeginningBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JevBeginningBalance model.
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
     * Creates a new JevBeginningBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function insertEntries($model_id, $debits, $object_codes, $credits)
    {
        foreach ($object_codes as $i => $val) {
            $item = new JevBeginningBalanceItem();
            $item->jev_beginning_balance_id = $model_id;
            $item->debit = $debits[$i];
            $item->credit = $credits[$i];
            $item->object_code = $object_codes[$i];
            if ($item->save(false)) {
            } else {
                return false;
            }
        }
        return true;
    }
    public function actionCreate()
    {
        $model = new JevBeginningBalance();

        if ($_POST) {
            $year = $_POST['year'];
            $book_id = $_POST['book_id'];
            $transaction = Yii::$app->db->beginTransaction();
            $debits = !empty($_POST['debit']) ? (array)json_decode($_POST['debit']) : [];
            $credits = !empty($_POST['credit']) ? (array)json_decode($_POST['credit']) : [];
            $object_codes = !empty($_POST['object_code']) ? (array)json_decode($_POST['object_code']) : [];
            $model->book_id = $book_id;
            $model->year = $year;
            // var_dump((array)$object_codes);
            // echo "<br>";
            // return var_dump((array)$debits);

            try {

                if ($flag = true) {
                    if ($model->save(false)) {
                        $flag =   $this->insertEntries($model->id, $debits, $object_codes, $credits);
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return 'Insert Fail';
                }
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'entries' => []
        ]);
    }

    /**
     * Updates an existing JevBeginningBalance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $entries = Yii::$app->db->createCommand("SELECT 

        jev_beginning_balance_item.object_code,
        accounting_codes.account_title,
        jev_beginning_balance_item.debit,
        jev_beginning_balance_item.credit
        FROM `jev_beginning_balance_item`
        LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
        WHERE jev_beginning_balance_item.jev_beginning_balance_id = :id
        ;")
            ->bindValue(':id', $id)
            ->queryAll();

        if ($_POST) {
            $transaction = Yii::$app->db->beginTransaction();

            Yii::$app->db->createCommand("DELETE FROM jev_beginning_balance_item WHERE jev_beginning_balance_id = :id")
                ->bindValue(':id', $id)
                ->query();
            $year = $_POST['year'];
            $book_id = $_POST['book_id'];
            $debits = !empty($_POST['debit']) ? (array)json_decode($_POST['debit']) : [];
            $credits = !empty($_POST['credit']) ? (array)json_decode($_POST['credit']) : [];
            $object_codes = !empty($_POST['object_code']) ? (array)json_decode($_POST['object_code']) : [];
            $model->book_id = $book_id;
            $model->year = $year;
            try {

                if ($flag = true) {
                    if ($model->save(false)) {
                        $flag =   $this->insertEntries($model->id, $debits, $object_codes, $credits);
                    } else {
                        $flag = false;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    $transaction->rollBack();
                    return 'Insert Fail';
                }
            } catch (ErrorException $e) {

                $transaction->rollBack();
                return json_encode($e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $model,
            'entries' => $entries
        ]);
    }

    /**
     * Deletes an existing JevBeginningBalance model.
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
     * Finds the JevBeginningBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JevBeginningBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JevBeginningBalance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
