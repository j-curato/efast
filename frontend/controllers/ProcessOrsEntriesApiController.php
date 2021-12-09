<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\filters\Cors;
use common\models\ProcessOrsEntries;
use yii\filters\auth\HttpBearerAuth;

class ProcessOrsEntriesApiController extends \yii\rest\ActiveController
{
    public $modelCLass = ProcessOrsEntries::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update', 'view', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter'=>Cors::class],$behaviors);
    }
    public function actions()
    {
        $action = parent::actions();
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();

        $source_process_ors_entries = Yii::$app->getRequest()->getBodyParams();
        $target_process_ors_entries = Yii::$app->db->createCommand("SELECT * FROM `process_ors_entries`")->queryAll();
        $source_process_ors_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_process_ors_entries), array_map('serialize', $target_process_ors_entries))

        );


        if (!empty($source_process_ors_entries_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_process_ors_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `process_ors_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_process_ors_entries = ProcessOrsEntries::findOne($val['id']);

                            $update_process_ors_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $update_process_ors_entries->process_ors_id = $val['process_ors_id'];
                            $update_process_ors_entries->amount = $val['amount'];
                            $update_process_ors_entries->reporting_period = $val['reporting_period'];
                            $update_process_ors_entries->record_allotment_entries_id = $val['record_allotment_entries_id'];
                            $update_process_ors_entries->is_realign = $val['is_realign'];


                            if ($update_process_ors_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_process_ors_entries = new ProcessOrsEntries();
                            $new_process_ors_entries->id = $val['id'];
                            $new_process_ors_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $new_process_ors_entries->process_ors_id = $val['process_ors_id'];
                            $new_process_ors_entries->amount = $val['amount'];
                            $new_process_ors_entries->reporting_period = $val['reporting_period'];
                            $new_process_ors_entries->record_allotment_entries_id = $val['record_allotment_entries_id'];
                            $new_process_ors_entries->is_realign = $val['is_realign'];
                            if ($new_process_ors_entries->save(false)) {
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
