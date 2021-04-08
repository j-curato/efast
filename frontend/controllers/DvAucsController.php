<?php

namespace frontend\controllers;

use Yii;
use app\models\DvAucs;
use app\models\DvAucsSearch;
use app\models\ProcessOrs;
use app\models\Raouds;
use app\models\Raouds2Search;
use app\models\RaoudsSearchForProcessOrsSearch;
use common\modules\auth\models\DvAucsEntries;
use ErrorException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DvAucsController implements the CRUD actions for DvAucs model.
 */
class DvAucsController extends Controller
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
     * Lists all DvAucs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DvAucsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DvAucs model.
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
     * Creates a new DvAucs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // $model = new DvAucs();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
        $searchModel = new RaoudsSearchForProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => '',
        ]);
    }

    /**
     * Updates an existing DvAucs model.
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
        $searchModel = new RaoudsSearchForProcessOrsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // echo $id;
        return $this->render('create', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'update_id' => $id,
        ]);
    }

    /**
     * Deletes an existing DvAucs model.
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
     * Finds the DvAucs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DvAucs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DvAucs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSample()
    {
        $x = [];
        foreach ($_POST['selection'] as $val) {

            $query = (new \yii\db\Query())
                ->select([
                    'mfo_pap_code.code AS mfo_pap_code_code', 'mfo_pap_code.name AS mfo_pap_name', 'fund_source.name AS fund_source_name',
                    'chart_of_accounts.uacs as object_code', 'chart_of_accounts.general_ledger', 'major_accounts.name',
                    'chart_of_accounts.id as chart_of_account_id', 'raouds.id AS raoud_id',
                    'raouds.obligated_amount',
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
            // $query['obligation_amount'] =  $_POST['amount'][$val];
            $query['1_percent_ewt'] =  $_POST['1_percent_ewt'][$val];
            $query['2_percent_ewt'] =  $_POST['2_percent_ewt'][$val];
            $query['3_percent_ft'] =  $_POST['3_percent_ft'][$val];
            $query['5_percent_ft'] =  $_POST['5_percent_ft'][$val];
            $query['5_percent_ewt'] =  $_POST['5_percent_ewt'][$val];
            $x[] = $query;
        }

        // return json_encode($_POST['selection']);
        // $query=Yii::$app->db->createCommand("SELECT * FROM raouds where id IN ('1','2')")->queryAll();
        // ob_start();
        // echo "<pre>";
        // var_dump($_POST['1_percent_ewt']);
        // echo "</pre>";
        return json_encode(['results' => $x]);
    }
    public function actionInsertDv()
    {


        if ($_POST) {
            $raoud_id = $_POST['raoud_id'];
            $nature_of_transaction_id = $_POST['nature_of_transaction'];
            $mrd_classification_id = $_POST['mrd_classification'];
            $reporting_period = $_POST['reporting_period'];
            $particular = $_POST['particular'];


            $transaction = Yii::$app->db->beginTransaction();


            try {
                $dv = new DvAucs();
                // $dv->raoud_id = $raoud_id;
                $dv->nature_of_transaction_id = $nature_of_transaction_id;
                $dv->mrd_classification_id = $mrd_classification_id;
                $dv->reporting_period = $reporting_period;
                $dv->particular = $particular;
                // $dv->one_percent_ewt = $one_percent_ewt;
                // $dv->two_percent_ewt = $two_percent_ewt;
                // $dv->five_percent_ewt = $five_percent_ewt;
                // $dv->three_percent_ft = $three_percent_ft;
                // $dv->five_percent_ft = $five_percent_ft;
                $dv->dv_number = $this->getDvNumber($reporting_period);

                if ($dv->validate()) {
                    if ($flag=$dv->save(false)) {
                        foreach ($_POST['raoud_id'] as $key => $val) {
                            $dv_entries = new DvAucsEntries();
                            $dv_entries->raoud_id = $val;
                            $dv_entries->dv_aucs_id = $dv->id;
                            $dv_entries->one_percent_ewt = $_POST['1_percent_ewt'][$key];
                            $dv_entries->two_percent_ewt = $_POST['2_percent_ewt'][$key];
                            $dv_entries->three_percent_ft = $_POST['3_percent_ft'][$key];
                            $dv_entries->five_percent_ft = $_POST['5_percent_ft'][$key];
                            $dv_entries->five_percent_ewt = $_POST['5_percent_ewt'][$key];
                            if ($dv_entries->save(false)){

                            }
                            // $dv_entries->total_withheld =$_POST['_percent_'];
                            // $dv_entries->tax_withheld =$_POST['_percent_'];

                        }
                    }
                } else {
                    return json_encode(['error' => $dv->errors]);
                }
                if ($flag) {

                    $transaction->commit();
                    // return $this->redirect(['view', 'id' => $model->id]);
                    return json_encode(['isSuccess' => 'success', 'id' => $dv->id]);
                }
            } catch (ErrorException $error) {

                $transaction->rollBack();

                return json_encode($error);
            }
        }
    }

    public function getDvNumber($reporting_period)
    {
        $latest_dv = (new \yii\db\Query())
            ->select('dv_number')
            ->from('dv_aucs')
            ->orderBy('id DESC')
            ->one();
        $dv_number = $reporting_period;

        if (!empty($latest_dv)) {
            $last_number = explode('-', $latest_dv['dv_number'])[2] + 1;
        } else {
            $last_number = 1;
        }
        $x = '';
        for ($i = strlen($last_number); $i < 4; $i++) {
            $x .= 0;
        }
        $dv_number .= '-' . $x . $last_number;

        // echo "<pre>";
        // var_dump($dv_number)
        // echo "</pre>";
        return $dv_number;
    }
    // public function actionYawa()
    // {

    // }
}
