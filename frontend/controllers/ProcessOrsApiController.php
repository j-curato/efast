<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\filters\Cors;
use common\models\ProcessOrs;
use common\models\ProcessOrsEntries;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Html;

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
        return array_merge(['corsFilter' => Cors::class], $behaviors);
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
        // $target_process_ors = Yii::$app->db->createCommand("SELECT * FROM `process_ors`")->queryAll();
        // $source_process_ors_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_process_ors), array_map('serialize', $target_process_ors))

        // );
        if (!empty($source_process_ors)) {
            try {
                // if ($flag = true) {
                //     foreach ($source_process_ors as $val) {
                //         $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `process_ors` WHERE id = :id)")
                //             ->bindValue(':id', $val['id'])
                //             ->queryScalar();
                //         if (intval($query) === 1) {
                //             $update_process_ors = ProcessOrs::findOne($val['id']);
                //             $update_process_ors->transaction_id = $val['transaction_id'];
                //             $update_process_ors->reporting_period = $val['reporting_period'];
                //             $update_process_ors->serial_number = $val['serial_number'];
                //             $update_process_ors->obligation_number = $val['obligation_number'];
                //             $update_process_ors->funding_code = $val['funding_code'];
                //             $update_process_ors->document_recieve_id = $val['document_recieve_id'];
                //             $update_process_ors->mfo_pap_code_id = $val['mfo_pap_code_id'];
                //             $update_process_ors->fund_source_id = $val['fund_source_id'];
                //             $update_process_ors->book_id = $val['book_id'];
                //             $update_process_ors->date = $val['date'];
                //             $update_process_ors->is_cancelled = $val['is_cancelled'];
                //             $update_process_ors->type = $val['type'];
                //             $update_process_ors->created_at = $val['created_at'];
                //             $update_process_ors->transaction_begin_time = $val['transaction_begin_time'];

                //             if ($update_process_ors->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 return false;
                //             }
                //         } else {
                //             $new_process_ors = new ProcessOrs();
                //             $new_process_ors->id = $val['id'];
                //             $new_process_ors->transaction_id = $val['transaction_id'];
                //             $new_process_ors->reporting_period = $val['reporting_period'];
                //             $new_process_ors->serial_number = $val['serial_number'];
                //             $new_process_ors->obligation_number = $val['obligation_number'];
                //             $new_process_ors->funding_code = $val['funding_code'];
                //             $new_process_ors->document_recieve_id = $val['document_recieve_id'];
                //             $new_process_ors->mfo_pap_code_id = $val['mfo_pap_code_id'];
                //             $new_process_ors->fund_source_id = $val['fund_source_id'];
                //             $new_process_ors->book_id = $val['book_id'];
                //             $new_process_ors->date = $val['date'];
                //             $new_process_ors->is_cancelled = $val['is_cancelled'];
                //             $new_process_ors->type = $val['type'];
                //             $new_process_ors->created_at = $val['created_at'];
                //             $new_process_ors->transaction_begin_time = $val['transaction_begin_time'];
                //             if ($new_process_ors->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 return false;
                //             }
                //         }
                //     }
                // }

                // if ($flag) {
                //     $transaction->commit();
                // }
                $db = \Yii::$app->db;


                $columns = [
                    'id',
                    'transaction_id',
                    'reporting_period',
                    'serial_number',
                    'obligation_number',
                    'funding_code',
                    'document_recieve_id',
                    'mfo_pap_code_id',
                    'fund_source_id',
                    'book_id',
                    'date',
                    'is_cancelled',
                    'type',
                    'created_at',
                    'transaction_begin_time',

                ];
                $data = [];

                foreach ($source_process_ors as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'transaction_id' => !empty($val['transaction_id']) ? Html::encode($val['transaction_id']) : null,
                        'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                        'serial_number' => !empty($val['serial_number']) ? Html::encode($val['serial_number']) : null,
                        'obligation_number' => !empty($val['obligation_number']) ? Html::encode($val['obligation_number']) : null,
                        'funding_code' => !empty($val['funding_code']) ? Html::encode($val['funding_code']) : null,
                        'document_recieve_id' => !empty($val['document_recieve_id']) ? Html::encode($val['document_recieve_id']) : null,
                        'mfo_pap_code_id' => !empty($val['mfo_pap_code_id']) ? Html::encode($val['mfo_pap_code_id']) : null,
                        'fund_source_id' => !empty($val['fund_source_id']) ? Html::encode($val['fund_source_id']) : null,
                        'book_id' => !empty($val['book_id']) ? Html::encode($val['book_id']) : null,
                        'date' => !empty($val['date']) ? Html::encode($val['date']) : null,
                        'is_cancelled' => !empty($val['is_cancelled']) ? Html::encode($val['is_cancelled']) : null,
                        'type' => !empty($val['type']) ? Html::encode($val['type']) : null,
                        'created_at' => !empty($val['created_at']) ? Html::encode($val['created_at']) : null,
                        'transaction_begin_time' => !empty($val['transaction_begin_time']) ? Html::encode($val['transaction_begin_time']) : null,
                    ];
                }

                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('process_ors', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    transaction_id=VALUES(transaction_id),
                    reporting_period=VALUES(reporting_period),
                    serial_number=VALUES(serial_number),
                    obligation_number=VALUES(obligation_number),
                    funding_code=VALUES(funding_code),
                    document_recieve_id=VALUES(document_recieve_id),
                    mfo_pap_code_id=VALUES(mfo_pap_code_id),
                    fund_source_id=VALUES(fund_source_id),
                    book_id=VALUES(book_id),
                    date=VALUES(date),
                    is_cancelled=VALUES(is_cancelled),
                    type=VALUES(type),
                    created_at=VALUES(created_at),
                    transaction_begin_time=VALUES(transaction_begin_time)
                        ")->execute();
                    $transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

        $transaction = Yii::$app->db->beginTransaction();
        $source_process_ors_entries = $source_json['process_ors_entries'];
        // $target_process_ors_entries = Yii::$app->db->createCommand("SELECT * FROM `process_ors_entries`")->queryAll();
        // $source_process_ors_entries_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_process_ors_entries), array_map('serialize', $target_process_ors_entries))

        // );


        if (!empty($source_process_ors_entries)) {
            // try {

            //     if ($flag = true) {

            //         foreach ($process_ors_entries as $val) {
            //             $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `process_ors_entries` WHERE id = :id)")
            //                 ->bindValue(':id', $val['id'])
            //                 ->queryScalar();
            //             if (intval($query) === 1) {
            //                 $update_process_ors_entries = ProcessOrsEntries::findOne($val['id']);

            //                 $update_process_ors_entries->chart_of_account_id = $val['chart_of_account_id'];
            //                 $update_process_ors_entries->process_ors_id = $val['process_ors_id'];
            //                 $update_process_ors_entries->amount = $val['amount'];
            //                 $update_process_ors_entries->reporting_period = $val['reporting_period'];
            //                 $update_process_ors_entries->record_allotment_entries_id = $val['record_allotment_entries_id'];
            //                 $update_process_ors_entries->is_realign = $val['is_realign'];


            //                 if ($update_process_ors_entries->save(false)) {
            //                 } else {
            //                     $transaction->rollBack();
            //                     return false;
            //                 }
            //             } else {
            //                 $new_process_ors_entries = new ProcessOrsEntries();
            //                 $new_process_ors_entries->id = $val['id'];
            //                 $new_process_ors_entries->chart_of_account_id = $val['chart_of_account_id'];
            //                 $new_process_ors_entries->process_ors_id = $val['process_ors_id'];
            //                 $new_process_ors_entries->amount = $val['amount'];
            //                 $new_process_ors_entries->reporting_period = $val['reporting_period'];
            //                 $new_process_ors_entries->record_allotment_entries_id = $val['record_allotment_entries_id'];
            //                 $new_process_ors_entries->is_realign = $val['is_realign'];
            //                 if ($new_process_ors_entries->save(false)) {
            //                 } else {
            //                     $transaction->rollBack();
            //                     return false;
            //                 }
            //             }
            //         }
            //     }

            //     if ($flag) {
            //         $transaction->commit();
            //         return 'success s';
            //     }
            // } catch (ErrorException $e) {
            //     return json_encode('entries' . $e->getMessage());
            // }
            $db = \Yii::$app->db;


            $columns = [
                'id',
                'chart_of_account_id',
                'process_ors_id',
                'amount',
                'reporting_period',
                'record_allotment_entries_id',
                'is_realign',


            ];
            $data = [];

            foreach ($source_process_ors_entries as $val) {

                $data[] = [
                    'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                    'chart_of_account_id' => !empty($val['chart_of_account_id']) ? Html::encode($val['chart_of_account_id']) : null,
                    'process_ors_id' => !empty($val['process_ors_id']) ? Html::encode($val['process_ors_id']) : null,
                    'amount' => !empty($val['amount']) ? Html::encode($val['amount']) : null,
                    'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                    'record_allotment_entries_id' => !empty($val['record_allotment_entries_id']) ? Html::encode($val['record_allotment_entries_id']) : null,
                    'is_realign' => !empty($val['is_realign']) ? Html::encode($val['is_realign']) : null,
                ];
            }

            if (!empty($data)) {

                $sql = $db->queryBuilder->batchInsert('process_ors_entries', $columns, $data);
                $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                chart_of_account_id=VALUES(chart_of_account_id),
                process_ors_id=VALUES(process_ors_id),
                amount=VALUES(amount),
                reporting_period=VALUES(reporting_period),
                record_allotment_entries_id=VALUES(record_allotment_entries_id),
                is_realign=VALUES(is_realign)
                    ")->execute();
                $transaction->commit();
                return json_encode('succcecs');
            }
        }
        return 'success';
    }
}
