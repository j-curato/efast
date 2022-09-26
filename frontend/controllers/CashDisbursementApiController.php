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
                        'is_cancelled' => Html::encode($val['is_cancelled']),
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
