<?php

namespace frontend\controllers;

use common\models\CashDisbursement;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class CashDisbursementApiController extends \yii\rest\ActiveController
{
    public $modelClass = CashDisbursement::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['update', 'delete', 'create', 'index', 'view'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return $behaviors;
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['view']);
        unset($actions['inde']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_cash_disbursement = Yii::$app->getRequest()->getBodyParams();
        $target_cash_disbursement = Yii::$app->db->createCommand("SELECT * FROM `cash_disbursement`")->queryAll();
        $source_cash_disbursement_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_cash_disbursement), array_map('serialize', $target_cash_disbursement))

        );


        if (!empty($source_cash_disbursement_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_cash_disbursement_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `cash_disbursement` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_cash_disbursement = CashDisbursement::findOne($val['id']);
                            $update_cash_disbursement->book_id = $val['book_id'];
                            $update_cash_disbursement->dv_aucs_id = $val['dv_aucs_id'];
                            $update_cash_disbursement->reporting_period = $val['reporting_period'];
                            $update_cash_disbursement->mode_of_payment = $val['mode_of_payment'];
                            $update_cash_disbursement->check_or_ada_no = $val['check_or_ada_no'];
                            $update_cash_disbursement->is_cancelled = $val['is_cancelled'];
                            $update_cash_disbursement->issuance_date = $val['issuance_date'];
                            $update_cash_disbursement->ada_number = $val['ada_number'];
                            $update_cash_disbursement->begin_time = $val['begin_time'];
                            $update_cash_disbursement->out_time = $val['out_time'];
                            $update_cash_disbursement->parent_disbursement = $val['parent_disbursement'];
                            if ($update_cash_disbursement->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_cash_disbursement = new CashDisbursement();
                            $new_cash_disbursement->id = $val['id'];
                            $new_cash_disbursement->book_id = $val['book_id'];
                            $new_cash_disbursement->dv_aucs_id = $val['dv_aucs_id'];
                            $new_cash_disbursement->reporting_period = $val['reporting_period'];
                            $new_cash_disbursement->mode_of_payment = $val['mode_of_payment'];
                            $new_cash_disbursement->check_or_ada_no = $val['check_or_ada_no'];
                            $new_cash_disbursement->is_cancelled = $val['is_cancelled'];
                            $new_cash_disbursement->issuance_date = $val['issuance_date'];
                            $new_cash_disbursement->ada_number = $val['ada_number'];
                            $new_cash_disbursement->begin_time = $val['begin_time'];
                            $new_cash_disbursement->out_time = $val['out_time'];
                            $new_cash_disbursement->parent_disbursement = $val['parent_disbursement'];
                            if ($new_cash_disbursement->save(false)) {
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
