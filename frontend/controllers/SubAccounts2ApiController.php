<?php

namespace frontend\controllers;

use common\models\SubAccounts2;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class SubAccounts2ApiController extends \yii\rest\ActiveController
{
    public $modelClass = SubAccounts2::class;

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
        $source_sub_account2 = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_sub_account2)) {
            try {

                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'sub_accounts1_id',
                    'object_code',
                    'name',
                    'is_active',
                ];
                $data = [];
                foreach ($source_sub_account2 as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'sub_accounts1_id' => !empty($val['sub_accounts1_id']) ? Html::encode($val['sub_accounts1_id']) : null,
                        'object_code' => !empty($val['object_code']) ? Html::encode($val['object_code']) : null,
                        'name' => !empty($val['name']) ? HtmlPurifier::process($val['name']) : null,
                        'is_active' => Html::encode($val['is_active']),
                    ];
                }

                if (!empty($data)) {
                    $sql = $db->queryBuilder->batchInsert('sub_accounts2', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                        sub_accounts1_id=VALUES(sub_accounts1_id),
                        object_code=VALUES(object_code),
                        name=VALUES(name),
                        is_active=VALUES(is_active)
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
