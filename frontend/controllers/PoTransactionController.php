<?php

namespace frontend\controllers;

use Yii;
use app\models\PoTransaction;
use app\models\PoTransactionSearch;
use DateTime;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoTransactionController implements the CRUD actions for PoTransaction model.
 */
class PoTransactionController extends Controller
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
                    'delete',
                    'update',
                    'create',
                    'get-transaction'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'delete',
                            'update',
                            'create',
                            'get-transaction'
                        ],
                        'allow' => true,
                        'roles' => ['super-user', 'po_transaction']
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
     * Lists all PoTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PoTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PoTransaction model.
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
     * Creates a new PoTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PoTransaction();

        $model->province = Yii::$app->user->identity->province;
        // $model->po_responsibility_center_id = strtoupper(\Yii::$app->user->identity->province) .'-'. $model->po_responsibility_center_id ;
        if ($model->load(Yii::$app->request->post())) {
            $model->tracking_number = $this->getTrackingNumber($model->po_responsibility_center_id, $model->reporting_period);
            if ($model->save(false)) {
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PoTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $x = explode('-', $model->tracking_number);
            $responsibility_center = (new \yii\db\Query())
                ->select("name")
                ->from('po_responsibility_center')
                ->where("id =:id", ['id' => $model->po_responsibility_center_id])
                ->one();
            $x[1] = $responsibility_center['name'];
            $model->tracking_number = implode('-', $x);
            if ($model->save(false)) {

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PoTransaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the PoTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PoTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoTransaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getTrackingNumber($responsibility_center_id, $reporting_period)
    {
        // $date = date("Y");
        $reporting_period_year = DateTime::createFromFormat('Y-m', $reporting_period)->format('Y');
        $date = $reporting_period_year;
        $responsibility_center = (new \yii\db\Query())
            ->select("name")
            ->from('po_responsibility_center')
            ->where("id =:id", ['id' => $responsibility_center_id])
            ->one();
        $province = Yii::$app->user->identity->province;
        if ($reporting_period_year <= 2021) {

            $latest_tracking_no = Yii::$app->db->createCommand(
                "SELECT CAST(substring_index(tracking_number,'-',-1)  AS UNSIGNED) as q
        FROM `po_transaction`
        WHERE tracking_number LIKE :province
       
         ORDER BY q DESC LIMIT 1"
            )
                ->bindValue(':province', $province . '%')

                ->queryScalar();
        } else {
            $latest_tracking_no = Yii::$app->db->createCommand(
                "SELECT CAST(substring_index(tracking_number,'-',-1)  AS UNSIGNED) as q
                FROM `po_transaction`
                WHERE tracking_number LIKE :province
                AND reporting_period LIKE :_year
                 ORDER BY q DESC LIMIT 1"
            )
                ->bindValue(':province', $province . '%')
                ->bindValue(':_year', $date . '%')
                ->queryScalar();
        }

        if (!empty($latest_tracking_no)) {
            $last_number = $latest_tracking_no + 1;
        } else {
            $last_number = 1;
        }
        $final_number = '';
        // for ($y = strlen($last_number); $y < 3; $y++) {
        //     $final_number .= 0;
        // }
        if (strlen($last_number) < 4) {

            $final_number = substr(str_repeat(0, 3) . $last_number, -3);
        } else {
            $final_number = $last_number;
        }


        // $final_number .= $last_number;
        $tracking_number = strtoupper(\Yii::$app->user->identity->province) . '-' . trim($responsibility_center['name']) . '-' . $date . '-' . $final_number;
        return  $tracking_number;
    }
    public function actionGetTransaction()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query = (new \yii\db\Query())
                ->select([
                    'po_transaction.particular',
                    'po_transaction.payee',
                    'po_transaction.amount',
                    'po_responsibility_center.name as r_center_name',
                ])
                ->from('po_transaction')
                ->join('LEFT JOIN', 'po_responsibility_center', 'po_transaction.po_responsibility_center_id =po_responsibility_center.id')
                ->where('po_transaction.id =:id', ['id' => $id])
                ->one();
            // ob_clean();
            // echo "<pre>";
            // var_dump($id);
            // echo "</pre>";
            // return ob_get_clean();
            return json_encode($query);
        }
    }
    public function actionGetAllTransaction()
    {
        $province = strtolower(Yii::$app->user->identity->province);
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('po_transaction');
        if (
            $province === 'adn' ||
            $province === 'ads' ||
            $province === 'sds' ||
            $province === 'sdn' ||
            $province === 'pdi'
        ) {
            $query->where('po_transaction.tracking_number LIKE :tracking_number', ['tracking_number' => "$province%"]);
        }
        $q =    $query->all();

        return json_encode($q);
    }
    public function actionImport()
    {
        if (!empty($_POST)) {
            $name = $_FILES["file"]["name"];
            $id = uniqid();
            $file = "transaction/{$id}_{$name}";
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
            } else {
                return "ERROR 2: MOVING FILES FAILED.";
                die();
            }
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $excel = $reader->load($file);
            $excel->setActiveSheetIndexByName('poTransaction');
            $worksheet = $excel->getActiveSheet();

            $data = [];


            $transaction = Yii::$app->db->beginTransaction();
            foreach ($worksheet->getRowIterator(3) as $key => $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                $y = 0;
                foreach ($cellIterator as $x => $cell) {
                    // $q = '';
                    // if ($y === 1) {
                    //     $cells[] = $cell->getFormattedValue();
                    // } else {
                    $cells[] =   $cell->getValue();
                    // }
                    // $y++;
                }
                if (!empty($cells)) {

                    $province = $cells[0];
                    $res_center =  $cells[1];
                    $transaction_number =  $cells[2];
                    $payee =  $cells[3];
                    $particular =  $cells[4];
                    $amount =  $cells[5];
                    $payroll_number = $cells[6];
                    $reporting_period = '2021-12';


                    $tran = new PoTransaction();
                    $res_center_id = Yii::$app->db->createCommand("SELECT * FROM po_responsibility_center 
                    WHERE po_responsibility_center.`name` = :res_center
                    AND po_responsibility_center.province =:province")
                        ->bindValue(':res_center', $res_center)
                        ->bindValue(':province', $province)
                        ->queryScalar();


                    if (empty($res_center_id)) {
                        $transaction->rollBack();
                        return "res center $key";
                    }
                    $tran->payee = $payee;
                    $tran->particular = $particular;
                    $tran->amount = $amount;
                    $tran->payroll_number = $payroll_number;
                    $tran->tracking_number = $transaction_number;
                    $tran->po_responsibility_center_id = $res_center_id;
                    $tran->province = $province;
                    $tran->reporting_period = $reporting_period;
                    if ($tran->save(false)) {
                    } else {
                        $transaction->rollBack();
                        return "res center $key";
                    }
                }
            }

            // $column = [
            //     'liquidation_id',
            //     'chart_of_account_id',
            //     'withdrawals',
            //     'vat_nonvat',
            //     'expanded_tax',
            //     'reporting_period',
            //     'advances_entries_id'
            // ];
            // $ja = Yii::$app->db->createCommand()->batchInsert('liquidation_entries', $column, $data)->execute();

            // // return $this->redirect(['index']);
            // return json_encode(['isSuccess' => true]);
            $transaction->commit();
            ob_clean();
            echo "<pre>";
            var_dump('success');
            echo "</pre>";
            return ob_get_clean();
        }
    }
    public function actionSearchPoTransaction($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_province = strtolower(Yii::$app->user->identity->province);

        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
        } else if (!is_null($q)) {
            $query = new Query();
            $query->select('po_transaction.id, po_transaction.tracking_number AS text')
                ->from('po_transaction')
                ->where(['like', 'po_transaction.tracking_number', $q]);
            if (
                $user_province === 'adn' ||
                $user_province === 'ads' ||
                $user_province === 'sdn' ||
                $user_province === 'sds' ||
                $user_province === 'pdi'
            ) {
                $query->andWhere('po_transaction.province = :province', ['province' => $user_province]);
            }
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        return $out;
    }
}
