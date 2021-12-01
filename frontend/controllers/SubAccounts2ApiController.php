<?php

namespace frontend\controllers;

use common\models\SubAccounts2;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class SubAccounts2ApiController extends \yii\rest\ActiveController
{
    public $modelClass = SubAccounts2::class;

    public function behaviors()
    {
        $behaviors=parent::behaviors();
        $behaviors['authenticator']['only']=['create','delete','update'];
        $behaviors['authenticator']['authMethods']=[
            HttpBearerAuth::class
        ];
        return $behaviors;
    }
    public function actions()
    {
        $actions=parent::actions();
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['update']);
    }
    public function actionCreate()
    {
        
        $transaction=Yii::$app->db->beginTransaction();
        $source_sub_account2 = Yii::$app->getRequest()->getBodyParams();
        $target_sub_account2 = Yii::$app->db->createCommand('SELECT * FROM sub_accounts2')->queryAll();
        $source_sub_account2_diff = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_sub_account2), array_map('serialize', $target_sub_account2))
        );
        if (!empty($source_sub_account2_diff)) {
            try {
                if ($flag=true){

                foreach ($source_sub_account2_diff as $val) {
                    $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM sub_accounts2 WHERE sub_accounts2.id = :id)")
                        ->bindValue(':id', $val['id'])
                        ->queryScalar();
                    if (intval($query) === 1) {
                        $update_sub_account2 = SubAccounts2::findOne($val['id']);
                        $update_sub_account2->sub_accounts1_id = $val['sub_accounts1_id'];
                        $update_sub_account2->object_code = $val['object_code'];
                        $update_sub_account2->name = $val['name'];
                        $update_sub_account2->is_active = $val['is_active'];
                        if ($update_sub_account2->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return json_encode('wala na save sa Document Recieve update');
                        }
                    } else {
                        $new_sub_account2 = new SubAccounts2();
                        $new_sub_account2->id = $val['id'];
                        $new_sub_account2->sub_accounts1_id = $val['sub_accounts1_id'];
                        $new_sub_account2->object_code = $val['object_code'];
                        $new_sub_account2->name = $val['name'];
                        $new_sub_account2->is_active = $val['is_active'];
                        if ($new_sub_account2->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return 'wala na sulod  sa Document Recieve ';
                        }
                    }
                }
            }

            if($flag){
                $transaction->commit();
                return 'success';
            }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }

    }

}
