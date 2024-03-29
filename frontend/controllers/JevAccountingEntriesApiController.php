<?php

namespace frontend\controllers;

use common\models\JevAccountingEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class JevAccountingEntriesApiController extends \yii\rest\ActiveController
{
    public $modelClass = JevAccountingEntries::class;

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
        $source = Yii::$app->getRequest()->getBodyParams();
        $source_jev_accounting_entries = $source['jev_accounting_entries'];
        $to_delete = $source['to_delete'];
        

        if(!empty($to_delete)){
            foreach($to_delete as $val){


                $q = JevAccountingEntries::findOne($val);
                $q->delete();
            }
        }




        if (!empty($source_jev_accounting_entries)) {
            try {
                if ($flag = true) {

                    foreach ($source_jev_accounting_entries as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM jev_accounting_entries WHERE jev_accounting_entries.id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_jev_accounting_entries = JevAccountingEntries::findOne($val['id']);
                            $update_jev_accounting_entries->jev_preparation_id = $val['jev_preparation_id'];
                            $update_jev_accounting_entries->cashflow_id = $val['cashflow_id'];
                            $update_jev_accounting_entries->net_asset_equity_id = $val['net_asset_equity_id'];
                            $update_jev_accounting_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $update_jev_accounting_entries->debit = $val['debit'];
                            $update_jev_accounting_entries->credit = $val['credit'];
                            $update_jev_accounting_entries->closing_nonclosing = $val['closing_nonclosing'];
                            $update_jev_accounting_entries->current_noncurrent = $val['current_noncurrent'];
                            $update_jev_accounting_entries->lvl = $val['lvl'];
                            $update_jev_accounting_entries->object_code = $val['object_code'];


                            if ($update_jev_accounting_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return json_encode('wala na save sa Document Recieve update');
                            }
                        } else {
                            $new_jev_accounting_entries = new JevAccountingEntries();
                            $new_jev_accounting_entries->id = $val['id'];
                            $new_jev_accounting_entries->jev_preparation_id = $val['jev_preparation_id'];
                            $new_jev_accounting_entries->cashflow_id = $val['cashflow_id'];
                            $new_jev_accounting_entries->net_asset_equity_id = $val['net_asset_equity_id'];
                            $new_jev_accounting_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $new_jev_accounting_entries->debit = $val['debit'];
                            $new_jev_accounting_entries->credit = $val['credit'];
                            $new_jev_accounting_entries->closing_nonclosing = $val['closing_nonclosing'];
                            $new_jev_accounting_entries->current_noncurrent = $val['current_noncurrent'];
                            $new_jev_accounting_entries->lvl = $val['lvl'];
                            $new_jev_accounting_entries->object_code = $val['object_code'];

                            if ($new_jev_accounting_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return 'wala na sulod  sa Document Recieve ';
                            }
                        }
                    }
                }

                if ($flag) {
                    $transaction->commit();
                    return 'success';
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
