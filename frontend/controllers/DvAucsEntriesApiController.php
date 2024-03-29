<?php

namespace frontend\controllers;

use common\models\DvAucsEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;

class DvAucsEntriesApiController extends \yii\rest\ActiveController
{
    public $modelClass = DvAucsEntries::class;
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
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['create']);
    }
    public function actionCreate()
    {

        $source_json = Yii::$app->getRequest()->getBodyParams();
        $source_dv_aucs_entries = $source_json['new_dv_aucs_entries'];
        // $target_dv_aucs_entries = Yii::$app->db->createCommand("SELECT * FROM `dv_aucs_entries`")->queryAll();
        // $source_dv_aucs_entries_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_dv_aucs_entries), array_map('serialize', $target_dv_aucs_entries))

        // );

        // return json_encode($source_json['to_delete']);
        if (!empty($source_json['to_delete'])) {
            $transaction = Yii::$app->db->beginTransaction();

            foreach ($source_json['to_delete'] as $val) {

                // return $val;
                $q = Yii::$app->db->createCommand("DELETE FROM dv_aucs_entries WHERE id = :id")->bindValue(':id', $val)->execute();
                // $q = DvAucsEntries::findOne($val);
                // return $q->id;
                // $q->delete();

            }
            $transaction->commit();
        }
        if (!empty($source_dv_aucs_entries)) {
            $transaction = Yii::$app->db->beginTransaction();

            try {

                // if ($flag = true) {

                //     foreach ($source_dv_aucs_entries as $val) {
                //         $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `dv_aucs_entries` WHERE id = :id)")
                //             ->bindValue(':id', $val['id'])
                //             ->queryScalar();
                //         if (intval($query) === 1) {
                //             $update_dv_aucs_entries = DvAucsEntries::findOne($val['id']);

                //             $update_dv_aucs_entries->dv_aucs_id = $val['dv_aucs_id'];
                //             $update_dv_aucs_entries->raoud_id = $val['raoud_id'];
                //             $update_dv_aucs_entries->amount_disbursed = $val['amount_disbursed'];
                //             $update_dv_aucs_entries->vat_nonvat = $val['vat_nonvat'];
                //             $update_dv_aucs_entries->ewt_goods_services = $val['ewt_goods_services'];
                //             $update_dv_aucs_entries->compensation = $val['compensation'];
                //             $update_dv_aucs_entries->other_trust_liabilities = $val['other_trust_liabilities'];
                //             $update_dv_aucs_entries->total_withheld = $val['total_withheld'];
                //             $update_dv_aucs_entries->process_ors_id = $val['process_ors_id'];

                //             if ($update_dv_aucs_entries->save(false)) {
                //             } else {
                //                 $transaction->rollBack();
                //                 $flag = false;
                //                 return false;
                //             }
                //         } else {
                //             $new_dv_aucs_entries = new DvAucsEntries();
                //             $new_dv_aucs_entries->id = $val['id'];
                //             $new_dv_aucs_entries->dv_aucs_id = $val['dv_aucs_id'];
                //             $new_dv_aucs_entries->raoud_id = $val['raoud_id'];
                //             $new_dv_aucs_entries->amount_disbursed = $val['amount_disbursed'];
                //             $new_dv_aucs_entries->vat_nonvat = $val['vat_nonvat'];
                //             $new_dv_aucs_entries->ewt_goods_services = $val['ewt_goods_services'];
                //             $new_dv_aucs_entries->compensation = $val['compensation'];
                //             $new_dv_aucs_entries->other_trust_liabilities = $val['other_trust_liabilities'];
                //             $new_dv_aucs_entries->total_withheld = $val['total_withheld'];
                //             $new_dv_aucs_entries->process_ors_id = $val['process_ors_id'];

                //             if ($new_dv_aucs_entries->save(false)) {
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
                //     return 'success ss';
                // }
                $db = \Yii::$app->db;


                $columns = [
                    'id',
                    'dv_aucs_id',
                    'raoud_id',
                    'amount_disbursed',
                    'vat_nonvat',
                    'ewt_goods_services',
                    'compensation',
                    'other_trust_liabilities',
                    'total_withheld',
                    'process_ors_id',

                ];
                $data = [];

                foreach ($source_dv_aucs_entries as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'dv_aucs_id' => !empty($val['dv_aucs_id']) ? Html::encode($val['dv_aucs_id']) : null,
                        'raoud_id' => !empty($val['raoud_id']) ? Html::encode($val['raoud_id']) : null,
                        'amount_disbursed' => !empty($val['amount_disbursed']) ? Html::encode($val['amount_disbursed']) : null,
                        'vat_nonvat' => !empty($val['vat_nonvat']) ? Html::encode($val['vat_nonvat']) : null,
                        'ewt_goods_services' => !empty($val['ewt_goods_services']) ? Html::encode($val['ewt_goods_services']) : null,
                        'compensation' => !empty($val['compensation']) ? Html::encode($val['compensation']) : null,
                        'other_trust_liabilities' => !empty($val['other_trust_liabilities']) ? Html::encode($val['other_trust_liabilities']) : null,
                        'total_withheld' => !empty($val['total_withheld']) ? Html::encode($val['total_withheld']) : null,
                        'process_ors_id' => !empty($val['process_ors_id']) ? Html::encode($val['process_ors_id']) : null,
                    ];
                }
                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('dv_aucs_entries', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    dv_aucs_id=VALUES(dv_aucs_id),
                    raoud_id=VALUES(raoud_id),
                    amount_disbursed=VALUES(amount_disbursed),
                    vat_nonvat=VALUES(vat_nonvat),
                    ewt_goods_services=VALUES(ewt_goods_services),
                    compensation=VALUES(compensation),
                    other_trust_liabilities=VALUES(other_trust_liabilities),
                    total_withheld=VALUES(total_withheld),
                    process_ors_id=VALUES(process_ors_id)

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
