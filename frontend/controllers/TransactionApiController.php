<?php

namespace frontend\controllers;

use common\models\Transaction;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class TransactionApiController extends \yii\rest\ActiveController
{


    public $modelClass = Transaction::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update'];
        $behavios['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return $behaviors;
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
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
        // return json_encode($source_transaction_difference);

        // var_dump($source_transaction_difference);
        // return json_encode($source_transaction_difference);

        if (!empty($source_transaction_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_transaction_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `transaction` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_transaction = transaction::findOne($val['id']);
                            $update_transaction->responsibility_center_id = $val['responsibility_center_id'];
                            $update_transaction->payee_id = $val['payee_id'];
                            $update_transaction->particular = $val['particular'];
                            $update_transaction->gross_amount = $val['gross_amount'];
                            $update_transaction->tracking_number = $val['tracking_number'];
                            $update_transaction->earmark_no = $val['earmark_no'];
                            $update_transaction->payroll_number = $val['payroll_number'];
                            $update_transaction->transaction_date = $val['transaction_date'];
                            $update_transaction->transaction_time = $val['transaction_time'];
                            $update_transaction->created_at = $val['created_at'];
                            if ($update_transaction->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;

                            }
                        } else {
                            $new_transaction = new transaction();
                            $new_transaction->id = $val['id'];
                            $new_transaction->responsibility_center_id = $val['responsibility_center_id'];
                            $new_transaction->payee_id = $val['payee_id'];
                            $new_transaction->particular = $val['particular'];
                            $new_transaction->gross_amount = $val['gross_amount'];
                            $new_transaction->tracking_number = $val['tracking_number'];
                            $new_transaction->earmark_no = $val['earmark_no'];
                            $new_transaction->payroll_number = $val['payroll_number'];
                            $new_transaction->transaction_date = $val['transaction_date'];
                            $new_transaction->transaction_time = $val['transaction_time'];
                            $new_transaction->created_at = $val['created_at'];
                            if ($new_transaction->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
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
