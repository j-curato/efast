<?php

namespace frontend\controllers;

use Yii;
use ErrorException;
use yii\filters\Cors;
use common\models\ChartOfAccounts;
use yii\filters\auth\HttpBearerAuth;

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
    }

    public function actionCreate()

    {

        $transaction = Yii::$app->db->beginTransaction();
        $source_chart_of_accounts = Yii::$app->getRequest()->getBodyParams();
        $target_chart_of_accounts = Yii::$app->db->createCommand('SELECT * FROM chart_of_accounts')->queryAll();
        $source_chart_of_accounts_diff = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_chart_of_accounts), array_map('serialize', $target_chart_of_accounts))
        );
        if (!empty($source_chart_of_accounts_diff)) {
            try {
                if ($flag = true) {
                    foreach ($source_chart_of_accounts_diff as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM chart_of_accounts WHERE chart_of_accounts.id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $p = ChartOfAccounts::findOne($val['id']);
                            $p->uacs = $val['uacs'];
                            $p->general_ledger = $val['general_ledger'];
                            $p->major_account_id = $val['major_account_id'];
                            $p->sub_major_account = $val['sub_major_account'];
                            $p->sub_major_account_2_id = $val['sub_major_account_2_id'];
                            $p->account_group = $val['account_group'];
                            $p->current_noncurrent = $val['current_noncurrent'];
                            $p->enable_disable = $val['enable_disable'];
                            $p->normal_balance = $val['normal_balance'];
                            $p->is_active = $val['is_active'];
                            if ($p->save(false)) {
                            } else {
                                return 'wala na save';
                            }
                        } else {
                            $new_chart = new ChartOfAccounts();

                            $new_chart->id = $val['id'];
                            $new_chart->uacs = $val['uacs'];
                            $new_chart->general_ledger = $val['general_ledger'];
                            $new_chart->major_account_id = $val['major_account_id'];
                            $new_chart->sub_major_account = $val['sub_major_account'];
                            $new_chart->sub_major_account_2_id = $val['sub_major_account_2_id'];
                            $new_chart->account_group = $val['account_group'];
                            $new_chart->current_noncurrent = $val['current_noncurrent'];
                            $new_chart->enable_disable = $val['enable_disable'];
                            $new_chart->normal_balance = $val['normal_balance'];
                            $new_chart->is_active = $val['is_active'];

                            if ($new_chart->save(false)) {
                            } else {
                                return 'wala na sulod  sa chart of accounts';
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
