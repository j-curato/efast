<?php

namespace frontend\controllers;

use app\models\AdvancesEntries;
use app\models\ChartOfAccounts;
use Yii;
use app\models\Rod;
use app\models\RodSearch;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RodController implements the CRUD actions for Rod model.
 */
class RodController extends Controller
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
                    'update',
                    'delete',
                    'view',
                    'create',
                    'insert-rod',
                    'get-rod',
                    'search-fund-source',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'view',
                            'create',
                            'insert-rod',
                            'get-rod',
                            'search-fund-source',
                        ],
                        'allow' => true,
                        'roles' => ['@']
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
     * Lists all Rod models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RodSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Rod model.
     * @param string $id
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
     * Creates a new Rod model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rod();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rod_number]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Rod model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->rod_number]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Rod model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rod model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Rod the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rod::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getRodNumber($province)
    {
        $year = date('Y');
        $query = Yii::$app->db->createCommand(
            "SELECT substring_index(rod_number,'-',-1) as id_number 
            FROM rod WHERE rod.province = :province  ORDER BY id_number DESC LIMIT 1 "
        )
            ->bindValue(':province', $province)
            ->queryScalar();
        $num = empty($query) ? 1 : $query + 1;
        $q = substr(str_repeat(0, 4) . $num, -4);
        $rod_number = strtoupper($province) . '-' . $year . '-' . $q;
        return $rod_number;
    }
    public function actionInsertRod()
    {
        if ($_POST) {

            $fund_source = $_POST['fund_source'];
            $province = empty($_POST['province']) ? '' : $_POST['province'];
            $rod_number = empty($_POST['rod_number']) ? '' : $_POST['rod_number'];
            $user_province = strtolower(Yii::$app->user->identity->province);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'
            ) {
                $province = $user_province;
            }
            $transaction = Yii::$app->db->beginTransaction();

            if (!empty($rod_number)) {
                $rod = Rod::findOne($rod_number);
                Yii::$app->db->createCommand('DELETE FROM rod_entries WHERE rod_number =:rod_number')
                    ->bindValue(':rod_number', $rod_number)
                    ->query();
            } else {

                $rod = new Rod();
                $rod->rod_number = $this->getRodNumber($province);
            }
            try {


                $rod->province = $province;
                if ($rod->validate()) {
                    if ($flag = $rod->save(false)) {

                        foreach ($fund_source as $index => $val) {
                            Yii::$app->db->createCommand("INSERT INTO rod_entries (rod_number,advances_entries_id)
                            VALUES (:rod_number,:advances_entries_id)
                            ")
                                ->bindValue(':rod_number', $rod->rod_number)
                                ->bindValue(':advances_entries_id', $val)
                                ->query();
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return json_encode(['isSuccess' => true, 'id' => $rod->rod_number]);
                    }
                } else {
                    $transaction->rollback();
                    return json_encode(['isSuccess' => false, 'error' => $rod->errors]);
                }
            } catch (ErrorException $e) {
                $transaction->rollback();
            }
        }
    }

    public function actionGetRod()
    {
        if ($_POST) {

            $province = !empty($_POST['province']) ? $_POST['province'] : '';
            $user_province = strtolower(Yii::$app->user->identity->province);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'
            ) {
                $province = $user_province;
            }

            $fund_source = !empty($_POST['fund_source']) ? $_POST['fund_source'] : '';

            $rod_number = !empty($_POST['rod_number']) ? $_POST['rod_number'] : '';
            $action_type = !empty($_POST['action_type']) ? $_POST['action_type'] : '';
            // return json_encode($fund_source);
            $db = Yii::$app->db;
            if (!empty($rod_number) &&  $action_type === 'view') {
                $q = $db->createCommand("SELECT advances_entries_id FROM rod_entries WHERE rod_entries.rod_number =:rod_number")
                    ->bindValue(':rod_number', $rod_number)
                    ->queryAll();
                $prov = $db->createCommand('SELECT province FROM rod WHERE rod_number = :rod_number')
                    ->bindValue(':rod_number', $rod_number)
                    ->queryOne();
                $province = $prov['province'];
                $fund_source = array_column($q, 'advances_entries_id');
            }
            $params = [];
            $sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'liquidation_entries.advances_entries_id', $fund_source], $params);
            $query1 = (new \yii\db\Query())
                ->select(["
                        liquidation.check_date,
                        liquidation.dv_number,
                        IFNULL(po_responsibility_center.`name`,'') reponsibility_center_name,
                        IFNULL(po_transaction.payee,liquidation.payee) as payee,
                        liquidation_entries.withdrawals,
                        advances_entries.fund_source
            "])
                ->from('liquidation_entries')
                ->join('LEFT JOIN', 'liquidation', 'liquidation_entries.liquidation_id = liquidation.id')
                ->join('LEFT JOIN', 'advances_entries', 'liquidation_entries.advances_entries_id = advances_entries.id')
                ->join('LEFT JOIN', 'po_transaction', 'liquidation.po_transaction_id = po_transaction.id')
                ->join('LEFT JOIN', 'po_responsibility_center', 'po_transaction.po_responsibility_center_id = po_responsibility_center.id')
                ->where('liquidation.province = :province', ['province' => $province])
                ->andWhere("$sql", $params)
                ->orderBy('liquidation.check_number DESC')
                ->all();

            $params2 = [];
            $fund_source_sql = Yii::$app->db->getQueryBuilder()->buildCondition(['IN', 'advances_entries.id', $fund_source], $params2);
            $fund_source_query = (new \yii\db\Query())
                ->select(["
                 advances_entries.fund_source,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.issuance_date,
                advances_entries.amount,
                liquidation_total.total_withdrawals,
                advances_entries.amount - IFNULL(liquidation_total.total_withdrawals,0) as balance
            "])
                ->from('advances_entries')
                ->join('LEFT JOIN', 'cash_disbursement', 'advances_entries.cash_disbursement_id = cash_disbursement.id')
                ->join('LEFT JOIN', ' (SELECT SUM(liquidation_entries.withdrawals) as total_withdrawals,
            liquidation_entries.advances_entries_id
            FROM liquidation_entries
            GROUP BY liquidation_entries.advances_entries_id
            ) as liquidation_total', 'advances_entries.id = liquidation_total.advances_entries_id')
                ->where("$fund_source_sql", $params)
                ->all();


            $group_liquidation = [];
            $i = 0;
            $x = 0;
            foreach ($query1 as $index => $val) {
                if ($i == 18) {

                    $x++;
                    $i = 0;
                }
                $group_liquidation[$x][$i] = [
                    'check_date' => $val['check_date'],
                    'dv_number' => $val['dv_number'],
                    'reponsibility_center_name' => $val['reponsibility_center_name'],
                    'payee' => $val['payee'],
                    'withdrawals' => $val['withdrawals'],
                    'fund_source' => $val['fund_source']
                ];



                $i++;
            }
            return json_encode([
                'liquidations' => $query1,
                'conso_fund_source' => $fund_source_query,
                'group_liquidation' => $group_liquidation
            ]);
        }
        return $this->render('_form');
    }
    public function actionSearchFundSource($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('advances_entries.id, advances_entries.fund_source AS text')
                ->from('advances_entries')
                ->join('LEFT JOIN', 'advances', 'advances_entries.advances_id = advances.id')
                ->where(['like', 'advances_entries.fund_source', $q]);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'
            ) {
                $query->andWhere('advances.province = :province', ['province' => $user_province]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => AdvancesEntries::find($id)->fund_source];
        }
        return $out;
    }
}
