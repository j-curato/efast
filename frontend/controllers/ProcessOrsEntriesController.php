<?php

namespace frontend\controllers;

use app\models\ProcessOrs;
use Yii;
use app\models\ProcessOrsEntries;
use app\models\ProcessOrsEntriesSearch;
use app\models\ProcessOrsRaoudsSearch;
use app\models\RaoudEntries;
use app\models\Raouds;
use app\models\Raouds2Search;
use app\models\RaoudsSearch;
use app\models\RaoudsSearch2;
use app\models\RecordAllotmentEntries;
use ErrorException;
use Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProcessOrsEntriesController implements the CRUD actions for ProcessOrsEntries model.
 */
class ProcessOrsEntriesController extends Controller
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
     * Lists all ProcessOrsEntries models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessOrsRaoudsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProcessOrsEntries model.
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
     * Creates a new ProcessOrsEntries model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new ProcessOrsEntries();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {p
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
        $searchModel = new Raouds2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
        ]);
    }

    /**
     * Updates an existing ProcessOrsEntries model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        // $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('update', [
        //     'model' => $model,
        // ]);
        $searchModel = new Raouds2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id
        ]);
    }
    public function actionAdjust($id)
    {
        $searchModel = new Raouds2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id
        ]);
    }

    /**
     * Deletes an existing ProcessOrsEntries model.
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
     * Finds the ProcessOrsEntries model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProcessOrsEntries the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProcessOrsEntries::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    // PAG KUHA SA RAOUDS DATA NGA BUHATAN OG OBLIGATION
    public function actionSample()
    {
        $x = [];
        foreach ($_POST['selection'] as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    'mfo_pap_code.code AS mfo_pap_code_code', 'mfo_pap_code.name AS mfo_pap_name', 'fund_source.name AS fund_source_name',
                    'chart_of_accounts.uacs as object_code', 'chart_of_accounts.general_ledger', 'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id', 'raouds.id AS raoud_id',
                    'entry.total', 'record_allotment_entries.amount', '(record_allotment_entries.amount - entry.total) AS remain'
                ])
                ->from('raouds')
                ->join("LEFT JOIN", "record_allotment_entries", "raouds.record_allotment_entries_id=record_allotment_entries.id")
                ->join("LEFT JOIN", "record_allotments", "record_allotment_entries.record_allotment_id=record_allotments.id")
                ->join("LEFT JOIN", "chart_of_accounts", "record_allotment_entries.chart_of_account_id=chart_of_accounts.id")
                ->join("LEFT JOIN", "major_accounts", "chart_of_accounts.major_account_id=major_accounts.id")
                ->join("LEFT JOIN", "fund_source", "record_allotments.fund_source_id=fund_source.id")
                ->join("LEFT JOIN", "mfo_pap_code", "record_allotments.mfo_pap_code_id=mfo_pap_code.id")
                ->join("LEFT JOIN", "raoud_entries", "raouds.id=raoud_entries.raoud_id")
                ->join("LEFT JOIN", "(SELECT SUM(raoud_entries.amount) as total,
                raouds.id, raouds.process_ors_id,
                raouds.record_allotment_entries_id
                FROM raouds,raoud_entries,process_ors
                WHERE raouds.process_ors_id= process_ors.id
                AND raouds.id = raoud_entries.raoud_id
                AND raouds.process_ors_id IS NOT NULL 
                GROUP BY raouds.record_allotment_entries_id) as entry", "raouds.record_allotment_entries_id=entry.record_allotment_entries_id")
                // ->join("LEFT JOIN","","raouds.process_ors_id=process_ors.id")

                ->where("raouds.id = :id", ['id' => $val])->one();
            $query['obligation_amount'] =  $_POST['amount'][$val];
            $x[] = $query;
        }

        // return json_encode($_POST['selection']);
        // $query=Yii::$app->db->createCommand("SELECT * FROM raouds where id IN ('1','2')")->queryAll();

        return json_encode(['results' => $x]);
    }

    public function actionInsertProcessOrs()
    {
        // return json_encode($_POST['reporting_period']);
        if (!empty($_POST)) {
            $reporting_period = $_POST['reporting_period'];
            $transaction_id = $_POST['transaction_id'];
            // return json_encode($_POST['chart_of_account_id']);
            $transaction = \Yii::$app->db->beginTransaction();
            // KUNG NAAY SULOD ANG UPDATE ID  MAG ADD OG RAOUD OG ENTRY NIYA  PARA E ADJUST
            if ($_POST['update_id'] > 0) {
                try {
                    // KUHAON ANG PARENT NA RAOUD NA E ADJUST
                    $raoud_to_adjust = Raouds::find()->where("id =:id", ['id' => $_POST['update_id']])->one();
                    // $ors_id = ::find($_POST['update_id'])->recordAllotment->id;
                    $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total_adjustment
                    FROM `raouds`,raoud_entries
                    WHERE raouds.id=raoud_entries.raoud_id
                    AND raoud_entries.amount >0
                    AND raoud_entries.parent_id_from_raoud = :raoud_id
                     ")
                        ->bindValue(":raoud_id", $raoud_to_adjust->id)
                        ->queryOne();

                    $total_amount = array_sum($_POST['obligation_amount']);
                    $adjust_total = $total_amount + $query['total_adjustment'];

                    $remaining_balance = $raoud_to_adjust->raoudEntries->amount -  $query['total_adjustment'];
                    // return  json_encode($query['total_adjustment']); 
                    if ($raoud_to_adjust->raoudEntries->amount >= $adjust_total) {

                        $raoud_to_adjust->isActive = false;
                        $raoud_to_adjust->save();

                        // echo $reporting_period;
                        foreach ($_POST['chart_of_account_id'] as $index => $value) {
                            // KANI MAO NI ANG RAOUDS KUNG ASA E CHARGE ANG GE ADJUST
                            $raoud_to_charge_adjustment = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                            // $raoud_to_charge_adjustment->isActive = 0;
                            // $raoud_to_charge_adjustment->save();

                            for ($i = 0; $i < 2; $i++) {
                                $raoud = new Raouds();
                                // $raoud->record_allotment_id = $raoud_to_charge_adjustment->record_allotment_id;
                                $amount = 0;
                                if ($i === 0) {
                                    $amount = -$_POST['obligation_amount'][$index];
                                    $chart_of_account_id = $raoud_to_adjust->raoudEntries->chart_of_account_id;
                                    $record_allotment_entries_id = $raoud_to_adjust->record_allotment_entries_id;
                                } else {
                                    $amount = $_POST['obligation_amount'][$index];
                                    $chart_of_account_id = $value;
                                    $record_allotment_entries_id = $raoud_to_charge_adjustment->record_allotment_entries_id;
                                }
                                $raoud->record_allotment_entries_id = $record_allotment_entries_id;
                                $raoud->is_parent = false;
                                $raoud->isActive = false;
                                $raoud->process_ors_id = $raoud_to_adjust->process_ors_id;
                                $raoud->reporting_period = $_POST['reporting_period'];
                                $raoud->obligated_amount = $amount;
                                if ($flag = $raoud->validate()) {
                                    if ($raoud->save()) {

                                        $raoud_entry = new RaoudEntries();
                                        $raoud_entry->raoud_id = $raoud->id;
                                        $raoud_entry->chart_of_account_id = $chart_of_account_id;
                                        $raoud_entry->amount = $amount;
                                        $raoud_entry->parent_id_from_raoud = $raoud_to_adjust->id;

                                        if ($raoud_entry->validate()) {
                                            if ($raoud_entry->save()) {
                                            }
                                        } else {
                                            $transaction->rollBack();
                                            return json_encode(['error' => 'yawa sa raoud entry']);
                                        }
                                    }
                                } else {

                                    $transaction->rollBack();
                                    return json_encode(["error" => 'yawa sa raoud']);
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return json_encode([
                                'isSuccess' => true,
                            ]);
                        }
                    } else {
                        return json_encode(['isSuccess' => false, 'error' =>
                        "Obligation Amount is Less than Adjust Amount  Remaining Balance is  $remaining_balance"]);
                    }
                } catch (ErrorException $e) {
                    return json_encode(["error" => $e]);
                }
            }
            // KUNG WLAY SULOD ANG UPDATE_ID DRI MO SULOD MAG BUHAT OG BAG.O NA DATA
            else {


                try {
                    $ors = new ProcessOrs();
                    $ors->reporting_period = $reporting_period;
                    $ors->transaction_id = $transaction_id;
                    $ors->serial_number = $this->getOrsSerialNumber($reporting_period);
                    if ($ors->validate()) {


                        if ($flag = $ors->save()) {
                            // echo $reporting_period;
                            foreach ($_POST['chart_of_account_id'] as $index => $value) {

                                $q = Raouds::find()->where("id =:id", ['id' => $_POST['raoud_id'][$index]])->one();
                                // $q->isActive = 0;
                                // $q->save();
                                $qwe = explode('-', $q->serial_number);
                                $raoud = new Raouds();
                                // $raoud->record_allotment_id = $q->record_allotment_id;
                                $raoud->record_allotment_entries_id = $q->record_allotment_entries_id;
                                $raoud->is_parent = false;
                                $raoud->serial_number = Yii::$app->memem->getOrsBursRaoudSerialNumber($q->serial_number);
                                $raoud->isActive = false;
                                $raoud->process_ors_id = $ors->id;
                                $raoud->reporting_period = $ors->reporting_period;
                                $raoud->obligated_amount = $_POST['obligation_amount'][$index];
                                if ($raoud->validate()) {
                                    if ($raoud->save()) {
                                        $raoud_entry = new RaoudEntries();
                                        $raoud_entry->raoud_id = $raoud->id;
                                        $raoud_entry->chart_of_account_id = $value;
                                        $raoud_entry->amount = $_POST['obligation_amount'][$index];
                                        if ($raoud_entry->validate()) {
                                            if ($raoud_entry->save()) {
                                            }
                                        } else {
                                            $transaction->rollBack();
                                            return json_encode(['error' => 'yawa sa raoud entry']);
                                        }
                                    }
                                } else {

                                    $transaction->rollBack();
                                    return json_encode(["error" => 'yawa sa raoud']);
                                }


                                $ors_entry = new ProcessOrsEntries();
                                $ors_entry->chart_of_account_id = $value;
                                $ors_entry->process_ors_id = $ors->id;
                                $ors_entry->amount = $_POST['obligation_amount'][$index];
                                if ($ors_entry->validate()) {

                                    if ($ors_entry->save()) {
                                    }
                                } else {
                                    $transaction->rollBack();
                                    return json_encode(["error" => 'yawa sa ors entry']);
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return json_encode(['isSuccess' => true]);
                        }
                    } else {
                        return json_encode(['isSuccess' => false, 'error' => $ors->errors]);
                    }
                } catch (ErrorException $e) {
                    return json_encode(["error" => $e]);
                }
            }

            // $ors->reporting_period = $reporting_period

        }
    }
    public function actionQwe()
    {
        $query = (new \yii\db\Query())
            ->select("serial_number")
            ->from('process_ors')
            ->orderBy("id DESC")
            ->one();

        $reporting_period = "2021-01";
        $year = date('Y', strtotime($reporting_period));


        if (empty($query['serial_number'])) {
            $serial_number  = $year . '-' . 1;
        } else {
            $last_number = explode('-', $query['serial_number']);
            $serial_number = $year . '-' . $last_number + 1;
        }
        echo "<pre>";
        var_dump($serial_number);
        echo "</pre>";
    }
    public function getOrsSerialNumber()
    {
        $reporting_period="2020-01";
        $query = (new \yii\db\Query())
            ->select("serial_number")
            ->from('process_ors')
            ->orderBy("id DESC")
            ->one();

        // $reporting_period = "2021-01";
        $year = date('Y', strtotime($reporting_period));


        if (empty($query['serial_number'])) {
            $serial_number  = $reporting_period . '-' . 1;
        } else {
            $last_number = explode('-', $query['serial_number']);
            $x = intval($last_number[2]) + 1;
            $serial_number = $reporting_period . '-' . $x;
        }
        // ob_start();
        // echo "<pre>";
        // var_dump( $last_number[1]);
        // echo "</pre>";
        // return ob_get_clean();
        return $serial_number;
    }
    public function getRaoudSerialNumber($serial_number)
    {
        // $serial_number = "Fund 01-2021-01-3";
        $raoud = (new \yii\db\Query())
            ->select("serial_number")
            ->from('raouds')
            ->orderBy('id DESC')
            ->one();

        $x = explode('-', $serial_number);
        $x[3] = explode('-', $raoud['serial_number'])[3] + 1;
        $y = implode('-', $x);

        return $y;
    }
}
