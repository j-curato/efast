<?php

namespace frontend\controllers;

use common\models\DvAucs;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class DvAucsApiController extends \yii\rest\ActiveController
{
    public $modelClass = DvAucs::class;
    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['update', 'create', 'delete', 'view', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        // unset($actions['delete']);
        // unset($actions['update']);
        // unset($actions['view']);
        // unset($actions['index']);
        unset($actions['create']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();
        $source_dv_aucs = $source_json;
        $target_dv_aucs = Yii::$app->db->createCommand("SELECT * FROM `dv_aucs`")->queryAll();
        $source_dv_aucs_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_dv_aucs), array_map('serialize', $target_dv_aucs))

        );


        if (!empty($source_dv_aucs_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_dv_aucs_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `dv_aucs` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_dv_aucs = DvAucs::findOne($val['id']);

                            $update_dv_aucs->dv_number = $val['dv_number'];
                            $update_dv_aucs->reporting_period = $val['reporting_period'];
                            $update_dv_aucs->tax_withheld = $val['tax_withheld'];
                            $update_dv_aucs->other_trust_liability_withheld = $val['other_trust_liability_withheld'];
                            $update_dv_aucs->net_amount_paid = $val['net_amount_paid'];
                            $update_dv_aucs->mrd_classification_id = $val['mrd_classification_id'];
                            $update_dv_aucs->nature_of_transaction_id = $val['nature_of_transaction_id'];
                            $update_dv_aucs->particular = $val['particular'];
                            $update_dv_aucs->payee_id = $val['payee_id'];
                            $update_dv_aucs->transaction_type = $val['transaction_type'];
                            $update_dv_aucs->book_id = $val['book_id'];
                            $update_dv_aucs->is_cancelled = $val['is_cancelled'];
                            $update_dv_aucs->created_at = $val['created_at'];
                            $update_dv_aucs->dv_link = $val['dv_link'];
                            $update_dv_aucs->transaction_begin_time = $val['transaction_begin_time'];
                            $update_dv_aucs->return_timestamp = $val['return_timestamp'];
                            $update_dv_aucs->out_timestamp = $val['out_timestamp'];
                            $update_dv_aucs->accept_timestamp = $val['accept_timestamp'];
                            $update_dv_aucs->tracking_sheet_id = $val['tracking_sheet_id'];
                            $update_dv_aucs->in_timestamp = $val['in_timestamp'];



                            if ($update_dv_aucs->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_dv_aucs = new DvAucs();
                            $new_dv_aucs->id = $val['id'];
                            $new_dv_aucs->dv_number = $val['dv_number'];
                            $new_dv_aucs->reporting_period = $val['reporting_period'];
                            $new_dv_aucs->tax_withheld = $val['tax_withheld'];
                            $new_dv_aucs->other_trust_liability_withheld = $val['other_trust_liability_withheld'];
                            $new_dv_aucs->net_amount_paid = $val['net_amount_paid'];
                            $new_dv_aucs->mrd_classification_id = $val['mrd_classification_id'];
                            $new_dv_aucs->nature_of_transaction_id = $val['nature_of_transaction_id'];
                            $new_dv_aucs->particular = $val['particular'];
                            $new_dv_aucs->payee_id = $val['payee_id'];
                            $new_dv_aucs->transaction_type = $val['transaction_type'];
                            $new_dv_aucs->book_id = $val['book_id'];
                            $new_dv_aucs->is_cancelled = $val['is_cancelled'];
                            $new_dv_aucs->created_at = $val['created_at'];
                            $new_dv_aucs->dv_link = $val['dv_link'];
                            $new_dv_aucs->transaction_begin_time = $val['transaction_begin_time'];
                            $new_dv_aucs->return_timestamp = $val['return_timestamp'];
                            $new_dv_aucs->out_timestamp = $val['out_timestamp'];
                            $new_dv_aucs->accept_timestamp = $val['accept_timestamp'];
                            $new_dv_aucs->tracking_sheet_id = $val['tracking_sheet_id'];
                            $new_dv_aucs->in_timestamp = $val['in_timestamp'];
                            if ($new_dv_aucs->save(false)) {
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
