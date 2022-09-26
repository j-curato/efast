<?php

namespace frontend\controllers;

use common\models\CashDisbursement;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;

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
        return array_merge(['corsFilter' => Cors::class], $behaviors);
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
        // $target_cash_disbursement = Yii::$app->db->createCommand("SELECT * FROM `cash_disbursement`")->queryAll();
        // $source_cash_disbursement_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_cash_disbursement), array_map('serialize', $target_cash_disbursement))

        // );


        if (!empty($source_cash_disbursement)) {
            try {

                // if ($flag = true) {

                //     foreach ($source_cash_disbursement as $val) {
                //         $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `cash_disbursement` WHERE id = :id)")
                //             ->bindValue(':id', $val['id'])
                //             ->queryScalar();
                //         if (intval($query) === 1) {
                //             $update_cash_disbursement = CashDisbursement::findOne($val['id']);
                //             $update_cash_disbursement->book_id = $val['book_id'];
                //             $update_cash_disbursement->dv_aucs_id = $val['dv_aucs_id'];
                //             $update_cash_disbursement->reporting_period = $val['reporting_period'];
                //             $update_cash_disbursement->mode_of_payment = $val['mode_of_payment'];
                //             $update_cash_disbursement->check_or_ada_no = $val['check_or_ada_no'];
                //             $update_cash_disbursement->is_cancelled = $val['is_cancelled'];
                //             $update_cash_disbursement->issuance_date = $val['issuance_date'];
                //             $update_cash_disbursement->ada_number = $val['ada_number'];
                //             $update_cash_disbursement->begin_time = $val['begin_time'];
                //             $update_cash_disbursement->out_time = $val['out_time'];
                //             $update_cash_disbursement->parent_disbursement = $val['parent_disbursement'];
                //             if ($update_cash_disbursement->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag=false;
                //                 return false;
                //             }
                //         } else {
                //             $new_cash_disbursement = new CashDisbursement();
                //             $new_cash_disbursement->id = $val['id'];
                //             $new_cash_disbursement->book_id = $val['book_id'];
                //             $new_cash_disbursement->dv_aucs_id = $val['dv_aucs_id'];
                //             $new_cash_disbursement->reporting_period = $val['reporting_period'];
                //             $new_cash_disbursement->mode_of_payment = $val['mode_of_payment'];
                //             $new_cash_disbursement->check_or_ada_no = $val['check_or_ada_no'];
                //             $new_cash_disbursement->is_cancelled = $val['is_cancelled'];
                //             $new_cash_disbursement->issuance_date = $val['issuance_date'];
                //             $new_cash_disbursement->ada_number = $val['ada_number'];
                //             $new_cash_disbursement->begin_time = $val['begin_time'];
                //             $new_cash_disbursement->out_time = $val['out_time'];
                //             $new_cash_disbursement->parent_disbursement = $val['parent_disbursement'];
                //             if ($new_cash_disbursement->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag=false;
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
                    'book_id',
                    'dv_aucs_id',
                    'reporting_period',
                    'mode_of_payment',
                    'check_or_ada_no',
                    'is_cancelled',
                    'issuance_date',
                    'ada_number',
                    'begin_time',
                    'out_time',
                    'parent_disbursement',
                    'created_at',

                ];
                $data = [];

                foreach ($source_cash_disbursement as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'book_id' => !empty($val['book_id']) ? Html::encode($val['book_id']) : null,
                        'dv_aucs_id' => !empty($val['dv_aucs_id']) ? Html::encode($val['dv_aucs_id']) : null,
                        'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                        'mode_of_payment' => !empty($val['mode_of_payment']) ? Html::encode($val['mode_of_payment']) : null,
                        'check_or_ada_no' => !empty($val['check_or_ada_no']) ? Html::encode($val['check_or_ada_no']) : null,
                        'is_cancelled' => !empty($val['is_cancelled']) ? Html::encode($val['is_cancelled']) : null,
                        'issuance_date' => !empty($val['issuance_date']) ? Html::encode($val['issuance_date']) : null,
                        'ada_number' => !empty($val['ada_number']) ? Html::encode($val['ada_number']) : null,
                        'begin_time' => !empty($val['begin_time']) ? Html::encode($val['begin_time']) : null,
                        'out_time' => !empty($val['out_time']) ? Html::encode($val['out_time']) : null,
                        'parent_disbursement' => !empty($val['parent_disbursement']) ? Html::encode($val['parent_disbursement']) : null,
                        'created_at' => !empty($val['created_at']) ? Html::encode($val['created_at']) : null,
                    ];
                }
                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('cash_disbursement', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    book_id=VALUES(book_id),
                    dv_aucs_id=VALUES(dv_aucs_id),
                    reporting_period=VALUES(reporting_period),
                    mode_of_payment=VALUES(mode_of_payment),
                    check_or_ada_no=VALUES(check_or_ada_no),
                    is_cancelled=VALUES(is_cancelled),
                    issuance_date=VALUES(issuance_date),
                    ada_number=VALUES(ada_number),
                    begin_time=VALUES(begin_time),
                    out_time=VALUES(out_time),
                    parent_disbursement=VALUES(parent_disbursement),
                    created_at=VALUES(created_at)
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
