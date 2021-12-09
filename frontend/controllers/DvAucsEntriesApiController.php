<?php

namespace frontend\controllers;

use common\models\DvAucsEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class DvAucsEntriesApiController extends \yii\rest\ActiveController
{
    public $modelClass=DvAucsEntries::class;
    public function behaviors()
    {
        $behaviors=parent::behaviors();
        $behaviors['authenticator']['only']=['create','delete','update','view','index'];
        $behaviors['authenticator']['authMethods']=[
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter'=>Cors::class],$behaviors);
    }
    public function actions()
    {
        $actions=parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['create']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_dv_aucs_entries = Yii::$app->getRequest()->getBodyParams();
        $target_dv_aucs_entries = Yii::$app->db->createCommand("SELECT * FROM `dv_aucs_entries`")->queryAll();
        $source_dv_aucs_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_dv_aucs_entries), array_map('serialize', $target_dv_aucs_entries))

        );


        if (!empty($source_dv_aucs_entries_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_dv_aucs_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `dv_aucs_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_dv_aucs_entries = DvAucsEntries::findOne($val['id']);

                            $update_dv_aucs_entries->dv_aucs_id=$val['dv_aucs_id'];
                            $update_dv_aucs_entries->raoud_id=$val['raoud_id'];
                            $update_dv_aucs_entries->amount_disbursed=$val['amount_disbursed'];
                            $update_dv_aucs_entries->vat_nonvat=$val['vat_nonvat'];
                            $update_dv_aucs_entries->ewt_goods_services=$val['ewt_goods_services'];
                            $update_dv_aucs_entries->compensation=$val['compensation'];
                            $update_dv_aucs_entries->other_trust_liabilities=$val['other_trust_liabilities'];
                            $update_dv_aucs_entries->total_withheld=$val['total_withheld'];
                            $update_dv_aucs_entries->process_ors_id=$val['process_ors_id'];

                            if ($update_dv_aucs_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_dv_aucs_entries = new DvAucsEntries();
                            $new_dv_aucs_entries->id = $val['id'];
                            $new_dv_aucs_entries->dv_aucs_id=$val['dv_aucs_id'];
                            $new_dv_aucs_entries->raoud_id=$val['raoud_id'];
                            $new_dv_aucs_entries->amount_disbursed=$val['amount_disbursed'];
                            $new_dv_aucs_entries->vat_nonvat=$val['vat_nonvat'];
                            $new_dv_aucs_entries->ewt_goods_services=$val['ewt_goods_services'];
                            $new_dv_aucs_entries->compensation=$val['compensation'];
                            $new_dv_aucs_entries->other_trust_liabilities=$val['other_trust_liabilities'];
                            $new_dv_aucs_entries->total_withheld=$val['total_withheld'];
                            $new_dv_aucs_entries->process_ors_id=$val['process_ors_id'];
                           
                            if ($new_dv_aucs_entries->save(false)) {
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
