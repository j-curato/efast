<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\filters\Cors;
use common\models\RecordAllotments;
use yii\filters\auth\HttpBearerAuth;
use common\models\RecordAllotmentEntries;
use yii\helpers\Html;

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
        return array_merge([
            'corsFilter' => Cors::class
        ], $behaviors);
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

        $source_json = Yii::$app->getRequest()->getBodyParams();

        $source_record_allotment = $source_json['record_allotments'];
        // $target_record_allotment = Yii::$app->db->createCommand("SELECT * FROM `record_allotments`")->queryAll();
        // $source_record_allotment_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_record_allotment), array_map('serialize', $target_record_allotment))

        // );
        // return json_encode($source_record_allotment_difference);

        // var_dump($source_record_allotment_difference);
        // return json_encode($source_record_allotment_difference);

        if (!empty($source_record_allotment)) {
            try {
                $ors_transaction = Yii::$app->db->beginTransaction();

                // foreach ($source_record_allotment as $val) {
                //     $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `record_allotments` WHERE id = :id)")
                //         ->bindValue(':id', $val['id'])
                //         ->queryScalar();
                //     if (intval($query) === 1) {
                //         $update_record_allotment = RecordAllotments::findOne($val['id']);
                //         $update_record_allotment->document_recieve_id = $val['document_recieve_id'];
                //         $update_record_allotment->fund_cluster_code_id = $val['fund_cluster_code_id'];
                //         $update_record_allotment->financing_source_code_id = $val['financing_source_code_id'];
                //         $update_record_allotment->fund_category_and_classification_code_id = $val['fund_category_and_classification_code_id'];
                //         $update_record_allotment->authorization_code_id = $val['authorization_code_id'];
                //         $update_record_allotment->mfo_pap_code_id = $val['mfo_pap_code_id'];
                //         $update_record_allotment->fund_source_id = $val['fund_source_id'];
                //         $update_record_allotment->reporting_period = $val['reporting_period'];
                //         $update_record_allotment->serial_number = $val['serial_number'];
                //         $update_record_allotment->allotment_number = $val['allotment_number'];
                //         $update_record_allotment->date_issued = $val['date_issued'];
                //         $update_record_allotment->valid_until = $val['valid_until'];
                //         $update_record_allotment->particulars = $val['particulars'];
                //         $update_record_allotment->fund_classification = $val['fund_classification'];
                //         $update_record_allotment->book_id = $val['book_id'];
                //         $update_record_allotment->funding_code = $val['funding_code'];
                //         $update_record_allotment->responsibility_center_id = $val['responsibility_center_id'];


                //         if ($update_record_allotment->save(false)) {
                //         } else {
                //             $transaction->rollBack();
                //             return false;
                //         }
                //     } else {
                //         $new_record_allotment = new RecordAllotments();
                //         $new_record_allotment->id = $val['id'];
                //         $new_record_allotment->document_recieve_id = $val['document_recieve_id'];
                //         $new_record_allotment->fund_cluster_code_id = $val['fund_cluster_code_id'];
                //         $new_record_allotment->financing_source_code_id = $val['financing_source_code_id'];
                //         $new_record_allotment->fund_category_and_classification_code_id = $val['fund_category_and_classification_code_id'];
                //         $new_record_allotment->authorization_code_id = $val['authorization_code_id'];
                //         $new_record_allotment->mfo_pap_code_id = $val['mfo_pap_code_id'];
                //         $new_record_allotment->fund_source_id = $val['fund_source_id'];
                //         $new_record_allotment->reporting_period = $val['reporting_period'];
                //         $new_record_allotment->serial_number = $val['serial_number'];
                //         $new_record_allotment->allotment_number = $val['allotment_number'];
                //         $new_record_allotment->date_issued = $val['date_issued'];
                //         $new_record_allotment->valid_until = $val['valid_until'];
                //         $new_record_allotment->particulars = $val['particulars'];
                //         $new_record_allotment->fund_classification = $val['fund_classification'];
                //         $new_record_allotment->book_id = $val['book_id'];
                //         $new_record_allotment->funding_code = $val['funding_code'];
                //         $new_record_allotment->responsibility_center_id = $val['responsibility_center_id'];
                //         if ($new_record_allotment->save(false)) {
                //         } else {
                //             $transaction->rollBack();
                //             return false;
                //         }
                //     }
                // }
                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'document_recieve_id',
                    'fund_cluster_code_id',
                    'financing_source_code_id',
                    'fund_category_and_classification_code_id',
                    'authorization_code_id',
                    'mfo_pap_code_id',
                    'fund_source_id',
                    'reporting_period',
                    'serial_number',
                    'allotment_number',
                    'date_issued',
                    'valid_until',
                    'particulars',
                    'fund_classification',
                    'book_id',
                    'funding_code',
                    'responsibility_center_id',


                ];
                $data = [];

                foreach ($source_record_allotment as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'document_recieve_id' => !empty($val['document_recieve_id']) ? Html::encode($val['document_recieve_id']) : null,
                        'fund_cluster_code_id' => !empty($val['fund_cluster_code_id']) ? Html::encode($val['fund_cluster_code_id']) : null,
                        'financing_source_code_id' => !empty($val['financing_source_code_id']) ? Html::encode($val['financing_source_code_id']) : null,
                        'fund_category_and_classification_code_id' => !empty($val['fund_category_and_classification_code_id']) ? Html::encode($val['fund_category_and_classification_code_id']) : null,
                        'authorization_code_id' => !empty($val['authorization_code_id']) ? Html::encode($val['authorization_code_id']) : null,
                        'mfo_pap_code_id' => !empty($val['mfo_pap_code_id']) ? Html::encode($val['mfo_pap_code_id']) : null,
                        'fund_source_id' => !empty($val['fund_source_id']) ? Html::encode($val['fund_source_id']) : null,
                        'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                        'serial_number' => !empty($val['serial_number']) ? Html::encode($val['serial_number']) : null,
                        'allotment_number' => !empty($val['allotment_number']) ? Html::encode($val['allotment_number']) : null,
                        'date_issued' => !empty($val['date_issued']) ? Html::encode($val['date_issued']) : null,
                        'valid_until' => !empty($val['valid_until']) ? Html::encode($val['valid_until']) : null,
                        'particulars' => !empty($val['particulars']) ? Html::encode($val['particulars']) : null,
                        'fund_classification' => !empty($val['fund_classification']) ? Html::encode($val['fund_classification']) : null,
                        'book_id' => !empty($val['book_id']) ? Html::encode($val['book_id']) : null,
                        'funding_code' => !empty($val['funding_code']) ? Html::encode($val['funding_code']) : null,
                        'responsibility_center_id' => !empty($val['responsibility_center_id']) ? Html::encode($val['responsibility_center_id']) : null,

                    ];
                }

                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('record_allotments', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
        
                        document_recieve_id=VALUES(document_recieve_id),
                        fund_cluster_code_id=VALUES(fund_cluster_code_id),
                        financing_source_code_id=VALUES(financing_source_code_id),
                        fund_category_and_classification_code_id=VALUES(fund_category_and_classification_code_id),
                        authorization_code_id=VALUES(authorization_code_id),
                        mfo_pap_code_id=VALUES(mfo_pap_code_id),
                        fund_source_id=VALUES(fund_source_id),
                        reporting_period=VALUES(reporting_period),
                        serial_number=VALUES(serial_number),
                        allotment_number=VALUES(allotment_number),
                        date_issued=VALUES(date_issued),
                        valid_until=VALUES(valid_until),
                        particulars=VALUES(particulars),
                        fund_classification=VALUES(fund_classification),
                        book_id=VALUES(book_id),
                        funding_code=VALUES(funding_code),
                        responsibility_center_id=VALUES(responsibility_center_id)
                    ")->execute();
                    $ors_transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        $source_record_allotment_entries = $source_json['record_allotment_entries'];
        // $target_record_allotment_entries = Yii::$app->db->createCommand("SELECT * FROM `record_allotment_entries`")->queryAll();
        // $source_record_allotment_entries_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_record_allotment_entries), array_map('serialize', $target_record_allotment_entries))

        // );

        if (!empty($source_record_allotment_entries)) {
            try {
                $entry_transaction = Yii::$app->db->beginTransaction();

                // foreach ($source_record_allotment_entries as $val) {
                //     $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `record_allotment_entries` WHERE id = :id)")
                //         ->bindValue(':id', $val['id'])
                //         ->queryScalar();
                //     if (intval($query) === 1) {
                //         $update_record_allotment_entries = RecordAllotmentEntries::findOne($val['id']);
                //         $update_record_allotment_entries->record_allotment_id = $val['record_allotment_id'];
                //         $update_record_allotment_entries->chart_of_account_id = $val['chart_of_account_id'];
                //         $update_record_allotment_entries->amount = $val['amount'];

                //         if ($update_record_allotment_entries->save(false)) {
                //         } else {
                //             $transaction->rollBack();
                //             return 'not save in entries';
                //         }
                //     } else {
                //         $new_record_allotment_entries = new RecordAllotmentEntries();
                //         $new_record_allotment_entries->id = $val['id'];
                //         $new_record_allotment_entries->record_allotment_id = $val['record_allotment_id'];
                //         $new_record_allotment_entries->chart_of_account_id = $val['chart_of_account_id'];
                //         $new_record_allotment_entries->amount = $val['amount'];

                //         if ($new_record_allotment_entries->save(false)) {
                //         } else {
                //             $transaction->rollBack();
                //             return 'not save in entries';
                //         }
                //     }
                // }
                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'record_allotment_id',
                    'chart_of_account_id',
                    'amount',
                    'lvl',
                    'object_code',
                    'report_type',
                ];
                $data = [];

                foreach ($source_record_allotment_entries as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'record_allotment_id' => !empty($val['record_allotment_id']) ? Html::encode($val['record_allotment_id']) : null,
                        'chart_of_account_id' => !empty($val['chart_of_account_id']) ? Html::encode($val['chart_of_account_id']) : null,
                        'amount' => !empty($val['amount']) ? Html::encode($val['amount']) : null,
                        'lvl' => !empty($val['lvl']) ? Html::encode($val['lvl']) : null,
                        'object_code' => !empty($val['object_code']) ? Html::encode($val['object_code']) : '',
                        'report_type' => !empty($val['report_type']) ? Html::encode($val['report_type']) : '',
                    ];
                }

                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('record_allotment_entries', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                            record_allotment_id=VALUES(record_allotment_id),
                    chart_of_account_id=VALUES(chart_of_account_id),
                    amount=VALUES(amount),
                    lvl=VALUES(lvl),
                    object_code=VALUES(object_code),
                    report_type=VALUES(report_type)
                    ")->execute();
                    $entry_transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }


        return 'success';
    }
}
