<?php

namespace frontend\controllers;

use app\models\JevAccountingEntries;
use Yii;
use app\models\JevPreparation;
use app\models\JevPreparationSearch;
use Exception;
use frontend\models\Model;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * JevPreparationController implements the CRUD actions for JevPreparation model.
 */
class JevPreparationController extends Controller
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
     * Lists all JevPreparation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JevPreparationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single JevPreparation model.
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
    public function actionGeneralLedgerIndex()
    {
        // echo "success";
        $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        chart_of_accounts.general_ledger
        FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
        AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        AND jev_preparation.fund_cluster_code_id =1 AND jev_accounting_entries.chart_of_account_id =1 
        ORDER BY jev_preparation.reporting_period")->queryAll();
        return $this->render('general_ledger_view', [
            'model' => $chart,
        ]);
    }
    public function actionLedger()
    {
        $gen = $_POST['gen'] ? 'AND jev_accounting_entries.chart_of_account_id =' . $_POST['gen'] : '';
        $fund = $_POST['fund'] ? 'AND jev_preparation.fund_cluster_code_id =' . $_POST['fund'] : '';
        // $reporting_period = $_POST['reporting_period'] ? "'AND jev_preparation.reporting_period ='" .  (String)$_POST['reporting_period'] ."'" : '';
        $reporting_period = $_POST['reporting_period'] ? "AND jev_preparation.reporting_period ='{$_POST['reporting_period']}'"  : '';
        $chart = Yii::$app->db->createCommand("SELECT  jev_preparation.explaination, jev_preparation.jev_number, jev_preparation.reporting_period ,
        jev_accounting_entries.id,jev_accounting_entries.debit,jev_accounting_entries.credit,chart_of_accounts.uacs,
        chart_of_accounts.general_ledger,jev_accounting_entries.id,(SELECT fund_cluster_code.name from fund_cluster_code where fund_cluster_code.id={$_POST['fund']}) as fund_cluster_code
        FROM jev_preparation,jev_accounting_entries,chart_of_accounts where jev_preparation.id = jev_accounting_entries.jev_preparation_id
        AND jev_accounting_entries.chart_of_account_id = chart_of_accounts.id
        
      {$gen} {$fund} {$reporting_period}
        ORDER BY jev_preparation.reporting_period

        ")->queryAll();

        echo json_encode(['results' => $chart]);
    }

    /**
     * Creates a new JevPreparation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new JevPreparation();

        $modelJevItems = [new JevAccountingEntries()];
        if ($model->load(Yii::$app->request->post())) {
            $modelJevItems = Model::createMultiple(JevAccountingEntries::class);
            Model::loadMultiple($modelJevItems, Yii::$app->request->post());

            // ajax validation
            // if (Yii::$app->request->isAjax) {
            //     Yii::$app->response->format = Response::FORMAT_JSON;
            //     return ArrayHelper::merge(
            //         ActiveForm::validateMultiple($modelsAddress),
            //         ActiveForm::validate($modelCustomer)
            //     );
            // }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelJevItems) && $valid;
            // $model->jev_number .= '-' . $model->fund_cluster_code_id . '-' . $this->jevNumber($model->reporting_period);

            if ($valid) {


                if ($this->checkIfBalance($modelJevItems)) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {

                            foreach ($modelJevItems as $modelJevItem) {
                                $modelJevItem->jev_preparation_id = $model->id;
                                if (!($flag = $modelJevItem->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
            // return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelJevItems' => (empty($modelJevItems)) ? [new JevAccountingEntries] : $modelJevItems
        ]);
    }

    /**
     * Updates an existing JevPreparation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // $modelCustomer = $this->findModel($id);
        // $modelsAddress = $modelCustomer->addresses;

        $model = $this->findModel($id);
        $modelJevItems = $model->jevAccountingEntries;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $oldIDs = ArrayHelper::map($modelJevItems, 'id', 'id');
            $modelJevItems = Model::createMultiple(JevAccountingEntries::class, $modelJevItems);
            Model::loadMultiple($modelJevItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelJevItems, 'id', 'id')));


            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelJevItems);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            JevAccountingEntries::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelJevItems as $modelAddress) {
                            $modelAddress->jev_preparation_id = $model->id;
                            if (!($flag = $modelAddress->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    // var_dump($e);
                    $transaction->rollBack();
                }
            }
            // return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelJevItems' => (empty($modelJevItems)) ? [new JevAccountingEntries] : $modelJevItems
        ]);
    }

    /**
     * Deletes an existing JevPreparation model.
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
     * Finds the JevPreparation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return JevPreparation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = JevPreparation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function jevNumber($reporting_period)
    {


        $query = JevPreparation::find()
            ->select('jev_number')
            ->orderBy([
                'id' => SORT_DESC
            ])->one();
        if (!empty($query)) {
            $x = explode('-', $query->jev_number)[4] + 1;
        } else {
            $x = 1;
        }
        $y = null;
        $len = strlen($x);

        // add zero bag.o mag last number
        for ($i = $len; $i < 4; $i++) {
            $y .= 0;
        }
        $year = date('Y', strtotime($reporting_period));
        $year .= '-' . date('m');
        $year .= '-' . $y . $x;

        VarDumper::dump($year);

        return $year;
    }
    public function checkIfBalance($jevItems)
    {
        $debit = 0;
        $credit = 0;
        foreach ($jevItems as $item) {
            $debit += $item->debit;
            $credit += $item->credit;
        }
        if ($debit == $credit) {
            return true;
        } else {
            return false;
        }
    }
}
