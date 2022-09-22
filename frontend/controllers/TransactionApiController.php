<?php

namespace frontend\controllers;

use common\models\Transaction;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

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
        $target_transaction = Yii::$app->db->createCommand("SELECT * FROM `transaction`")->queryAll();
        $source_transaction_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_transaction), array_map('serialize', $target_transaction))

        );
        if (!empty($source_transaction_difference)) {
            try {

                if ($flag = true) {
                    foreach ($source_transaction_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `transaction` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $transaction_tbl = transaction::findOne($val['id']);
                        } else {
                            $transaction_tbl = new transaction();
                            $transaction_tbl->id = $val['id'];
                        }
                        $transaction_tbl->responsibility_center_id = $val['responsibility_center_id'];
                        $transaction_tbl->payee_id = $val['payee_id'];
                        $transaction_tbl->particular = $val['particular'];
                        $transaction_tbl->gross_amount = $val['gross_amount'];
                        $transaction_tbl->tracking_number = $val['tracking_number'];
                        $transaction_tbl->earmark_no = $val['earmark_no'];
                        $transaction_tbl->payroll_number = $val['payroll_number'];
                        $transaction_tbl->transaction_date = $val['transaction_date'];
                        $transaction_tbl->transaction_time = $val['transaction_time'];
                        $transaction_tbl->created_at = $val['created_at'];
                        $transaction_tbl->is_local = $val['is_local'];
                        $transaction_tbl->type = $val['type'];
                        if ($transaction_tbl->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return false;
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return 'success s';
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        return 'success';
    }
}
