<?php

namespace frontend\controllers;

use Yii;
use app\models\Cibr;
use app\models\CibrSearch;
use app\models\LiquidationReportingPeriod;
use DateTime;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CibrController implements the CRUD actions for Cibr model.
 */
class CibrController extends Controller
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
                    'insert-cibr',
                    'final',
                    'get-cibr',
                    'add-link',
                    'export',
                ],
                'rules' => [
                    [
                        'actions' => [

                            'update',
                            'delete',
                            'insert-cibr',
                            'final'
                        ],
                        'allow' => true,
                        'roles' => ['create_cibr']
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'get-cibr',
                            'add-link',
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
     * Lists all Cibr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CibrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cibr model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)

    {
        $model = $this->findModel($id);
        $dataProvider = Yii::$app->db->createCommand('CALL cibr_function(:province,:reporting_period,:bank_account_id)')
            ->bindValue(':reporting_period', $model->reporting_period)
            ->bindValue(':province', $model->province)
            ->bindValue(':bank_account_id',   $model->bank_account_id)
            ->queryAll();

        $bank_account_data = Yii::$app->db->createCommand("SELECT * FROM bank_account WHERE id = :id")
            ->bindValue(':id',  $model->bank_account_id)
            ->queryOne();
        $province =  $bank_account_data['province'];

        $q1 = Yii::$app->db->createCommand("SELECT 
                SUM(total) as total
             from cibr_advances_balances
             where reporting_period <:reporting_period 
             AND province LIKE :province
            
              ")
            ->bindValue(':reporting_period',   $model->reporting_period)
            ->bindValue(':province',   $province)
            ->queryScalar();

        $q2 = Yii::$app->db->createCommand("SELECT 
                    SUM(total_withdrawals) as total_withdrawal
                from cibr_liquidation_balances
                where reporting_period <:reporting_period 
                AND province LIKE :province
                 ")
            ->bindValue(':reporting_period',   $model->reporting_period)
            ->bindValue(':province',   $province)
            ->queryScalar();
        $balance = $q1 - $q2;

        ArrayHelper::multisort($dataProvider, ['check_number',], [SORT_ASC]);

        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'province' =>   $province,
            'reporting_period' =>   $model->reporting_period,
            'book' =>   $model->book_name,
            'model' => $model,
            'beginning_balance' => $balance

        ]);
    }

    /**
     * Creates a new Cibr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Cibr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cibr model.
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
     * Deletes an existing Cibr model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        Yii::$app->db->createCommand("DELETE FROM liquidation_reporting_period WHERE reporting_period =:reporting_period
        AND province =:province
        ")
            ->bindValue(':reporting_period', $model->reporting_period)
            ->bindValue(':province', $model->province)
            ->query();

        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Cibr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cibr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cibr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested data  does not exist.');
    }
    public function actionInsertCibr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $bank_account_id = $_POST['bank_account_id'];
            $cibr_id = !empty($_POST['id']) ? $_POST['id'] : '';


            $q = (new \yii\db\Query())
                ->select('id')
                ->from('cibr')
                ->andWhere('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
                ->andWhere('bank_account_id =:bank_account_id', ['bank_account_id' => $bank_account_id])
                ->one();
            if (!empty($q)) {
                return json_encode(['isSuccess' => false, 'error' => 'CIBR already Exist']);
            }
            if (!empty($cibr_id)) {
                $cibr = Cibr::findOne($cibr_id);
            } else {
                $cibr = new Cibr();
            }
            $cibr->reporting_period = $reporting_period;
            $cibr->bank_account_id = $bank_account_id;
            if ($cibr->validate()) {
                if ($cibr->save(false)) {
                    return json_encode(['isSuccess' => true, 'Successfully Save']);
                }
            }
        }
    }
    public function actionFinal($id)
    {
        $model = $this->findModel($id);

        $model->is_final === 0 ? $x = 1 : $x = 0;

        $model->is_final = $x;

        if ($model->save()) {
            if ($model->is_final === 1) {
                $r_periods = new LiquidationReportingPeriod();
                $r_periods->reporting_period = $model->reporting_period;
                $r_periods->province = $model->province;
                if ($r_periods->save(false)) {

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                Yii::$app->db->createCommand("DELETE FROM  liquidation_reporting_period 
                 WHERE reporting_period = :reporting_period AND province =:province")
                    ->bindValue(':reporting_period', $model->reporting_period)
                    ->bindValue(':province', $model->province)
                    ->query();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
    }
    public function actionGetCibr()
    {
        if ($_POST) {
            $reporting_period = $_POST['reporting_period'];
            $province = '';
            $bank_account_id = $_POST['bank_account_id'];

            if (
                empty($reporting_period)
                || empty($bank_account_id)
                // || empty($book)
            ) {
                return json_encode(['error' => true, 'message' => 'Reporting Period,Province and Book are Required']);
            }


            // $dataProvider = Yii::$app->db->createCommand("SELECT 
            //     check_date,
            //     check_number,
            //     particular,
            //     amount,
            //     withdrawals,
            //     vat_nonvat,
            //     expanded_tax,
            //     gl_object_code,
            //     gl_account_title, 
            //     reporting_period
            //     from advances_liquidation
            //     where reporting_period =:reporting_period AND province LIKE :province
            //     ORDER BY reporting_period,check_date,check_number 
            // ")->bindValue(':reporting_period', $reporting_period)
            //     ->bindValue(':province', $province)
            //     ->queryAll();
            $bank_account_data = Yii::$app->db->createCommand("SELECT * FROM bank_account WHERE id = :id")
                ->bindValue(':id', $bank_account_id)
                ->queryOne();
            $dataProvider = Yii::$app->db->createCommand('CALL cibr_function(:province,:reporting_period,:bank_account_id)')
                ->bindValue(':reporting_period', $reporting_period)
                ->bindValue(':province', $province)
                ->bindValue(':bank_account_id',   $bank_account_id)
                ->queryAll();

            $province =  $bank_account_data['province'];


            $q1 = Yii::$app->db->createCommand("SELECT 
                SUM(total) as total
             from cibr_advances_balances
             where reporting_period <:reporting_period 
             AND province LIKE :province
              ")
                ->bindValue(':reporting_period',   $reporting_period)
                ->bindValue(':province',   $province)
                ->queryScalar();

            $q2 = Yii::$app->db->createCommand("SELECT 
                    SUM(total_withdrawals) as total_withdrawal
                from cibr_liquidation_balances
                where reporting_period <:reporting_period 
                AND province LIKE :province
                 ")
                ->bindValue(':reporting_period',   $reporting_period)
                ->bindValue(':province',   $province)
                ->queryScalar();
            $balance = $q1 - $q2;

            ArrayHelper::multisort($dataProvider, ['check_number',], [SORT_ASC]);

            return $this->render('_form', [
                'dataProvider' => $dataProvider,
                'province' => $province,
                'reporting_period' => $reporting_period,
                'book' => '',
                'beginning_balance' => $balance

            ]);
        } else {
            return $this->render('_form');
        }
    }
    function generateCibr($reporting_period, $province, $bank_account_id)
    {
        // $reporting_period = $_POST['reporting_period'];
        // $province = $_POST['province'];
        // $book = $_POST['book'];

        if (
            empty($reporting_period)
            || empty($bank_account_id)
            // || empty($book)
        ) {
            return json_encode(['error' => true, 'message' => 'Reporting Period,Province and Book are Required']);
        }


        $dataProvider = Yii::$app->db->createCommand('CALL cibr_function(:province,:reporting_period,:bank_account_id)')
            ->bindValue(':reporting_period', $reporting_period)
            ->bindValue(':province', $province)
            ->bindValue(':bank_account_id', $bank_account_id)
            ->queryAll();

        $q1 = Yii::$app->db->createCommand("SELECT 
            SUM(total) as total
         from cibr_advances_balances
         where reporting_period <:reporting_period 
         AND province LIKE :province
         AND bank_account_id = :bank_account_id
          ")
            ->bindValue(':reporting_period',   $reporting_period)
            ->bindValue(':province',   $province)
            ->bindValue(':bank_account_id',   $bank_account_id)
            ->queryScalar();

        $q2 = Yii::$app->db->createCommand("SELECT 
                SUM(total_withdrawals) as total_withdrawal
            from cibr_liquidation_balances
            where reporting_period <:reporting_period 
            AND province LIKE :province 
            AND bank_account_id = :bank_account_id
             ")
            ->bindValue(':reporting_period',   $reporting_period)
            ->bindValue(':province',   $province)
            ->bindValue(':bank_account_id',   $bank_account_id)
            ->queryScalar();
        $balance = $q1 - $q2;

        ArrayHelper::multisort($dataProvider, ['check_number',], [SORT_ASC]);
        return [
            'dataProvider' => $dataProvider,
            'province' => $province,
            'reporting_period' => $reporting_period,
            'book' => '',
            'beginning_balance' => $balance


        ];
    }
    public function actionAddLink()
    {
        if ($_POST) {
            $link = $_POST['link'];
            $id = $_POST['id'];
            $dv  = Cibr::findOne($id);

            $dv->document_link = $link;
            if ($dv->save(false)) {
                return json_encode(['isSuccess' => true, 'cancelled' => 'save success']);
            }
            return json_encode(['isSuccess' => true, 'cancelled' => $link]);
        }
    }
    public function actionExport()
    {

        if ($_POST) {
            $id  = $_POST['id'];
            // return $id;
            $model = $this->findModel($id);
            $province = $model->province;
            $reporting_period = $model->reporting_period;
            $bank_account_id = $model->bank_account_id;
            $query = $this->generateCibr($reporting_period, $province, $bank_account_id);

            $prov = Yii::$app->memem->cibrCdrHeader($province);
            // echo $prov['officer'];
            // return var_dump($query['dataProvider'][0]);
            $styleArray = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // header
            $sheet->setCellValue('A1', "CASH IN BANK REGISTER");
            $sheet->mergeCells('A1:L1');
            $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('A2', "For the month of " . DateTime::createFromFormat('Y-m', $reporting_period)->format('F, Y'));
            $sheet->mergeCells('A2:L2');
            $sheet->getStyle('A2:L2')->getAlignment()->setHorizontal('center');


            $sheet->setCellValue('A3', "Entity Name:Department of Trade and Industry");
            $sheet->mergeCells('A3:C3');
            $sheet->setCellValue('J3', "Sheet No. :");
            $sheet->mergeCells('J3:L3');

            $sheet->setCellValue('A4', "Sub-Office/District/Division: Provincial Office");
            $sheet->mergeCells('A4:C4');
            $sheet->setCellValue('J4', "Name of Disbursing Officer: " . $prov['officer']);
            $sheet->mergeCells('J4:L4');

            $sheet->setCellValue('A5', "Municipality/City/Province: " . $prov['province']);
            $sheet->mergeCells('A5:C5');
            $sheet->setCellValue('J5', "Station: " . $prov['province']);
            $sheet->mergeCells('J5:L5');

            $sheet->setCellValue('A6', "Fund Cluster :");
            $sheet->mergeCells('A6:C6');
            $sheet->setCellValue('J6', "Bank: Landbank of the Philippines");
            $sheet->mergeCells('J6:L6');

            $sheet->setCellValue('J7', "Location: " . $prov['location']);
            $sheet->mergeCells('J7:L7');

            $sheet->setCellValue('A8', "DATE");
            $sheet->mergeCells('A8:A11');
            $sheet->setCellValue('B8', "Check No.");
            $sheet->mergeCells('B8:B11');
            $sheet->setCellValue('C8', "Particular");
            $sheet->mergeCells('C8:C11');

            $sheet->setCellValue('D8', "CASH IN BANK");
            // $sheet->mergeCells('D8:D10');
            $sheet->mergeCellsByColumnAndRow(4, 8, 6, 9);


            $sheet->setCellValue('D10', "Deposits");
            $sheet->mergeCells('D10:D11');
            $sheet->setCellValue('E10', "Withdrawals");
            $sheet->mergeCells('E10:E11');
            $sheet->setCellValue('F10', "Balances");
            $sheet->mergeCells('F10:F11');


            $sheet->setCellValue('G8', "BREAKDOWN");
            $sheet->mergeCells('G8:L8');
            $sheet->getStyle('G8:L8')->getAlignment()->setHorizontal('center');
            $sheet->setCellValue('G9', "PERSONNEL SERVICES");
            $sheet->mergeCells('G9:H9');
            $sheet->setCellValue('I9', "MAINTENANCE AND OTHER OPERATING EXPENSES");
            $sheet->setCellValue('J9', "OTHERS");
            $sheet->mergeCells('J9:L9');

            $sheet->setCellValue('G10', "Salaries and Wages-Casual");
            $sheet->setCellValue('H10', "Salaries and Wages -Casual/ Contractual");
            $sheet->setCellValue('I10', "Office Supplies Expenses");
            $sheet->setCellValue('G11', "50101020");
            $sheet->setCellValue('H11', "50101020");
            $sheet->setCellValue('I11', "50201010");

            $sheet->setCellValue('J10', "Account Description");
            $sheet->mergeCells('J10:J11');
            $sheet->setCellValue('K10', "UACS Code	");
            $sheet->mergeCells('K10:K11');
            $sheet->setCellValue('L10', "Amount");
            $sheet->mergeCells('L10:L11');
            // $sheet->getStyleByColumnAndRow(1,8,12,11)->applyFromArray($styleArray);

            $x = 7;
            // $styleArray = array(
            //     'borders' => array(
            //         'allBorders' => array(
            //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
            //             'color' => array('argb' => 'FFFF0000'),
            //         ),
            //     ),
            // );


            $row = 13;
            $begin_balance  = (float)$query['beginning_balance'];
            $sheet->setCellValueByColumnAndRow(3, 12, 'Beggining Balance');
            $sheet->setCellValueByColumnAndRow(6, 12, $begin_balance);
            $total_amount = 0;
            $total_withdrawals = 0;
            foreach ($query['dataProvider']  as  $val) {
                $begin_balance += (float)$val['amount'] - (float)$val['withdrawals'];
                $total_amount += (float)$val['amount'];
                $total_withdrawals += (float)$val['withdrawals'];
                $sheet->setCellValueByColumnAndRow(1, $row,  $val['check_date']);
                $sheet->setCellValueByColumnAndRow(2, $row,  $val['check_number']);
                $sheet->setCellValueByColumnAndRow(3, $row,  $val['particular']);
                $sheet->setCellValueByColumnAndRow(4, $row,  $val['amount']);
                $sheet->setCellValueByColumnAndRow(5, $row,  $val['withdrawals']);
                $sheet->setCellValueByColumnAndRow(6, $row,  $begin_balance);
                // $sheet->setCellValueByColumnAndRow(7, $row,  $val['particular']);
                // $sheet->setCellValueByColumnAndRow(8, $row,  $val['payee']);
                $sheet->setCellValueByColumnAndRow(10, $row,  $val['gl_account_title']);
                $sheet->setCellValueByColumnAndRow(11, $row,  $val['gl_object_code']);
                $sheet->setCellValueByColumnAndRow(12, $row,  $val['withdrawals']);
                // $sheet->setCellValueByColumnAndRow(12, $row,  $val['vat_nonvat']);
                // $sheet->setCellValueByColumnAndRow(13, $row,  $val['expanded_tax']);
                // $sheet->setCellValueByColumnAndRow(14, $row,  $val['liquidation_damage']);
                // $sheet->setCellValueByColumnAndRow(15, $row,  $val['gross_payment']);
                // $sheet->setCellValueByColumnAndRow(16, $row,  $val['province']);
                // $sheet->setCellValueByColumnAndRow(17, $row,  $val['orig_reporting_period']);

                $row++;
            }
            $sheet->setCellValueByColumnAndRow(2, $row,  'TOTAL');
            $sheet->setCellValueByColumnAndRow(4, $row,  $total_amount);
            $sheet->setCellValueByColumnAndRow(5, $row,  $total_withdrawals);
            $sheet->setCellValueByColumnAndRow(6, $row,  $begin_balance);
            $sheet->setCellValueByColumnAndRow(12, $row,  $total_withdrawals);
            $sheet->mergeCellsByColumnAndRow(2, $row, 3, $row);

            date_default_timezone_set('Asia/Manila');
            // return date('l jS \of F Y h:i:s A');
            $id = uniqid() . '_' . date('Y-m-d h A');
            $file_name = "$province cibr_$id.xlsx";
            // header('Content-Type: application/vnd.ms-excel');
            // header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
            // header('Content-Transfer-Encoding: binary');
            // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            // header('Pragma: public'); // HTTP/1.0
            // echo readfile($file);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");

            $path = Yii::getAlias('@webroot') . '/transaction';

            $file = $path . "/$province cibr_$id.xlsx";
            $file2 = "transaction/$province cibr_$id.xlsx";
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
        }
    }
}
