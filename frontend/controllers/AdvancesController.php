<?php

namespace frontend\controllers;

use Yii;
use app\models\Advances;
use app\models\AdvancesEntries;
use app\models\AdvancesEntriesSearch;
use app\models\AdvancesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvancesController implements the CRUD actions for Advances model.
 */
class AdvancesController extends Controller
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
     * Lists all Advances models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new AdvancesEntriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        // $searchModel = new AdvancesSearch();
        // $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advances model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $x = AdvancesEntries::findOne($id);
        $model = $this->findModel($x->advances_id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Advances model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advances();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Advances model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)

    {
        $x = AdvancesEntries::findOne($id);
        $model = $this->findModel($x->advances_id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Advances model.
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
     * Finds the Advances model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advances the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advances::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAddData()
    {


        if ($_POST) {
            $selected = $_POST['selection'];
            $params = [];
            $sql = \Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'cash_disbursement.id', $selected], $params);
            //$sql = some_id NOT IN (:qp0, :qp1, :qp2)
            //$params = [':qp0'=>1, ':qp1'=>2, ':qp2'=>3]
            $query = Yii::$app->db->createCommand("SELECT 
           cash_disbursement.id as cash_disbursement_id,
           cash_disbursement.issuance_date,
           cash_disbursement.check_or_ada_no,
           cash_disbursement.mode_of_payment,
           cash_disbursement.ada_number,
           dv_aucs.dv_number,
           dv_aucs.particular,
            dv_entries.total_disbursed,
            payee.account_name as payee

            FROM cash_disbursement,dv_aucs,payee,
            (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,dv_aucs_entries.dv_aucs_id 
            from dv_aucs_entries GROUP BY dv_aucs_entries.dv_aucs_id) as dv_entries
           WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
           AND dv_aucs.payee_id =payee.id
           AND dv_aucs.id =dv_entries.dv_aucs_id


           AND $sql", $params)->queryAll();


            return json_encode($query);
        }
    }

    public function actionInsertAdvances()
    {
        if ($_POST) {
            $update_id = !empty($_POST['update_id']) ? $_POST['update_id'] : '';

            $cash_disbursement_id = $_POST['cash_disbursement_id'];
            $report = $_POST['report'];
            $province = $_POST['province'];
            $particular = $_POST['particular'];
            $sub_account1_id = $_POST['sub_account1'];
            $amount = $_POST['amount'];
            
            $transaction = Yii::$app->db->beginTransaction();



            if (!empty($update_id)) {
                $advances = Advances::findOne($update_id);
                foreach ($advances->advancesEntries as $val) {
                    $val->delete();
                }
            } else {
                $advances = new Advances();
                $advances->nft_number = $this->getNftNumber();
            }


            $advances->report_type = $report;
            $advances->province = $province;
            $advances->particular = $particular;
            if ($advances->validate()) {
                if ($flag = $advances->save(false)) {

                    foreach ($cash_disbursement_id as $index => $val) {
                        $ad_entry = new AdvancesEntries();
                        $ad_entry->advances_id = $advances->id;
                        $ad_entry->cash_disbursement_id = $cash_disbursement_id[$index];
                        $ad_entry->sub_account1_id = $sub_account1_id[$index];
                        $ad_entry->amount = floatval(preg_replace('/[^\d.]/', '', $amount[$index]));
                        if ($ad_entry->validate()) {

                            if ($ad_entry->save(false)) {
                            }
                        } else {
                            return json_encode(['isSuccess' => false, 'error' => $ad_entry->errors]);
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return json_encode(['isSuccess' => true]);
                }
            } else {

                return json_encode(['isSuccess' => false, 'error' => $advances->errors]);
            }
        }
    }
    public function actionUpdateAdvances()
    {
        if ($_POST) {
            $update_id = $_POST['update_id'];

            $query = (new \yii\db\Query())
                ->select([
                    'dv_aucs.dv_number',
                    'cash_disbursement.id as cash_disbursement_id',
                    'cash_disbursement.mode_of_payment',
                    'cash_disbursement.check_or_ada_no',
                    'cash_disbursement.ada_number',
                    'cash_disbursement.issuance_date',
                    'payee.account_name as payee',
                    'dv_aucs.particular',
                    'advances.report_type',
                    'advances.particular',
                    'advances.province',
                    'advances_entries.amount',
                    'advances_entries.sub_account1_id'
                ])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->join('LEFT JOIN', 'cash_disbursement', 'advances_entries.cash_disbursement_id = cash_disbursement.id')
                ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
                ->join('LEFT JOIN', 'payee', 'dv_aucs.payee_id = payee.id')
                ->where('advances_entries.advances_id =:advances_id', ['advances_id' => $update_id])
                ->all();

            return json_encode($query);
        }
    }
    public function getNftNumber()
    {
        $q = Advances::find()->orderBy('id DESC')->one();
        $num = 0;
        if (!empty($q)) {
            $x = explode('-', $q->nft_number);
            $num = $x[1] + 1;
        } else {
            $num = 1;
        }

        $y = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $y .= 0;
        }
        $y .= $num;


        return date('Y') . '-' . $y;
    }

    public function actionGetAllAdvances()
    {
        $res = (new \yii\db\Query())
            ->select("*")
            ->from('advances')
            ->all();
        return json_encode($res);
    }
}

// $var = floatval(preg_replace('/[^\d.]/', '', $var));
