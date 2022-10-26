<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\filters\Cors;
use common\models\ChartOfAccounts;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class ChartOfAccountsApiController extends \yii\rest\ActiveController
{

    public $modelClass = ChartOfAccounts::class;
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
        unset($actions['create']);
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['delete']);
        unset($actions['update']);
    }

    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();
        $chart_of_accounts = Yii::$app->getRequest()->getBodyParams();
        if (!empty($chart_of_accounts)) {
            try {

                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'uacs',
                    'general_ledger',
                    'major_account_id',
                    'sub_major_account',
                    'sub_major_account_2_id',
                    'account_group',
                    'current_noncurrent',
                    'enable_disable',
                    'normal_balance',
                    'is_active',
                    'is_province_visible',
                    'fk_depreciation_id',
                    'fk_impairment_id',
                    'fk_ppe_useful_life_id',

                ];
                $data = [];
                foreach ($chart_of_accounts as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'uacs' => !empty($val['uacs']) ? HtmlPurifier::process($val['uacs']) : null,
                        'general_ledger' => !empty($val['general_ledger']) ? HtmlPurifier::process($val['general_ledger']) : null,
                        'major_account_id' => !empty($val['major_account_id']) ? Html::encode($val['major_account_id']) : null,
                        'sub_major_account' => !empty($val['sub_major_account']) ? Html::encode($val['sub_major_account']) : null,
                        'sub_major_account_2_id' => !empty($val['sub_major_account_2_id']) ? Html::encode($val['sub_major_account_2_id']) : null,
                        'account_group' => !empty($val['account_group']) ? HtmlPurifier::process($val['account_group']) : null,
                        'current_noncurrent' => !empty($val['current_noncurrent']) ? HtmlPurifier::process($val['current_noncurrent']) : null,
                        'enable_disable' => Html::encode($val['enable_disable']),
                        'normal_balance' => !empty($val['normal_balance']) ? HtmlPurifier::process($val['normal_balance']) : null,
                        'is_active' => Html::encode($val['is_active']),
                        'is_province_visible' => Html::encode($val['is_province_visible']),
                        'fk_depreciation_id' => !empty($val['fk_depreciation_id']) ? Html::encode($val['fk_depreciation_id']) : null,
                        'fk_impairment_id' => !empty($val['fk_impairment_id']) ? Html::encode($val['fk_impairment_id']) : null,
                        'fk_ppe_useful_life_id' => !empty($val['fk_ppe_useful_life_id']) ? Html::encode($val['fk_ppe_useful_life_id']) : null,

                    ];
                }

                if (!empty($data)) {
                    $sql = $db->queryBuilder->batchInsert('chart_of_accountrs', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                        uacs=VALUES(uacs),
                        general_ledger=VALUES(general_ledger),
                        major_account_id=VALUES(major_account_id),
                        sub_major_account=VALUES(sub_major_account),
                        sub_major_account_2_id=VALUES(sub_major_account_2_id),
                        account_group=VALUES(account_group),
                        current_noncurrent=VALUES(current_noncurrent),
                        enable_disable=VALUES(enable_disable),
                        normal_balance=VALUES(normal_balance),
                        is_active=VALUES(is_active),
                        is_province_visible=VALUES(is_province_visible),
                        fk_depreciation_id=VALUES(fk_depreciation_id),
                        fk_impairment_id=VALUES(fk_impairment_id),
                        fk_ppe_useful_life_id=VALUES(fk_ppe_useful_life_id)
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
