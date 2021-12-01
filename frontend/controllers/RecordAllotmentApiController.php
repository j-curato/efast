<?php

namespace frontend\controllers;

use common\models\RecordAllotmentEntries;
use common\models\RecordAllotments;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class RecordAllotmentApiController extends \yii\rest\ActiveController
{

    public $modelClass = RecordAllotments::class;
    public function behaviors()
    {

        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update', 'view', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return $behaviors;
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
        unset($actions['index']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();

        $source_record_allotment = $source_json['record_allotments'];
        $target_record_allotment = Yii::$app->db->createCommand("SELECT * FROM `record_allotments`")->queryAll();
        $source_record_allotment_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_record_allotment), array_map('serialize', $target_record_allotment))

        );
        // return json_encode($source_record_allotment_difference);

        // var_dump($source_record_allotment_difference);
        // return json_encode($source_record_allotment_difference);

        if (!empty($source_record_allotment_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_record_allotment_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `record_allotments` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_record_allotment = RecordAllotments::findOne($val['id']);
                            $update_record_allotment->document_recieve_id = $val['document_recieve_id'];
                            $update_record_allotment->fund_cluster_code_id = $val['fund_cluster_code_id'];
                            $update_record_allotment->financing_source_code_id = $val['financing_source_code_id'];
                            $update_record_allotment->fund_category_and_classification_code_id = $val['fund_category_and_classification_code_id'];
                            $update_record_allotment->authorization_code_id = $val['authorization_code_id'];
                            $update_record_allotment->mfo_pap_code_id = $val['mfo_pap_code_id'];
                            $update_record_allotment->fund_source_id = $val['fund_source_id'];
                            $update_record_allotment->reporting_period = $val['reporting_period'];
                            $update_record_allotment->serial_number = $val['serial_number'];
                            $update_record_allotment->allotment_number = $val['allotment_number'];
                            $update_record_allotment->date_issued = $val['date_issued'];
                            $update_record_allotment->valid_until = $val['valid_until'];
                            $update_record_allotment->particulars = $val['particulars'];
                            $update_record_allotment->fund_classification = $val['fund_classification'];
                            $update_record_allotment->book_id = $val['book_id'];
                            $update_record_allotment->funding_code = $val['funding_code'];
                            $update_record_allotment->responsibility_center_id = $val['responsibility_center_id'];


                            if ($update_record_allotment->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_record_allotment = new RecordAllotments();
                            $new_record_allotment->id = $val['id'];
                            $new_record_allotment->document_recieve_id = $val['document_recieve_id'];
                            $new_record_allotment->fund_cluster_code_id = $val['fund_cluster_code_id'];
                            $new_record_allotment->financing_source_code_id = $val['financing_source_code_id'];
                            $new_record_allotment->fund_category_and_classification_code_id = $val['fund_category_and_classification_code_id'];
                            $new_record_allotment->authorization_code_id = $val['authorization_code_id'];
                            $new_record_allotment->mfo_pap_code_id = $val['mfo_pap_code_id'];
                            $new_record_allotment->fund_source_id = $val['fund_source_id'];
                            $new_record_allotment->reporting_period = $val['reporting_period'];
                            $new_record_allotment->serial_number = $val['serial_number'];
                            $new_record_allotment->allotment_number = $val['allotment_number'];
                            $new_record_allotment->date_issued = $val['date_issued'];
                            $new_record_allotment->valid_until = $val['valid_until'];
                            $new_record_allotment->particulars = $val['particulars'];
                            $new_record_allotment->fund_classification = $val['fund_classification'];
                            $new_record_allotment->book_id = $val['book_id'];
                            $new_record_allotment->funding_code = $val['funding_code'];
                            $new_record_allotment->responsibility_center_id = $val['responsibility_center_id'];
                            if ($new_record_allotment->save(false)) {
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
        $source_record_allotment_entries = $source_json['record_allotment_entries'];
        $target_record_allotment_entries = Yii::$app->db->createCommand("SELECT * FROM `record_allotment_entries`")->queryAll();
        $source_record_allotment_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_record_allotment_entries), array_map('serialize', $target_record_allotment_entries))

        );

        if (!empty($source_record_allotment_entries_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_record_allotment_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `record_allotment_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_record_allotment_entries = RecordAllotmentEntries::findOne($val['id']);
                            $update_record_allotment_entries->record_allotment_id = $val['record_allotment_id'];
                            $update_record_allotment_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $update_record_allotment_entries->amount = $val['amount'];

                            if ($update_record_allotment_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'not save in entries';
                            }
                        } else {
                            $new_record_allotment_entries = new RecordAllotmentEntries();
                            $new_record_allotment_entries->id = $val['id'];
                            $new_record_allotment_entries->record_allotment_id = $val['record_allotment_id'];
                            $new_record_allotment_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $new_record_allotment_entries->amount = $val['amount'];

                            if ($new_record_allotment_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'not save in entries';
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
