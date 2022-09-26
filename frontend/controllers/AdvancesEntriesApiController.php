<?php

namespace frontend\controllers;

use common\models\AdvancesEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;

class AdvancesEntriesApiController extends \yii\rest\ActiveController
{
    public $modelClass = AdvancesEntries::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['update', 'delete', 'view', 'create', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['index']);
    }
    public function actionCreate()
    {
        $source_json = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_json)) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                // if ($flag = true) {
                //     foreach ($source_json as $val) {
                //         $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `advances_entries` WHERE id = :id)")
                //             ->bindValue(':id', $val['id'])
                //             ->queryScalar();
                //         if (intval($query) == 1) {
                //             $update_advances_entries = AdvancesEntries::findOne($val['id']);
                //             $update_advances_entries->advances_id = $val['advances_id'];
                //             $update_advances_entries->cash_disbursement_id = $val['cash_disbursement_id'];
                //             $update_advances_entries->sub_account1_id = $val['sub_account1_id'];
                //             $update_advances_entries->amount = $val['amount'];
                //             $update_advances_entries->object_code = $val['object_code'];
                //             $update_advances_entries->fund_source = $val['fund_source'];
                //             $update_advances_entries->book_id = $val['book_id'];
                //             $update_advances_entries->reporting_period = $val['reporting_period'];
                //             $update_advances_entries->fund_source_type = $val['fund_source_type'];
                //             $update_advances_entries->division = $val['division'];
                //             $update_advances_entries->advances_type = $val['advances_type'];
                //             $update_advances_entries->report_type = $val['report_type'];
                //             $update_advances_entries->is_deleted = $val['is_deleted'];

                //             if ($update_advances_entries->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag = false;
                //                 return 'failed in update';
                //                 die();
                //             }
                //         } else {
                //             $new_advances_entries = new AdvancesEntries();
                //             $new_advances_entries->id = $val['id'];
                //             $new_advances_entries->advances_id = $val['advances_id'];
                //             $new_advances_entries->cash_disbursement_id = $val['cash_disbursement_id'];
                //             $new_advances_entries->sub_account1_id = $val['sub_account1_id'];
                //             $new_advances_entries->amount = $val['amount'];
                //             $new_advances_entries->object_code = $val['object_code'];
                //             $new_advances_entries->fund_source = $val['fund_source'];
                //             $new_advances_entries->book_id = $val['book_id'];
                //             $new_advances_entries->reporting_period = $val['reporting_period'];
                //             $new_advances_entries->fund_source_type = $val['fund_source_type'];
                //             $new_advances_entries->division = $val['division'];
                //             $new_advances_entries->advances_type = $val['advances_type'];
                //             $new_advances_entries->report_type = $val['report_type'];
                //             $new_advances_entries->is_deleted = $val['is_deleted'];

                //             if ($new_advances_entries->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag = false;
                //                 return 'failed in new';
                //                 die();
                //             }
                //         }
                //     }
                // }
                // if ($flag) {
                //     $transaction->commit();
                //     return 'success commit';
                // } else {
                //     return 'yawa';
                // }
                $db = \Yii::$app->db;


                $columns = [
                    'id',
                    'advances_id',
                    'cash_disbursement_id',
                    'sub_account1_id',
                    'amount',
                    'object_code',
                    'fund_source',
                    'book_id',
                    'reporting_period',
                    'fund_source_type',
                    'division',
                    'advances_type',
                    'report_type',
                    'is_deleted',

                ];
                $data = [];

                foreach ($source_json as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'advances_id' => !empty($val['advances_id']) ? Html::encode($val['advances_id']) : null,
                        'cash_disbursement_id' => !empty($val['cash_disbursement_id']) ? Html::encode($val['cash_disbursement_id']) : null,
                        'sub_account1_id' => !empty($val['sub_account1_id']) ? Html::encode($val['sub_account1_id']) : null,
                        'amount' => !empty($val['amount']) ? Html::encode($val['amount']) : null,
                        'object_code' => !empty($val['object_code']) ? Html::encode($val['object_code']) : null,
                        'fund_source' => !empty($val['fund_source']) ? Html::encode($val['fund_source']) : null,
                        'book_id' => !empty($val['book_id']) ? Html::encode($val['book_id']) : null,
                        'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                        'fund_source_type' => !empty($val['fund_source_type']) ? Html::encode($val['fund_source_type']) : null,
                        'division' => !empty($val['division']) ? Html::encode($val['division']) : null,
                        'advances_type' => !empty($val['advances_type']) ? Html::encode($val['advances_type']) : null,
                        'report_type' => !empty($val['report_type']) ? Html::encode($val['report_type']) : null,
                        'is_deleted' => !empty($val['is_deleted']) ? Html::encode($val['is_deleted']) : null,
                    ];
                }
                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('advances_entries', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    advances_id=VALUES(advances_id),
                    cash_disbursement_id=VALUES(cash_disbursement_id),
                    sub_account1_id=VALUES(sub_account1_id),
                    amount=VALUES(amount),
                    object_code=VALUES(object_code),
                    fund_source=VALUES(fund_source),
                    book_id=VALUES(book_id),
                    reporting_period=VALUES(reporting_period),
                    fund_source_type=VALUES(fund_source_type),
                    division=VALUES(division),
                    advances_type=VALUES(advances_type),
                    report_type=VALUES(report_type),
                    is_deleted=VALUES(is_deleted)
                        ")->execute();
                    $transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
