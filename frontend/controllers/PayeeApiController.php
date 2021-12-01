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
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
          return array_merge([
            'corsFilter' => Cors::class,
        ], $behaviors);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
    }
    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();
        $source_jason = Yii::$app->getRequest()->getBodyParams();
        $source_payee = $source_jason;
        $target_payee = Yii::$app->db->createCommand('SELECT * FROM payee')->queryAll();
        $source_payee_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_payee), array_map('serialize', $target_payee))

        );
        // return json_encode($source_payee_difference);

        // var_dump($source_payee_difference);
        // return json_encode($source_payee_difference);

        if (!empty($source_payee_difference)) {
            try {
                if ($flag = true) {


                    foreach ($source_payee_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM payee WHERE payee.id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_payee = Payee::findOne($val['id']);
                            $update_payee->id = $val['id'];
                            $update_payee->account_name = $val['account_name'];
                            $update_payee->registered_name = $val['registered_name'];
                            $update_payee->contact_person = $val['contact_person'];
                            $update_payee->registered_address = $val['registered_address'];
                            $update_payee->contact = $val['contact'];
                            $update_payee->remark = $val['remark'];
                            $update_payee->tin_number = $val['tin_number'];
                            $update_payee->isEnable = $val['isEnable'];
                            if ($update_payee->save(false)) {
                            } else {
                                return json_encode('wala na save');
                            }
                        } else {
                            $new_payee = new Payee();
                            $new_payee->id = $val['id'];
                            $new_payee->account_name = $val['account_name'];
                            $new_payee->registered_name = $val['registered_name'];
                            $new_payee->contact_person = $val['contact_person'];
                            $new_payee->registered_address = $val['registered_address'];
                            $new_payee->contact = $val['contact'];
                            $new_payee->remark = $val['remark'];
                            $new_payee->tin_number = $val['tin_number'];
                            $new_payee->isEnable = $val['isEnable'];
                            if ($new_payee->save(false)) {
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
        return json_encode('succcecss');
    }
}
