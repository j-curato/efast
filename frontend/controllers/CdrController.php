<?php

namespace frontend\controllers;

use app\models\Books;
use Yii;
use app\models\Cdr;
use app\models\CdrSearch;
use app\models\LiquidationReportingPeriod;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CdrController implements the CRUD actions for Cdr model.
 */
class CdrController extends Controller
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
                    'create',
                    'view',
                    'update',
                    'delete',
                    'cdr',
                    'cdr-final',
                    'insert-cdr',
                    'export',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'update',
                            'delete',
                            'cdr-final',
                            'insert-cdr'
                        ],
                        'allow' => true,
                        'roles' => ['create_cdr']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'cdr',
                            'create',
                            'export',

                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],
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
     * Lists all Cdr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CdrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cdr model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }
        $query = (new \yii\db\Query())
            ->select(
                'check_date,
            check_number,
            particular,
            amount,
            withdrawals,
            gl_object_code,
            gl_account_title,
            reporting_period,
            vat_nonvat,
            expanded_tax
        '
            )
            ->from('advances_liquidation')
            ->where('reporting_period <=:reporting_period', ['reporting_period' => $model->reporting_period])
            ->andWhere('book_name =:book_name', ['book_name' => $model->book_name])
            ->andWhere('province LIKE :province', ['province' => $model->province])
            ->andWhere('advances_type LIKE :advances_type', ['advances_type' => $model->report_type])
            ->orderBy('reporting_period,check_date')
            ->all();
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => '',
            'reporting_period' => '',
            'province' => '',
            'consolidated' => '',
            'book' => '',
            'cdr' => '',
        ]);
    }

    /**
     * Creates a new Cdr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cdr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cdr model.
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
            'dataProvider' => '',
            'reporting_period' => '',
            'province' => '',
            'consolidated' => '',
            'book' => '',
            'cdr' => '',
        ]);
    }

    /**
     * Deletes an existing Cdr model.
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
     * Finds the Cdr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cdr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cdr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionCdr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $bank_account_id  = $_POST['bank_account_id'];
            $report_type = $_POST['report_type'];


            $province = Yii::$app->db->createCommand("SELECT province FROM bank_account WHERE id = :id")
                ->bindValue(':id', $bank_account_id)
                ->queryScalar();
            $query = Yii::$app->db->createCommand("SELECT 
                        liquidation.check_date,
                        liquidation.check_number,
                       po_transaction.particular,
                        0 as amount,
                        IFNULL(liq.withdrawals,0) as withdrawals,
                        IFNULL(liq.vat_nonvat,0)as vat_nonvat,
                        IFNULL(liq.expanded_tax,0)as expanded_tax,
                        liq.reporting_period,
                        accounting_codes.object_code as gl_object_code,
                        accounting_codes.account_title as gl_account_title
                        FROM (	
                            SELECT
                            liquidation.id,
                            IFNULL(SUM(liquidation_entries.withdrawals),0) as withdrawals,
                            IFNULL(SUM(liquidation_entries.vat_nonvat),0)as vat_nonvat,
                            IFNULL(SUM(liquidation_entries.expanded_tax),0)as expanded_tax,
                            liquidation_entries.reporting_period,
                            (CASE  
                                WHEN liquidation_entries.new_object_code IS  NOT NULL THEN liquidation_entries.new_object_code
                                WHEN  liquidation_entries.new_chart_of_account_id IS  NOT NULL THEN  new_chart.uacs
                                ELSE orig_chart.uacs
                                END) as  gl_object_code
                            FROM liquidation_entries
                            LEFT JOIN chart_of_accounts as orig_chart ON liquidation_entries.chart_of_account_id = orig_chart.id
                            LEFT JOIN chart_of_accounts as new_chart ON liquidation_entries.new_chart_of_account_id = new_chart.id
                            LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                            LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id  = advances_entries.id
                            LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id =  cash_disbursement.id
                            LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
                            LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
                            WHERE liquidation_entries.reporting_period = :reporting_period
                            AND liquidation.province = :province
                            AND check_range.bank_account_id = :bank_account_id
                            AND advances_entries.report_type = :report_type
                            GROUP BY
                            liquidation_entries.reporting_period,
                            liquidation.id,
                            (CASE  
                                WHEN liquidation_entries.new_object_code IS  NOT NULL THEN liquidation_entries.new_object_code
                                WHEN  liquidation_entries.new_chart_of_account_id IS  NOT NULL THEN  new_chart.uacs
                                ELSE orig_chart.uacs
                                END)
                        ) as liq
                        LEFT JOIN liquidation ON liq.id = liquidation.id
                        LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
                        LEFT JOIN accounting_codes ON liq.gl_object_code = accounting_codes.object_code

                        UNION ALL

                      
  SELECT 
                        cash.check_date,
                        cash.check_number,
                        dv_aucs.particular,
                        advances_entries.amount ,
                        0 as withdrawals,
                        0 as vat_nonvat,
                        0 as expanded_tax,
                        advances_entries.reporting_period,
                        '' as gl_object_code,
                        '' as gl_account_title
                        FROM 
                        advances_entries
                        LEFT JOIN advances ON advances_entries.advances_id = advances.id
                        LEFT JOIN dv_aucs ON advances.dv_aucs_id = dv_aucs.id
LEFT JOIN  (SELECT 
            cash_disbursement_items.fk_dv_aucs_id,
            cash_disbursement.check_or_ada_no as check_number,
            cash_disbursement.ada_number,
            cash_disbursement.issuance_date as check_date,
            mode_of_payments.`name` as mode_of_payment,
            books.`name` as book_name
            FROM 
            cash_disbursement
            JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
            LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
            LEFT JOIN books ON cash_disbursement.book_id = books.id
            WHERE 
            cash_disbursement_items.is_deleted = 0
            AND cash_disbursement.is_cancelled = 0
            AND NOT EXISTS (SELECT * FROM cash_disbursement c WHERE c.is_cancelled = 1 AND c.parent_disbursement = cash_disbursement.id)
) cash ON dv_aucs.id = cash.fk_dv_aucs_id


                        WHERE advances_entries.reporting_period = :reporting_period
                        AND advances.province = :province
                        AND advances_entries.report_type =:report_type 
                        AND advances_entries.is_deleted NOT IN (1,9)
                        AND advances.bank_account_id = :bank_account_id
                        AND dv_aucs.is_cancelled = 0
            
 
            ")->bindValue(':reporting_period',  $reporting_period)
                ->bindValue(':province', $province)
                ->bindValue(':report_type', $report_type)
                ->bindValue(':bank_account_id', $bank_account_id)
                ->queryAll();

            $advances_balance = 0;
            $liquidation_balance = 0;

            $advances_balance = Yii::$app->db->createCommand("SELECT ROUND(IFNULL(SUM(balance),0),2)as balance
                    FROM cdr_advances_balance
                    WHERE reporting_period <:reporting_period
                    AND province LIKE :province
                    AND report_type LIKE :report_type
                    AND bank_account_id = :bank_account_id
                    ")
                ->bindValue(':reporting_period',  $reporting_period)
                ->bindValue(':province', $province)
                ->bindValue(':report_type', $report_type)
                ->bindValue(':bank_account_id', $bank_account_id)
                ->queryScalar();
            $liquidation_balance = Yii::$app->db->createCommand("SELECT ROUND(IFNULL(SUM(total_withdrawals),0),2)as balance
                    FROM cdr_liquidation_balance
                    WHERE reporting_period <:reporting_period
                    AND province LIKE :province
                    AND report_type LIKE :report_type
                    AND bank_account_id = :bank_account_id")
                ->bindValue(':reporting_period',  $reporting_period)
                ->bindValue(':province', $province)
                ->bindValue(':report_type', $report_type)
                ->bindValue(':bank_account_id', $bank_account_id)
                ->queryScalar();
            $advances_balance_con = !empty($advances_balance) ? $advances_balance : 0;
            $liquidation_balance_con = !empty($liquidation_balance) ? $liquidation_balance : 0;
            $balance  = $advances_balance - $liquidation_balance;


            $result = ArrayHelper::index($query, null, [function ($element) {
                return $element['reporting_period'];
            }, 'gl_object_code']);
            // ob_clean();
            // echo "<pre>";
            // var_dump($result);
            // echo "</pre>";

            // return ob_get_clean();
            $consolidated = [];
            if (!empty($result[$reporting_period])) {

                foreach ($result[$reporting_period] as $key => $res) {
                    $gross_amount = 0;
                    $vat_nonvat = 0;
                    $expanded_tax = 0;
                    $account_title =  $res[0]['gl_account_title'];

                    foreach ($res as $data) {
                        $gross_amount += (float)$data['withdrawals'];
                        $vat_nonvat += (float)$data['vat_nonvat'];
                        $expanded_tax += (float)$data['expanded_tax'];
                    }

                    $consolidated[] = [
                        'object_code' => $key,
                        'account_title' => $account_title,
                        'gross_amount' => round($gross_amount, 2),
                        'vat_nonvat' => round($vat_nonvat, 2),
                        'expanded_tax' => round($expanded_tax, 2),
                        'gross_expense' => round($gross_amount + $vat_nonvat + $expanded_tax, 2)
                    ];
                }
            }
            $prov = [];
            $municipality = '';
            $officer = '';
            $location = '';

            $prov = Yii::$app->memem->cibrCdrHeader($province);
            $municipality = $prov['province'];
            $officer = $prov['officer'];
            $location = $prov['location'];
            // $q = array_sum($consolidated['gross_amount']);
            // return (['res' => $q]);
            // ob_clean();
            // echo "<pre>";
            // var_dump(
            //     $balance

            // );
            // echo "</pre>";
            // return ob_get_clean();
            // $book_name = Yii::$app->db->createCommand('SELECT books.name FROM books where books.id =:id')
            //     ->bindValue(':id', $book)->queryOne();
            $book_name = '';
            return json_encode([
                'cdr' => $query,
                'consolidate' => $consolidated,
                'book' => $book_name,
                'reporting_period' => date('F, Y', strtotime($reporting_period)),
                'municipality' => $municipality,
                'officer' => $officer,
                'location' => $location,
                'balance' => floatval($balance),
                'advance_type' => $report_type

            ]);

            // return $this->render('update', [
            //     'dataProvider' => $query,
            //     'reporting_period' => $reporting_period,
            //     'province' => $province,
            //     'consolidated' => $consolidated,
            //     'book' => $book_name,
            //     'cdr' => $cdr,
            //     'model' => ''


            // ]);
        } else {

            return $this->render('update', []);
        }
    }
    public function actionCdrFinal()
    {
        if ($_POST) {
            $id = $_POST['id'];
            try {
                $cdr = Cdr::findOne($id);
                $cdr->is_final = $cdr->is_final === 0 ? true : false;
                $cdr->serial_number = $this->getSerialNumber($cdr->reporting_period, $cdr->report_type,  $cdr->province);
                if ($cdr->save(false)) {
                    $r = Yii::$app->db->createCommand('SELECT reporting_period FROM liquidation_reporting_period 
                    WHERE reporting_period = :reporting_period ')
                        ->bindValue(':reporting_period', $cdr->reporting_period)
                        ->queryOne();
                    if (empty($r)) {

                        $liq_reporting_period = new LiquidationReportingPeriod();
                        $liq_reporting_period->reporting_period = $cdr->reporting_period;
                        $liq_reporting_period->province = $cdr->province;
                        if ($liq_reporting_period->save(false)) {
                        }
                    }

                    return json_encode(['isScuccess' => true, 'message' => 'success']);
                } else {
                }
            } catch (ErrorException $e) {
                return json_encode(['isScuccess' => false, 'message' => $e->getMessage()]);
            }
        }
    }
    public function actionInsertCdr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $bank_account_id = $_POST['bank_account_id'];
            // $book_name = $_POST['book'];
            $report_type = $_POST['report_type'];
            $query = (new \yii\db\Query())
                ->select('id')
                ->from('cdr')
                ->where('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                ->andWhere('fk_bank_account_id LIKE :fk_bank_account_id', ['fk_bank_account_id' => $bank_account_id])
                ->andWhere('report_type LIKE :report_type', ['report_type' => $report_type])
                ->one();
            if (!empty($query)) {
                return json_encode(['isSuccess' => false, 'error' => 'na save na ']);
            }
            $province = Yii::$app->db->createCommand("SELECT province FROM bank_account WHERE id =:id")
                ->bindValue(':id', $bank_account_id)
                ->queryScalar();
            $cdr = new Cdr();
            $cdr->reporting_period = $reporting_period;
            $cdr->fk_bank_account_id = $bank_account_id;
            $cdr->report_type = $report_type;
            $cdr->province = $province;

            if ($cdr->validate()) {
                if ($cdr->save(false)) {
                    return json_encode(['isSuccess' => true, 'error' => 'Success', 'id' => $cdr->id]);
                }
            } else {
                return json_encode(['isSuccess' => false, 'error' => $cdr->errors]);
            }
        }
    }
    public function getSerialNumber($reporting_period, $report_type, $province)
    {
        // $report_type = 'Advances for Operating Expenses';
        // $province = 'ADN';
        // $reporting_period = '2021-02';
        // $book_name = Yii::$app->db->createCommand('SELECT books.name FROM books where id =:id')
        //     ->bindValue(':id', $book_id)
        //     ->queryOne();
        $serial_number = 'CDR ';
        // if ($report_type === 'Advances for Operating Expenses') {
        //     $type = 'OPEX';
        // } else if ($report_type === 'Advances to Special Disbursing Officer') {
        //     $type = 'SDO';
        // }

        $serial_number = $report_type . '-' . strtoupper($province) . '-' . $reporting_period;

        return $serial_number;
    }
    public function actionExport()
    {

        if ($_POST) {
            $from_reporting_period = '2021-12';
            $to_reporting_period = '2021-12';



            $province = 'adn';


            // $province = strtolower(Yii::$app->user->identity->province);
            $q = (new \yii\db\Query())
                ->select(["*",])
                ->from('liquidation_entries_view')
                ->where(
                    'liquidation_entries_view.reporting_period BETWEEN :from_reporting_period AND :to_reporting_period',

                    ['from_reporting_period' => $from_reporting_period, 'to_reporting_period' => $to_reporting_period]
                );

            if (
                $province === 'adn' ||
                $province === 'ads' ||
                $province === 'sdn' ||
                $province === 'sds' ||
                $province === 'pdi'
            ) {
                $q->andWhere('province = :province', ['province' => $province]);
            }

            $query = $q->all();

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->setAutoFilter('A1:P1');
            $sheet->setCellValue('A1', "ID");
            $sheet->setCellValue('B1', "Reporting Period");
            $sheet->setCellValue('C1', "DV Number");
            $sheet->setCellValue('D1', "Check Date");
            $sheet->setCellValue('E1', "Check Number");
            $sheet->setCellValue('F1', "Fund Source");
            $sheet->setCellValue('G1', 'Particular');
            $sheet->setCellValue('H1', 'Payee');
            $sheet->setCellValue('I1', 'Object Code');
            $sheet->setCellValue('J1', 'Account Title');
            $sheet->setCellValue('K1', 'Withdrawals');
            $sheet->setCellValue('L1', 'Vat-NonVat');
            $sheet->setCellValue('M1', 'Expanded Tax');
            $sheet->setCellValue('N1', 'Liquidation Damage');
            $sheet->setCellValue('O1', 'Gross Payment');
            $sheet->setCellValue('P1', 'Province');
            $sheet->setCellValue('Q1', 'Original Reporting Period');


            $x = 7;
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );


            $row = 2;

            foreach ($query  as  $val) {

                $sheet->setCellValueByColumnAndRow(1, $row,  $val['id']);
                $sheet->setCellValueByColumnAndRow(2, $row,  $val['reporting_period']);
                $sheet->setCellValueByColumnAndRow(3, $row,  $val['dv_number']);
                $sheet->setCellValueByColumnAndRow(4, $row,  $val['check_date']);
                $sheet->setCellValueByColumnAndRow(5, $row,  $val['check_number']);
                $sheet->setCellValueByColumnAndRow(6, $row,  $val['fund_source']);
                $sheet->setCellValueByColumnAndRow(7, $row,  $val['particular']);
                $sheet->setCellValueByColumnAndRow(8, $row,  $val['payee']);
                $sheet->setCellValueByColumnAndRow(9, $row,  $val['object_code']);
                $sheet->setCellValueByColumnAndRow(10, $row,  $val['account_title']);
                $sheet->setCellValueByColumnAndRow(11, $row,  $val['withdrawals']);
                $sheet->setCellValueByColumnAndRow(12, $row,  $val['vat_nonvat']);
                $sheet->setCellValueByColumnAndRow(13, $row,  $val['expanded_tax']);
                $sheet->setCellValueByColumnAndRow(14, $row,  $val['liquidation_damage']);
                $sheet->setCellValueByColumnAndRow(15, $row,  $val['gross_payment']);
                $sheet->setCellValueByColumnAndRow(16, $row,  $val['province']);
                $sheet->setCellValueByColumnAndRow(17, $row,  $val['orig_reporting_period']);

                $row++;
            }

            date_default_timezone_set('Asia/Manila');
            // return date('l jS \of F Y h:i:s A');
            $id = uniqid() . '_' . date('Y-m-d h A');
            $file_name = "liquidation_$id.xlsx";
            // header('Content-Type: application/vnd.ms-excel');
            // header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            // header('Content-Transfer-Encoding: binary');
            // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            // header('Pragma: public'); // HTTP/1.0
            // echo readfile($file);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            $path = Yii::getAlias('@webroot') . '/transaction';

            $file = $path . "\liquidation_$id.xlsx";
            $file2 = "transaction/liquidation_$id.xlsx";
            $writer->save($file);
            // return ob_get_clean();
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            // echo "<script>window.open('$file2','_self')</script>";

            return json_encode($file2);
            // }
            // Yii::$app->response->xSendFile($path);

            // echo "/afms/transaction/liquidation.xlsx";
            // flush(); // Flush system output buffer

            // echo "<script> window.location.href = '$file';</script>";
            // echo "<script>window.open($file2)</script>";

            exit();
            // return json_encode(['res' => "transaction\ckdj_excel_$id.xlsx"]);
            // return json_encode($file);
            // exit;
        } else {
            return 'qweqweqw';
        }
    }
    public function actionSearchCdr($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            // $out['results'] = ['id' => $id, 'text' => Payee::findOne($id)->account_name];
        } else if (!is_null($q)) {
            // $qu
            // $query = (new yii\db\Query);
            // $query->select('payee.id, payee.account_name AS text')
            //     ->from('payee')
            //     ->where(['like', 'payee.account_name', $q])
            //     ->andWhere('payee.isEnable = 1');

            // $command = $query->createCommand();
            // $data = $command->queryAll();
            $data = Yii::$app->db->createCommand("SELECT cdr.id, cdr.serial_number as `text` FROM cdr WHERE cdr.serial_number LIKE :cdr")
                ->bindValue(':cdr', '%' . $q . '%')->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
    public function actionCdrEntries()
    {

        if (Yii::$app->request->isPost) {

            $cdr_details = Yii::$app->db->createCommand("SELECT 
                        reporting_period,
                        report_type,
                        fk_bank_account_id
                        FROM cdr
                        WHERE 
                        cdr.id = :id
            ")->bindValue(':id', $_POST['id'])
                ->queryOne();
            if (!empty($cdr_details)) {

                $query = Yii::$app->db->createCommand("SELECT 
                
                accounting_codes.account_title,
                cdr_conso.new_object_code as object_code,
                cdr_conso.withdrawals +cdr_conso.vat_nonvat +cdr_conso.expanded_tax as debit,
                0 as credit
                FROM  
                (
                SELECT 
                
                liquidation_entries.new_object_code,
                
                SUM(liquidation_entries.withdrawals) as withdrawals,
                SUM(liquidation_entries.vat_nonvat) as vat_nonvat,
                SUM(liquidation_entries.expanded_tax) as expanded_tax
                
                FROM liquidation_entries
                
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                
                WHERE
                liquidation_entries.reporting_period = :reporting_period
                   AND check_range.bank_account_id =:bank_account_id
                AND advances_entries.report_type = :report_type
                GROUP BY
                liquidation_entries.new_object_code
                ) as cdr_conso
                LEFT JOIN accounting_codes ON cdr_conso.new_object_code = accounting_codes.object_code
                UNION ALL 
                SELECT 

                '' as object_code,
                '' as account_title,
                0 as debit,
                SUM(liquidation_entries.expanded_tax) +
                SUM(liquidation_entries.vat_nonvat) as credit
                FROM liquidation_entries
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id

                WHERE
                liquidation_entries.reporting_period = :reporting_period
                AND check_range.bank_account_id =:bank_account_id
                AND advances_entries.report_type = :report_type
                UNION ALL 
                SELECT 
                '' as object_code,
                '' as account_title,
                0 as debit,
                SUM(liquidation_entries.withdrawals) as credit
                FROM liquidation_entries
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                WHERE
                liquidation_entries.reporting_period = :reporting_period
                AND check_range.bank_account_id =:bank_account_id
                AND advances_entries.report_type = :report_type
                
                ")
                    ->bindValue(':reporting_period', $cdr_details['reporting_period'])
                    ->bindValue(':report_type', $cdr_details['report_type'])
                    ->bindValue(':bank_account_id', $cdr_details['fk_bank_account_id'])
                    ->queryAll();

                return json_encode($query);
            }
        } else {
            return json_encode('qwe');
        }
    }
}
