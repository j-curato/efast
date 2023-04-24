<?php

namespace frontend\controllers;

use Yii;
use app\models\Saob;
use app\models\SaobSearch;
use DateTime;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * SaobController implements the CRUD actions for Saob model.
 */
class SaobController extends Controller
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
                    'index',
                    'generate'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'view',
                            'create',
                            'index',
                            'generate'
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
     * Lists all Saob models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaobSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Saob model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $data = $this->generateSaob(
            $model->from_reporting_period,
            $model->to_reporting_period,
            $model->mfo_pap_code_id == 0 ? 'all' : $model->mfo_pap_code_id,
            $model->document_recieve_id == 0 ? 'all' : $model->document_recieve_id,
            $model->book_id
        );
        return $this->render('view', [
            'model' => $model,
            'json_data' => json_encode($data)
        ]);
    }

    /**
     * Creates a new Saob model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Saob();

        if ($_POST) {
            if (Yii::$app->user->can('super-user')) {

                $from_reporting_period = $_POST['from_reporting_period'];
                $to_reporting_period = $_POST['to_reporting_period'];
                $book_id = $_POST['book_id'];
                $mfo_pap_code_id = $_POST['mfo_code'];
                $document_recieve_id = $_POST['document_recieve'];

                $model->from_reporting_period = $from_reporting_period;
                $model->to_reporting_period = $to_reporting_period;
                $model->book_id = $book_id;
                $model->mfo_pap_code_id = $mfo_pap_code_id;
                $model->document_recieve_id = $document_recieve_id;
                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Saob model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Saob model.
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
     * Finds the Saob model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Saob the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Saob::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGenerate()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $mfo_code = $_POST['mfo_code'];
            $document_recieve = $_POST['document_recieve'];
            $book_id = $_POST['book_id'];
            return json_encode($this->generateSaob(
                $from_reporting_period,
                $to_reporting_period,
                $mfo_code,
                $document_recieve,
                $book_id
            ));
        }
        return $this->render('saobs');
    }

    public function generateSaob(
        $from_reporting_period,
        $to_reporting_period,
        $mfo_code,
        $document_recieve,
        $book_id
    ) {



        $current_ors = new Query();
        $current_ors->select([

            "saob_rao.mfo_pap_code_id",
            "saob_rao.document_recieve_id",
            "saob_rao.book_id",
            "saob_rao.chart_of_account_id",
            "IFNULL(NULL,0) as prev_allotment",
            "SUM(saob_rao.allotment_amount) as current_allotment",
            "IFNULL(NULL,0) as prev_total_ors",
            'SUM(saob_rao.ors_amount) AS `current_total_ors`'
        ])
            ->from('saob_rao')

            ->where(" saob_rao.reporting_period >= :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
            ->andWhere("saob_rao.reporting_period <= :to_reporting_period", ['to_reporting_period' => $to_reporting_period])
            ->andWhere("saob_rao.book_id = :book_id", ['book_id' => $book_id]);
        if (strtolower($mfo_code) !== 'all') {

            $current_ors->andWhere("saob_rao.mfo_pap_code_id = :mfo_code", ['mfo_code' => $mfo_code]);
        }
        if (strtolower($document_recieve) !== 'all') {

            $current_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
        }
        $current_ors->groupBy(
            "saob_rao.mfo_pap_code_id,
            saob_rao.document_recieve_id,
            saob_rao.book_id,
            saob_rao.chart_of_account_id"
        );


        $prev_ors = new Query();
        $prev_ors->select([

            "saob_rao.mfo_pap_code_id",
            "saob_rao.document_recieve_id",
            "saob_rao.book_id",
            "saob_rao.chart_of_account_id",
            ' SUM(saob_rao.allotment_amount) AS `prev_total_allotment`',
            "IFNULL(NULL,0) as current_allotment",
            'SUM(saob_rao.ors_amount) AS `prev_total_ors`',
            "IFNULL(NULL,0) as current_total_ors",
        ])
            ->from('saob_rao')
            ->andWhere(" saob_rao.reporting_period < :from_reporting_period", ['from_reporting_period' => $from_reporting_period])
            ->andWhere(" saob_rao.reporting_period LIKE '2022%'")
            ->andWhere("saob_rao.book_id = :book_id", ['book_id' => $book_id]);
        if (strtolower($mfo_code) !== 'all') {

            $prev_ors->andWhere("saob_rao.mfo_pap_code_id = :mfo_code", ['mfo_code' => $mfo_code]);
        }
        if (strtolower($document_recieve) !== 'all') {
            $prev_ors->andWhere("saob_rao.document_recieve_id = :document", ['document' => $document_recieve]);
        }

        $prev_ors->groupBy(
            "saob_rao.mfo_pap_code_id,
            saob_rao.document_recieve_id,
            saob_rao.book_id,
            saob_rao.chart_of_account_id"
        );

        $sql_current_ors = $current_ors->createCommand()->getRawSql();
        $sql_prev_ors = $prev_ors->createCommand()->getRawSql();


        $detailed_query   = Yii::$app->db->createCommand("SELECT 
        mfo_pap_code.`name` as mfo_name,
        document_recieve.`name` as document_name,
        books.`name` as book_name,
        CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as account_title,
        major_accounts.`name` as major_name,
        sub_major_accounts.`name` as sub_major_name,
        SUM(q.prev_allotment) as prev_allotment,
        SUM(q.current_allotment) as current_allotment,
        SUM(q.prev_total_ors) as prev_total_ors,
        SUM(q.current_total_ors) as current_total_ors,
        SUM(q.prev_total_ors) + SUM(q.current_total_ors)  as to_date,
        (SUM(q.prev_allotment) + SUM(q.current_allotment)) - (SUM(q.prev_total_ors) + SUM(q.current_total_ors)) as balance
        FROM ( 
        $sql_current_ors 
        UNION ALL 
        $sql_prev_ors
        ) as q
        LEFT JOIN chart_of_accounts ON q.chart_of_account_id = chart_of_accounts.id
        LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
        LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
        LEFT JOIN mfo_pap_code ON q.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN document_recieve ON q.document_recieve_id = document_recieve.id
        LEFT JOIN books ON q.book_id = books.id
        GROUP BY q.mfo_pap_code_id,
        q.document_recieve_id,
        q.book_id,
        q.chart_of_account_id")
            ->queryAll();

        $conso_query   = Yii::$app->db->createCommand("SELECT 
        mfo_pap_code.`name` as mfo_name,
        document_recieve.`name` as document_name,
        SUM(q.prev_allotment) as prev_allotment,
        SUM(q.current_allotment) as current_allotment,
        SUM(q.prev_total_ors) as prev_total_ors,
        SUM(q.current_total_ors) as current_total_ors,
        SUM(q.prev_total_ors) + SUM(q.current_total_ors)  as to_date,
        (SUM(q.prev_allotment) + SUM(q.current_allotment)) - (SUM(q.prev_total_ors) + SUM(q.current_total_ors)) as balance
        FROM ( 
        $sql_current_ors 
        UNION ALL 
        $sql_prev_ors
        ) as q
        LEFT JOIN chart_of_accounts ON q.chart_of_account_id = chart_of_accounts.id
        LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
        LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
        LEFT JOIN mfo_pap_code ON q.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN document_recieve ON q.document_recieve_id = document_recieve.id
        LEFT JOIN books ON q.book_id = books.id
        GROUP BY q.mfo_pap_code_id,
        q.document_recieve_id")
            ->queryAll();
        $conso_per_major_query   = Yii::$app->db->createCommand("SELECT 
            major_accounts.`name` as major_name,
            document_recieve.`name` as document_name,
            SUM(q.prev_allotment) as prev_allotment,
            SUM(q.current_allotment) as current_allotment,
            SUM(q.prev_total_ors) as prev_total_ors,
            SUM(q.current_total_ors) as current_total_ors,
            SUM(q.prev_total_ors) + SUM(q.current_total_ors)  as to_date,
            (SUM(q.prev_allotment) + SUM(q.current_allotment)) - (SUM(q.prev_total_ors) + SUM(q.current_total_ors)) as balance
            FROM ( 
            $sql_current_ors 
            UNION ALL 
            $sql_prev_ors
            ) as q
            LEFT JOIN chart_of_accounts ON q.chart_of_account_id = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
            LEFT JOIN mfo_pap_code ON q.mfo_pap_code_id = mfo_pap_code.id
            LEFT JOIN document_recieve ON q.document_recieve_id = document_recieve.id
            LEFT JOIN books ON q.book_id = books.id
            GROUP BY major_accounts.id,
            q.document_recieve_id")
            ->queryAll();
        // return json_encode($detailed_query);
        $conso_per_major_query_final = ArrayHelper::index($conso_per_major_query, 'document_name', 'major_name');

        $result2 = ArrayHelper::index($detailed_query, null, [function ($element) {
            return $element['major_name'];
        }, 'sub_major_name',]);
        return ['result' => $result2, 'allotments' => [], 'conso_saob' => $conso_query, 'conso_per_major' => $conso_per_major_query_final];



        // END
        //     $query = Yii::$app->db->createCommand("SELECT
        //     mfo_pap_code.`name` as mfo_name,
        //     document_recieve.`name` as document_name,
        //     major_accounts.`name` as major_name,
        //     major_accounts.`object_code` as major_object_code,
        //     sub_major_accounts.`name` as sub_major_name,
        //     chart_of_accounts.uacs,
        //     chart_of_accounts.general_ledger,
        //     IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as allotment,
        //     IFNULL(prev.total_ors ,0)as prev_total_ors,
        //     IFNULL(current.total_ors,0) as current_total_ors,
        //     IFNULL(prev.total_ors ,0) + 
        //     IFNULL(current.total_ors,0) as ors_to_date,
        //     0 as prev_allotment

        //     FROM ($sql_current_ors) as current
        //     LEFT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
        //     AND current.document_recieve_id = prev.document_recieve_id
        //     AND current.book_id = prev.book_id
        //     AND current.chart_of_account_id = prev.chart_of_account_id)
        //     LEFT JOIN chart_of_accounts ON current.chart_of_account_id  = chart_of_accounts.id
        //     LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
        //     LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
        //     LEFT JOIN mfo_pap_code ON current.mfo_pap_code_id = mfo_pap_code.id
        //     LEFT JOIN document_recieve ON current.document_recieve_id = document_recieve.id
        //     LEFT JOIN books ON current.book_id  = books.id
        //     WHERE
        //     IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0 OR 
        //     IFNULL(prev.total_ors ,0) + 
        //     IFNULL(current.total_ors,0) >0
        //     UNION 
        //     SELECT
        //     mfo_pap_code.`name` as mfo_name,
        //     document_recieve.`name` as document_name,
        //     major_accounts.`name` as major_name,
        //     major_accounts.`object_code` as major_object_code,
        //     sub_major_accounts.`name` as sub_major_name,
        //     chart_of_accounts.uacs,
        //     chart_of_accounts.general_ledger,
        //    0 as allotment,
        //     IFNULL(prev.total_ors ,0)as prev_total_ors,
        //     IFNULL(current.total_ors,0) as current_total_ors,
        //     IFNULL(prev.total_ors ,0) + 
        //     IFNULL(current.total_ors,0) as ors_to_date,
        //     IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) as prev_allotment
        //     FROM ($sql_current_ors) as current
        //     RIGHT JOIN  ($sql_prev_ors) as prev ON (current.mfo_pap_code_id = prev.mfo_pap_code_id 
        //     AND current.document_recieve_id = prev.document_recieve_id
        //     AND current.book_id = prev.book_id
        //     AND current.chart_of_account_id = prev.chart_of_account_id)
        //     LEFT JOIN chart_of_accounts ON prev.chart_of_account_id  = chart_of_accounts.id
        //     LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
        //     LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id
        //     LEFT JOIN mfo_pap_code ON prev.mfo_pap_code_id = mfo_pap_code.id
        //     LEFT JOIN document_recieve ON prev.document_recieve_id = document_recieve.id
        //     LEFT JOIN books ON prev.book_id  = books.id
        //     WHERE
        //     IFNULL(current.total_allotment,0) + IFNULL(prev.total_allotment,0) >0 OR 
        //     IFNULL(prev.total_ors ,0) + 
        //     IFNULL(current.total_ors,0) >0

        //     ")->queryAll();

        //     $result = ArrayHelper::index($query, 'uacs', [function ($element) {
        //         return $element['mfo_name'];
        //     }, 'document_name']);


        //     $allotment_total = array();
        //     foreach ($result as $mfo => $val1) {
        //         foreach ($val1 as $document => $val2) {
        //             foreach ($val2 as $uacs => $val3) {
        //                 $allot = floatval($result[$mfo][$document][$uacs]['allotment']);
        //                 if ($allot != 0) {

        //                     $allotment_total[$mfo][$document][$uacs] = $allot;
        //                 }
        //             }
        //         }
        //     }


        //     $result2 = ArrayHelper::index($query, null, [function ($element) {
        //         return $element['major_name'];
        //     }, 'sub_major_name',]);
        //     // var_dump($result2);
        //     // die();
        //     $conso_saob = array();
        //     $sort_by_mfo_document = ArrayHelper::index($query, null, [function ($element) {
        //         return $element['mfo_name'];
        //     }, 'document_name']);

        //     foreach ($sort_by_mfo_document as $mfo => $mfo_val) {
        //         foreach ($mfo_val as $document => $document_val) {
        //             $to_date = round(array_sum(array_column($document_val, 'ors_to_date')), 2);
        //             $conso_saob[] =
        //                 [
        //                     'mfo_name' => $mfo,
        //                     'document' => $document,
        //                     'prev_allotment' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document], 'prev_allotment')), 2),
        //                     'current_allotment' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document], 'allotment')), 2),
        //                     'beginning_balance' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document], 'allotment')), 2),
        //                     'prev' => round(array_sum(array_column($sort_by_mfo_document[$mfo][$document], 'prev_total_ors')), 2),
        //                     'current' => round(array_sum(array_column($document_val, 'current_total_ors')), 2),
        //                     'to_date' => round(array_sum(array_column($document_val, 'ors_to_date')), 2),
        //                 ];
        //         }
        //     }


        //     return ['result' => $result2, 'allotments' => $allotment_total, 'conso_saob' => $conso_saob];
    }
}
