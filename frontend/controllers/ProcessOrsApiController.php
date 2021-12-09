<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\filters\Cors;
use common\models\ProcessOrs;
use common\models\ProcessOrsEntries;
use yii\filters\auth\HttpBearerAuth;

class ProcessOrsApiController extends \yii\rest\ActiveController
{
    public $modelClass = ProcessOrs::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter'=>Cors::class],$behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['update']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();
        

        $source_process_ors = $source_json['process_ors'];
        $target_process_ors = Yii::$app->db->createCommand("SELECT * FROM `process_ors`")->queryAll();
        $source_process_ors_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_process_ors), array_map('serialize', $target_process_ors))

        );
        if (!empty($source_process_ors_difference)) {
            try {
                if ($flag = true) {
                    foreach ($source_process_ors_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `process_ors` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_process_ors = ProcessOrs::findOne($val['id']);
                            $update_process_ors->transaction_id = $val['transaction_id'];
                            $update_process_ors->reporting_period = $val['reporting_period'];
                            $update_process_ors->serial_number = $val['serial_number'];
                            $update_process_ors->obligation_number = $val['obligation_number'];
                            $update_process_ors->funding_code = $val['funding_code'];
                            $update_process_ors->document_recieve_id = $val['document_recieve_id'];
                            $update_process_ors->mfo_pap_code_id = $val['mfo_pap_code_id'];
                            $update_process_ors->fund_source_id = $val['fund_source_id'];
                            $update_process_ors->book_id = $val['book_id'];
                            $update_process_ors->date = $val['date'];
                            $update_process_ors->is_cancelled = $val['is_cancelled'];
                            $update_process_ors->type = $val['type'];
                            $update_process_ors->created_at = $val['created_at'];
                            $update_process_ors->transaction_begin_time = $val['transaction_begin_time'];

                            if ($update_process_ors->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_process_ors = new ProcessOrs();
                            $new_process_ors->id = $val['id'];
                            $new_process_ors->transaction_id = $val['transaction_id'];
                            $new_process_ors->reporting_period = $val['reporting_period'];
                            $new_process_ors->serial_number = $val['serial_number'];
                            $new_process_ors->obligation_number = $val['obligation_number'];
                            $new_process_ors->funding_code = $val['funding_code'];
                            $new_process_ors->document_recieve_id = $val['document_recieve_id'];
                            $new_process_ors->mfo_pap_code_id = $val['mfo_pap_code_id'];
                            $new_process_ors->fund_source_id = $val['fund_source_id'];
                            $new_process_ors->book_id = $val['book_id'];
                            $new_process_ors->date = $val['date'];
                            $new_process_ors->is_cancelled = $val['is_cancelled'];
                            $new_process_ors->type = $val['type'];
                            $new_process_ors->created_at = $val['created_at'];
                            $new_process_ors->transaction_begin_time = $val['transaction_begin_time'];
                            if ($new_process_ors->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        $source_process_ors_entries =$source_json['process_ors_entries'];
        $target_process_ors_entries = Yii::$app->db->createCommand("SELECT * FROM `process_ors_entries`")->queryAll();
        $source_process_ors_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_process_ors_entries), array_map('serialize', $target_process_ors_entries))

        );


        if (!empty($source_process_ors_entries_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_process_ors_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `process_ors_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_process_ors_entries = ProcessOrsEntries::findOne($val['id']);

                            $update_process_ors_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $update_process_ors_entries->process_ors_id = $val['process_ors_id'];
                            $update_process_ors_entries->amount = $val['amount'];
                            $update_process_ors_entries->reporting_period = $val['reporting_period'];
                            $update_process_ors_entries->record_allotment_entries_id = $val['record_allotment_entries_id'];
                            $update_process_ors_entries->is_realign = $val['is_realign'];


                            if ($update_process_ors_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_process_ors_entries = new ProcessOrsEntries();
                            $new_process_ors_entries->id = $val['id'];
                            $new_process_ors_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $new_process_ors_entries->process_ors_id = $val['process_ors_id'];
                            $new_process_ors_entries->amount = $val['amount'];
                            $new_process_ors_entries->reporting_period = $val['reporting_period'];
                            $new_process_ors_entries->record_allotment_entries_id = $val['record_allotment_entries_id'];
                            $new_process_ors_entries->is_realign = $val['is_realign'];
                            if ($new_process_ors_entries->save(false)) {
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
