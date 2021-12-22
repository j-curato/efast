<?php

namespace frontend\controllers;

use common\models\JevPreparation;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class JevPreparationApiController extends \yii\rest\ActiveController
{
    public $modelClass = JevPreparation::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update', 'view', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }
    public function actions()
    {

        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['update']);
        unset($actions['delete']);
    }
    public function actionCreate()
    {



        $transaction = Yii::$app->db->beginTransaction();
        $source_jev = Yii::$app->getRequest()->getBodyParams();

        if (!empty($source_jev)) {
            try {
                if ($flag = true) {

                    foreach ($source_jev as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM jev_preparation WHERE jev_preparation.id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_jev_preparation = JevPreparation::findOne($val['id']);
                            $update_jev_preparation->responsibility_center_id = $val['responsibility_center_id'];
                            $update_jev_preparation->fund_cluster_code_id = $val['fund_cluster_code_id'];
                            $update_jev_preparation->reporting_period = $val['reporting_period'];
                            $update_jev_preparation->date = $val['date'];
                            $update_jev_preparation->jev_number = $val['jev_number'];
                            $update_jev_preparation->ref_number = $val['ref_number'];
                            $update_jev_preparation->dv_number = $val['dv_number'];
                            $update_jev_preparation->lddap_number = $val['lddap_number'];
                            $update_jev_preparation->explaination = $val['explaination'];
                            $update_jev_preparation->payee_id = $val['payee_id'];
                            $update_jev_preparation->cash_flow_id = $val['cash_flow_id'];
                            $update_jev_preparation->mrd_classification_id = $val['mrd_classification_id'];
                            $update_jev_preparation->cadadr_serial_number = $val['cadadr_serial_number'];
                            $update_jev_preparation->check_ada = $val['check_ada'];
                            $update_jev_preparation->check_ada_number = $val['check_ada_number'];
                            $update_jev_preparation->check_ada_date = $val['check_ada_date'];
                            $update_jev_preparation->book_id = $val['book_id'];
                            $update_jev_preparation->created_at = $val['created_at'];
                            $update_jev_preparation->cash_disbursement_id = $val['cash_disbursement_id'];


                            if ($update_jev_preparation->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return json_encode('wala na save sa Document Recieve update');
                            }
                        } else {
                            $new_jev_preparation = new JevPreparation();
                            $new_jev_preparation->id = $val['id'];
                            $new_jev_preparation->responsibility_center_id = $val['responsibility_center_id'];
                            $new_jev_preparation->fund_cluster_code_id = $val['fund_cluster_code_id'];
                            $new_jev_preparation->reporting_period = $val['reporting_period'];
                            $new_jev_preparation->date = $val['date'];
                            $new_jev_preparation->jev_number = $val['jev_number'];
                            $new_jev_preparation->ref_number = $val['ref_number'];
                            $new_jev_preparation->dv_number = $val['dv_number'];
                            $new_jev_preparation->lddap_number = $val['lddap_number'];
                            $new_jev_preparation->explaination = $val['explaination'];
                            $new_jev_preparation->payee_id = $val['payee_id'];
                            $new_jev_preparation->cash_flow_id = $val['cash_flow_id'];
                            $new_jev_preparation->mrd_classification_id = $val['mrd_classification_id'];
                            $new_jev_preparation->cadadr_serial_number = $val['cadadr_serial_number'];
                            $new_jev_preparation->check_ada = $val['check_ada'];
                            $new_jev_preparation->check_ada_number = $val['check_ada_number'];
                            $new_jev_preparation->check_ada_date = $val['check_ada_date'];
                            $new_jev_preparation->book_id = $val['book_id'];
                            $new_jev_preparation->created_at = $val['created_at'];
                            $new_jev_preparation->cash_disbursement_id = $val['cash_disbursement_id'];

                            if ($new_jev_preparation->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return 'wala na sulod  sa Document Recieve ';
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return 'success';
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
