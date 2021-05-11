<?php

namespace frontend\controllers;

use Yii;
use app\models\Liquidation;
use app\models\LiquidataionSearch;
use app\models\LiquidationEntries;
use app\models\LiquidationEntriesSearch;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LiquidationController implements the CRUD actions for Liquidation model.
 */
class LiquidationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Liquidation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LiquidationEntriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Liquidation model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // $q = LiquidationEntries::findOne(($id));
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Liquidation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Liquidation();


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Liquidation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $q  = LiquidationEntries::findOne($id);
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('update', [
            'model' => $model,
            'update_type' => 'update'
        ]);
    }

    /**
     * Deletes an existing Liquidation model.
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
    public function actionReAlign($id)
    {
        $q  = LiquidationEntries::findOne($id);
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('update', [
            'model' => $model,
            'update_type' => 're-align'
        ]);
    }

    /**
     * Finds the Liquidation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Liquidation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Liquidation::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAddAdvances()
    {

        if ($_POST) {
            $selected = $_POST['selection'];
            $params = [];

            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'advances.id', $selected], $params);

            $query = (new \yii\db\Query())
                ->select('*')
                ->from('advances')
                ->where("$sql", $params)
                ->all();

            return json_encode($query);
        }
    }
    public function actionInsertLiquidation()
    {
        if ($_POST) {
            $advances_id = !empty($_POST['advances_id']) ? $_POST['advances_id'] : '';
            $payee_id = $_POST['payee'];
            $check_date = $_POST['check_date'];
            $check_number = $_POST['check_number'];
            $particular = $_POST['particular'];
            $chart_of_account = !empty($_POST['chart_of_account_id']) ? $_POST['chart_of_account_id'] : '';
            $withdrawal = !empty($_POST['withdrawal']) ? $_POST['withdrawal'] : '';
            $vat_nonvat = !empty($_POST['vat_nonvat']) ? $_POST['vat_nonvat'] : '';
            $ewt = !empty($_POST['ewt']) ? $_POST['ewt'] : '';
            $update_id = !empty($_POST['update_id']) ? $_POST['update_id'] : '';
            $type = !empty($_POST['update_type']) ? $_POST['update_type'] : '';
            $new_reporting_period = !empty($_POST['new_reporting_period']) ? $_POST['new_reporting_period'] : '';

            // ob_clean();
            // echo "<pre>";
            // var_dump($ewt);
            // echo "</pre>";
            // die();
            $transaction = Yii::$app->db->beginTransaction();
            $id ='';
            if (!empty($update_id)) {
                $liquidation = Liquidation::findOne($update_id);
                // foreach ($liquidation->liquidationEntries as $val) {
                //     $val->delete();
                // }
            } else {

                $liquidation = new Liquidation();
            }
            $liquidation->check_date = $check_date;
            $liquidation->payee_id = $payee_id;
            $liquidation->check_number = $check_number;
            $liquidation->particular = $particular;

            try {
                if ($liquidation->validate()) {
                    if ($flag = $liquidation->save(false)) {
                        if (!empty($advances_id)) {

                            foreach ($advances_id as $index => $val) {

                                list($withd) = sscanf(implode(explode(',', $withdrawal[$index])), "%f");
                                list($vat) = sscanf(implode(explode(',', $vat_nonvat[$index])), "%f");
                                list($e) = sscanf(implode(explode(',', $ewt[$index])), "%f");
                                $liq_entries = new LiquidationEntries();
                                $liq_entries->liquidation_id = $liquidation->id;
                                $liq_entries->chart_of_account_id = $chart_of_account[$index];
                                $liq_entries->advances_id = $val;
                                $liq_entries->withdrawals = $withd;
                                $liq_entries->vat_nonvat = $vat;
                                $liq_entries->ewt_goods_services = $e;
                                $liq_entries->reporting_period = $new_reporting_period[$index];

                                if ($liq_entries->validate()) {
                                    if ($liq_entries->save(false)) {
                                       
                                    }
                                } else {
                                    $transaction->rollBack();
                                    return json_encode(['isSuccess' => false, 'error' => $liq_entries->errors]);
                                }
                            }
                        }
                    }
                    if ($flag) {

                        $transaction->commit();
                        return json_encode(['isSuccess' => true, 'id' => $liquidation->id]);
                    }
                } else {
                    $transaction->rollback();
                    return json_encode(['isSuccess' => false, 'error' => $liquidation->errors]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                return json_encode(['isSuccess' => false, 'error' => $e->getMessage()]);
            }
        }
    }

    public function actionUpdateLiquidation()
    {
        if ($_POST) {
            $id = $_POST['update_id'];

            $query = (new \yii\db\Query())
                ->select([
                    'liquidation_entries.advances_id as id',
                    'advances.particular',
                    'advances.nft_number',
                    'advances.province',
                    'advances.report_type',
                    'liquidation_entries.chart_of_account_id',
                    'liquidation_entries.ewt_goods_services',
                    'liquidation_entries.vat_nonvat',
                    'liquidation_entries.withdrawals',
                    'liquidation_entries.reporting_period'
                ])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'advances', 'liquidation_entries.advances_id = advances.id')
                ->where("liquidation_entries.liquidation_id =:liquidation_id", ['liquidation_id' => $id])
                ->all();
            return json_encode($query);
        }
    }
}
