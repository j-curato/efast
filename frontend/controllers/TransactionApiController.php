<?php

namespace frontend\controllers;

use common\models\Transaction;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;

class TransactionApiController extends \yii\rest\ActiveController
{


    public $modelClass = Transaction::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge([
            'corsFilter' => Cors::class,
        ], $behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
    }
    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();
        $source_jason = Yii::$app->getRequest()->getBodyParams();
        $source_transaction = $source_jason;
        // $target_transaction = Yii::$app->db->createCommand("SELECT * FROM `transaction`")->queryAll();
        // $source_transaction_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_transaction), array_map('serialize', $target_transaction))

        // );
        // var_dump($source_transaction);
        // die();
        if (!empty($source_transaction)) {
            try {

                // foreach ($source_transaction as $val) {
                //     $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `transaction` WHERE id = :id)")
                //         ->bindValue(':id', $val['id'])
                //         ->queryScalar();
                //     if (intval($query) === 1) {
                //         $transaction_tbl = transaction::findOne($val['id']);
                //     } else {
                //         $transaction_tbl = new transaction();
                //         $transaction_tbl->id = $val['id'];
                //     }
                //     $transaction_tbl->responsibility_center_id = $val['responsibility_center_id'];
                //     $transaction_tbl->payee_id = $val['payee_id'];
                //     $transaction_tbl->particular = $val['particular'];
                //     $transaction_tbl->gross_amount = $val['gross_amount'];
                //     $transaction_tbl->tracking_number = $val['tracking_number'];
                //     $transaction_tbl->earmark_no = $val['earmark_no'];
                //     $transaction_tbl->payroll_number = $val['payroll_number'];
                //     $transaction_tbl->transaction_date = $val['transaction_date'];
                //     $transaction_tbl->transaction_time = $val['transaction_time'];
                //     $transaction_tbl->created_at = $val['created_at'];
                //     $transaction_tbl->is_local = $val['is_local'];
                //     $transaction_tbl->type = $val['type'];
                //     if ($transaction_tbl->save(false)) {
                //     } else {
                //         $transaction->rollBack();
                //         return false;
                //     }
                // }
                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'responsibility_center_id',
                    'payee_id',
                    'particular',
                    'gross_amount',
                    'tracking_number',
                    'earmark_no',
                    'payroll_number',
                    'transaction_date',
                    'transaction_time',
                    'created_at',
                    'is_local',
                    'type',

                ];
                $data = [];

                foreach ($source_transaction as $val) {

                    $data[] = [
                        'id' => Html::encode($val['id']),
                        'responsibility_center_id' => Html::encode($val['responsibility_center_id']),
                        'payee_id' => Html::encode($val['payee_id']),
                        'particular' => Html::encode($val['particular']),
                        'gross_amount' => Html::encode($val['gross_amount']),
                        'tracking_number' => Html::encode($val['tracking_number']),
                        'earmark_no' => Html::encode($val['earmark_no']),
                        'payroll_number' => Html::encode($val['payroll_number']),
                        'transaction_date' => Html::encode($val['transaction_date']),
                        'transaction_time' => Html::encode($val['transaction_time']),
                        'created_at' => Html::encode($val['created_at']),
                        'is_local' => Html::encode($val['is_local']),
                        'type' => Html::encode($val['type']),
                    ];
                }

                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('transaction', $columns, $data);
                    $db->createCommand($sql . "AS new_val ON DUPLICATE KEY UPDATE
                    responsibility_center_id=VALUES(responsibility_center_id),
                    payee_id=VALUES(payee_id),
                    particular=VALUES(particular),
                    gross_amount=VALUES(gross_amount),
                    tracking_number=VALUES(tracking_number),
                    earmark_no=VALUES(earmark_no),
                    payroll_number=VALUES(payroll_number),
                    transaction_date=VALUES(transaction_date),
                    transaction_time=VALUES(transaction_time),
                    created_at=VALUES(created_at),
                    is_local=VALUES(is_local),
                    type=VALUES(type)
                ")->query();
                    $transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        return 'success';
    }
}
