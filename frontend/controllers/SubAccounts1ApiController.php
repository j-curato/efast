<?php

namespace frontend\controllers;

use common\models\SubAccounts1;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class SubAccounts1ApiController extends \yii\rest\ActiveController
{
    public $modelClass = SubAccounts1::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter'=>Cors::class],$behaviors);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
    }
    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();

        $source_sub_account1 = Yii::$app->getRequest()->getBodyParams();
        $target_sub_account1 = Yii::$app->db->createCommand('SELECT * FROM sub_accounts1')->queryAll();
        $source_sub_account1_diff = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_sub_account1), array_map('serialize', $target_sub_account1))
        );
        if (!empty($source_sub_account1_diff)) {
            try {
                if ($flag=true)
                foreach ($source_sub_account1_diff as $val) {
                    $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM sub_accounts1 WHERE sub_accounts1.id = :id)")
                        ->bindValue(':id', $val['id'])
                        ->queryScalar();
                    if (intval($query) === 1) {
                        $update_subAccount1 = SubAccounts1::findOne($val['id']);
                        $update_subAccount1->chart_of_account_id = $val['chart_of_account_id'];
                        $update_subAccount1->object_code = $val['object_code'];
                        $update_subAccount1->name = $val['name'];
                        $update_subAccount1->is_active = $val['is_active'];

                        if ($update_subAccount1->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return json_encode('wala na save');
                        }
                    } else {
                        $new_subAccounts1 = new SubAccounts1();
                        $new_subAccounts1->id = $val['id'];
                        $new_subAccounts1->chart_of_account_id = $val['chart_of_account_id'];
                        $new_subAccounts1->object_code = $val['object_code'];
                        $new_subAccounts1->name = $val['name'];
                        $new_subAccounts1->is_active = $val['is_active'];
                        if ($new_subAccounts1->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return 'wala na sulod  sa chart of accounts';
                        }
                    }
                }
                if ($flag){
                    $transaction->commit();
                    return 'success';
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
