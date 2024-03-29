<?php

namespace frontend\controllers;

use common\models\DvAccountingEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;

class DvAccountingEntriesApiController extends \yii\rest\ActiveController
{
    public $modelClass = DvAccountingEntries::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete', 'view', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['delete']);
        unset($actions['update']);
        unset($actions['view']);
        unset($actions['index']);
    }
    public function actionCreate()
    {
        $source_json = Yii::$app->getRequest()->getBodyParams();
        $source_dv_accounting_entries = $source_json['new_dv_accounting_entries'];
        // $target_dv_accounting_entries = Yii::$app->db->createCommand("SELECT * FROM `dv_accounting_entries`")->queryAll();
        // $source_dv_accounting_entries_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_dv_accounting_entries), array_map('serialize', $target_dv_accounting_entries))

        // );
        if (!empty($source_json['to_delete'])) {

            foreach ($source_json['to_delete'] as $val) {
                $q = Yii::$app->db->createCommand("DELETE FROM dv_accounting_entries WHERE id = :id")->bindValue(':id', $val)->execute();
            }
        }

        if (!empty($source_dv_accounting_entries)) {
            try {
                $transaction = Yii::$app->db->beginTransaction();

                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'dv_aucs_id',
                    'cashflow_id',
                    'net_asset_equity_id',
                    'chart_of_account_id',
                    'debit',
                    'credit',
                    'closing_nonclosing',
                    'current_noncurrent',
                    'object_code',
                    'payroll_id',
                    'remittance_payee_id',


                ];
                $data = [];


                foreach ($source_dv_accounting_entries as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'dv_aucs_id' => !empty($val['dv_aucs_id']) ? Html::encode($val['dv_aucs_id']) : null,
                        'cashflow_id' => !empty($val['cashflow_id']) ? Html::encode($val['cashflow_id']) : null,
                        'net_asset_equity_id' => !empty($val['net_asset_equity_id']) ? Html::encode($val['net_asset_equity_id']) : null,
                        'chart_of_account_id' => !empty($val['chart_of_account_id']) ? Html::encode($val['chart_of_account_id']) : null,
                        'debit' => !empty($val['debit']) ? Html::encode($val['debit']) : 0,
                        'credit' => !empty($val['credit']) ? Html::encode($val['credit']) : 0,
                        'closing_nonclosing' => !empty($val['closing_nonclosing']) ? Html::encode($val['closing_nonclosing']) : null,
                        'current_noncurrent' => !empty($val['current_noncurrent']) ? Html::encode($val['current_noncurrent']) : null,
                        'object_code' => !empty($val['object_code']) ? Html::encode($val['object_code']) : null,
                        'payroll_id' => !empty($val['payroll_id']) ? Html::encode($val['payroll_id']) : null,
                        'remittance_payee_id' => !empty($val['remittance_payee_id']) ? Html::encode($val['remittance_payee_id']) : null,
                    ];
                }
                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('dv_accounting_entries', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                    dv_aucs_id=VALUES(dv_aucs_id),
                    cashflow_id=VALUES(cashflow_id),
                    net_asset_equity_id=VALUES(net_asset_equity_id),
                    chart_of_account_id=VALUES(chart_of_account_id),
                    debit=VALUES(debit),
                    credit=VALUES(credit),
                    closing_nonclosing=VALUES(closing_nonclosing),
                    current_noncurrent=VALUES(current_noncurrent),
                    object_code=VALUES(object_code),
                    payroll_id=VALUES(payroll_id),
                    remittance_payee_id=VALUES(remittance_payee_id)

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
