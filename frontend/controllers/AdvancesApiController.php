<?php

namespace frontend\controllers;

use common\models\Advances;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;

class AdvancesApiController extends \yii\rest\ActiveController
{
    public $modelClass = Advances::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update', 'index', 'view'];
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

        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_json)) {
            try {
                // if ($flag = true) {
                //     foreach ($source_json as $val) {
                //         $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `advances` WHERE id = :id)")
                //             ->bindValue(':id', $val['id'])
                //             ->queryScalar();
                //         if (intval($query) === 1) {
                //             $update_advances = Advances::findOne($val['id']);
                //             $update_advances->province = $val['province'];
                //             $update_advances->report_type = $val['report_type'];
                //             $update_advances->particular = $val['particular'];
                //             $update_advances->nft_number = $val['nft_number'];
                //             $update_advances->created_at = $val['created_at'];
                //             $update_advances->reporting_period = $val['reporting_period'];
                //             $update_advances->book_id = $val['book_id'];
                //             $update_advances->advances_type = $val['advances_type'];
                //             $update_advances->dv_aucs_id = $val['dv_aucs_id'];
                //             $update_advances->bank_account_id = $val['bank_account_id'];


                //             if ($update_advances->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag = false;
                //                 return false;
                //             }
                //         } else {
                //             $new_advances = new Advances();
                //             $new_advances->id = $val['id'];
                //             $new_advances->province = $val['province'];
                //             $new_advances->report_type = $val['report_type'];
                //             $new_advances->particular = $val['particular'];
                //             $new_advances->nft_number = $val['nft_number'];
                //             $new_advances->created_at = $val['created_at'];
                //             $new_advances->reporting_period = $val['reporting_period'];
                //             $new_advances->book_id = $val['book_id'];
                //             $new_advances->advances_type = $val['advances_type'];
                //             $new_advances->bank_account_id = $val['bank_account_id'];
                //             $new_advances->dv_aucs_id = $val['dv_aucs_id'];
                //             if ($new_advances->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag = false;
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
                    'province',
                    'report_type',
                    'particular',
                    'nft_number',
                    'created_at',
                    'reporting_period',
                    'book_id',
                    'advances_type',
                    'bank_account_id',
                    'dv_aucs_id',

                ];
                $data = [];

                foreach ($source_json as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'province' => !empty($val['province']) ? Html::encode($val['province']) : null,
                        'report_type' => !empty($val['report_type']) ? Html::encode($val['report_type']) : null,
                        'particular' => !empty($val['particular']) ? Html::encode($val['particular']) : null,
                        'nft_number' => !empty($val['nft_number']) ? Html::encode($val['nft_number']) : null,
                        'created_at' => !empty($val['created_at']) ? Html::encode($val['created_at']) : null,
                        'reporting_period' => !empty($val['reporting_period']) ? Html::encode($val['reporting_period']) : null,
                        'book_id' => !empty($val['book_id']) ? Html::encode($val['book_id']) : null,
                        'advances_type' => !empty($val['advances_type']) ? Html::encode($val['advances_type']) : null,
                        'bank_account_id' => !empty($val['bank_account_id']) ? Html::encode($val['bank_account_id']) : null,
                        'dv_aucs_id' => !empty($val['dv_aucs_id']) ? Html::encode($val['dv_aucs_id']) : null,
                    ];
                }
                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('advances', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    province=VALUES(province),
                    report_type=VALUES(report_type),
                    particular=VALUES(particular),
                    nft_number=VALUES(nft_number),
                    created_at=VALUES(created_at),
                    reporting_period=VALUES(reporting_period),
                    book_id=VALUES(book_id),
                    advances_type=VALUES(advances_type),
                    bank_account_id=VALUES(bank_account_id),
                    dv_aucs_id=VALUES(dv_aucs_id)

                        ")->execute();
                    $transaction->commit();
                    return json_encode('succcecs');
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
            return json_encode('succces');
        }
    }
}
