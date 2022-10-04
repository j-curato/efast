<?php

namespace frontend\controllers;

use common\models\SubAccounts1;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

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

        $source_sub_account1 = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_sub_account1)) {
            try {

                $db = \Yii::$app->db;
                $columns = [
                    'id',
                    'chart_of_account_id',
                    'object_code',
                    'name',
                    'is_active',
                ];
                $data = [];
                foreach ($source_sub_account1 as $val) {

                    $data[] = [
                        'id' => !empty($val['id']) ? Html::encode($val['id']) : null,
                        'chart_of_account_id' => !empty($val['chart_of_account_id']) ? Html::encode($val['chart_of_account_id']) : null,
                        'object_code' => !empty($val['object_code']) ? Html::encode($val['object_code']) : null,
                        'name' => !empty($val['name']) ? HtmlPurifier::process($val['name']) : null,
                        'is_active' => Html::encode($val['is_active']),
                    ];
                }

                if (!empty($data)) {

                    $sql = $db->queryBuilder->batchInsert('sub_accounts1', $columns, $data);
                    $db->createCommand($sql . "ON DUPLICATE KEY UPDATE
                        chart_of_account_id=VALUES(chart_of_account_id),
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
