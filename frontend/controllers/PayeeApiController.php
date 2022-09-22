<?php

namespace frontend\controllers;

use common\models\Payee;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class PayeeApiController extends \yii\rest\ActiveController
{
    public $modelClass = Payee::class;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'view', 'index', 'update'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['index']);
        unset($actions['view']);
    }
    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();
        $source_jason = Yii::$app->getRequest()->getBodyParams();
        $source_payee = $source_jason;
        // $target_payee = Yii::$app->db->createCommand('SELECT * FROM payee')->queryAll();
        // $source_payee_difference = array_map(
        //     'unserialize',
        //     array_diff(array_map('serialize', $source_payee), array_map('serialize', $target_payee))

        // );
        // return json_encode($source_payee_difference);

        // var_dump($source_payee_difference);
        // return json_encode($source_payee_difference);

        if (!empty($source_payee)) {
            try {
                if ($flag = true) {


                    foreach ($source_payee as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM payee WHERE payee.id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $payee_tbl = Payee::findOne($val['id']);
                        } else {
                            $payee_tbl = new Payee();
                        }
                        $payee_tbl->id = $val['id'];
                        $payee_tbl->account_name = $val['account_name'];
                        $payee_tbl->registered_name = $val['registered_name'];
                        $payee_tbl->contact_person = $val['contact_person'];
                        $payee_tbl->registered_address = $val['registered_address'];
                        $payee_tbl->contact = $val['contact'];
                        $payee_tbl->remark = $val['remark'];
                        $payee_tbl->tin_number = $val['tin_number'];
                        $payee_tbl->isEnable = $val['isEnable'];
                        if ($payee_tbl->save(false)) {
                        } else {
                            $transaction->rollBack();
                            return 'failed';
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
        return json_encode('succcecss');
    }
}
