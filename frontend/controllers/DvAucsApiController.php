<?php

namespace frontend\controllers;

use common\models\DvAucs;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

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
        // $target_dv_aucs = Yii::$app->db->createCommand("SELECT * FROM `dv_aucs`")->queryAll();
        // $source_dv_aucs_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_dv_aucs), array_map('serialize', $target_dv_aucs))

        // );


        if (!empty($source_dv_aucs)) {
            try {

                // if ($flag = true) {

                //     foreach ($source_dv_aucs as $val) {
                //         $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `dv_aucs` WHERE id = :id)")
                //             ->bindValue(':id', $val['id'])
                //             ->queryScalar();
                //         if (intval($query) === 1) {
                //             $update_dv_aucs = DvAucs::findOne($val['id']);

                //             $update_dv_aucs->dv_number = $val['dv_number'];
                //             $update_dv_aucs->reporting_period = $val['reporting_period'];
                //             $update_dv_aucs->tax_withheld = $val['tax_withheld'];
                //             $update_dv_aucs->other_trust_liability_withheld = $val['other_trust_liability_withheld'];
                //             $update_dv_aucs->net_amount_paid = $val['net_amount_paid'];
                //             $update_dv_aucs->mrd_classification_id = $val['mrd_classification_id'];
                //             $update_dv_aucs->nature_of_transaction_id = $val['nature_of_transaction_id'];
                //             $update_dv_aucs->particular = $val['particular'];
                //             $update_dv_aucs->payee_id = $val['payee_id'];
                //             $update_dv_aucs->transaction_type = $val['transaction_type'];
                //             $update_dv_aucs->book_id = $val['book_id'];
                //             $update_dv_aucs->is_cancelled = $val['is_cancelled'];
                //             $update_dv_aucs->created_at = $val['created_at'];
                //             $update_dv_aucs->dv_link = $val['dv_link'];
                //             $update_dv_aucs->transaction_begin_time = $val['transaction_begin_time'];
                //             $update_dv_aucs->return_timestamp = $val['return_timestamp'];
                //             $update_dv_aucs->out_timestamp = $val['out_timestamp'];
                //             $update_dv_aucs->accept_timestamp = $val['accept_timestamp'];
                //             $update_dv_aucs->tracking_sheet_id = $val['tracking_sheet_id'];
                //             $update_dv_aucs->in_timestamp = $val['in_timestamp'];



                //             if ($update_dv_aucs->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 return false;
                //             }
                //         } else {
                //             $new_dv_aucs = new DvAucs();
                //             $new_dv_aucs->id = $val['id'];
                //             $new_dv_aucs->dv_number = $val['dv_number'];
                //             $new_dv_aucs->reporting_period = $val['reporting_period'];
                //             $new_dv_aucs->tax_withheld = $val['tax_withheld'];
                //             $new_dv_aucs->other_trust_liability_withheld = $val['other_trust_liability_withheld'];
                //             $new_dv_aucs->net_amount_paid = $val['net_amount_paid'];
                //             $new_dv_aucs->mrd_classification_id = $val['mrd_classification_id'];
                //             $new_dv_aucs->nature_of_transaction_id = $val['nature_of_transaction_id'];
                //             $new_dv_aucs->particular = $val['particular'];
                //             $new_dv_aucs->payee_id = $val['payee_id'];
                //             $new_dv_aucs->transaction_type = $val['transaction_type'];
                //             $new_dv_aucs->book_id = $val['book_id'];
                //             $new_dv_aucs->is_cancelled = $val['is_cancelled'];
                //             $new_dv_aucs->created_at = $val['created_at'];
                //             $new_dv_aucs->dv_link = $val['dv_link'];
                //             $new_dv_aucs->transaction_begin_time = $val['transaction_begin_time'];
                //             $new_dv_aucs->return_timestamp = $val['return_timestamp'];
                //             $new_dv_aucs->out_timestamp = $val['out_timestamp'];
                //             $new_dv_aucs->accept_timestamp = $val['accept_timestamp'];
                //             $new_dv_aucs->tracking_sheet_id = $val['tracking_sheet_id'];
                //             $new_dv_aucs->in_timestamp = $val['in_timestamp'];
                //             if ($new_dv_aucs->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 return false;
                //             }
                //         }
                //     }
                // }

                // if ($flag) {
                //     $transaction->commit();
                //     return 'success s';
                // }
                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'dv_number',
                    'reporting_period',
                    'mrd_classification_id',
                    'nature_of_transaction_id',
                    'particular',
                    'payee_id',
                    'transaction_type',
                    'book_id',
                    'is_cancelled',
                    'created_at',
                    'dv_link',
                    'transaction_begin_time',
                    'return_timestamp',
                    'out_timestamp',
                    'accept_timestamp',
                    'tracking_sheet_id',
                    'in_timestamp',
                    'is_payable',
                    'recieved_at',
                    'payroll_id',
                    'fk_remittance_id',
                    'fk_ro_alphalist_id',
                    'object_code',

                ];
                $data = [];

                foreach ($source_dv_aucs as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'dv_number' => !empty($val['dv_number']) ? Html::encode($val['dv_number']) : null,
                        'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                        'mrd_classification_id' => !empty($val['mrd_classification_id']) ? Html::encode($val['mrd_classification_id']) : null,
                        'nature_of_transaction_id' => !empty($val['nature_of_transaction_id']) ? Html::encode($val['nature_of_transaction_id']) : null,
                        'particular' => !empty($val['particular']) ? HtmlPurifier::process($val['particular']) : null,
                        'payee_id' => !empty($val['payee_id']) ? Html::encode($val['payee_id']) : null,
                        'transaction_type' => !empty($val['transaction_type']) ? Html::encode($val['transaction_type']) : null,
                        'book_id' => !empty($val['book_id']) ? Html::encode($val['book_id']) : null,
                        'is_cancelled' =>  Html::encode($val['is_cancelled']),
                        'created_at' => !empty($val['created_at']) ? Html::encode($val['created_at']) : null,
                        'dv_link' => Html::encode($val['dv_link']),
                        'transaction_begin_time' => !empty($val['transaction_begin_time']) ? Html::encode($val['transaction_begin_time']) : null,
                        'return_timestamp' => !empty($val['return_timestamp']) ? Html::encode($val['return_timestamp']) : null,
                        'out_timestamp' => !empty($val['out_timestamp']) ? Html::encode($val['out_timestamp']) : null,
                        'accept_timestamp' => !empty($val['accept_timestamp']) ? Html::encode($val['accept_timestamp']) : null,
                        'tracking_sheet_id' => !empty($val['tracking_sheet_id']) ? Html::encode($val['tracking_sheet_id']) : null,
                        'in_timestamp' => !empty($val['in_timestamp']) ? Html::encode($val['in_timestamp']) : null,
                        'is_payable' =>  Html::encode($val['is_payable']),
                        'recieved_at' => !empty($val['recieved_at']) ? Html::encode($val['recieved_at']) : null,
                        'payroll_id' => !empty($val['payroll_id']) ? Html::encode($val['payroll_id']) : null,
                        'fk_remittance_id' => !empty($val['fk_remittance_id']) ? Html::encode($val['fk_remittance_id']) : null,
                        'fk_ro_alphalist_id' => !empty($val['fk_ro_alphalist_id']) ? Html::encode($val['fk_ro_alphalist_id']) : null,
                        'object_code' => !empty($val['object_code']) ? HtmlPurifier::process($val['object_code']) : '',


                    ];
                }

                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('dv_aucs', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    dv_number=VALUES(dv_number),
                    reporting_period=VALUES(reporting_period),
                    mrd_classification_id=VALUES(mrd_classification_id),
                    nature_of_transaction_id=VALUES(nature_of_transaction_id),
                    particular=VALUES(particular),
                    payee_id=VALUES(payee_id),
                    transaction_type=VALUES(transaction_type),
                    book_id=VALUES(book_id),
                    is_cancelled=VALUES(is_cancelled),
                    created_at=VALUES(created_at),
                    dv_link=VALUES(dv_link),
                    transaction_begin_time=VALUES(transaction_begin_time),
                    return_timestamp=VALUES(return_timestamp),
                    out_timestamp=VALUES(out_timestamp),
                    accept_timestamp=VALUES(accept_timestamp),
                    tracking_sheet_id=VALUES(tracking_sheet_id),
                    in_timestamp=VALUES(in_timestamp),
                    is_payable=VALUES(is_payable),
                    recieved_at=VALUES(recieved_at),
                    payroll_id=VALUES(payroll_id),
                    fk_remittance_id=VALUES(fk_remittance_id),
                    fk_ro_alphalist_id=VALUES(fk_ro_alphalist_id),
                    object_code=VALUES(object_code)
                ")->execute();
                    $transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        return 'success';
    }
}
