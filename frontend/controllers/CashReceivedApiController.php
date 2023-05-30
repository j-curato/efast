<?php

namespace frontend\controllers;

use common\models\CashReceived;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class CashReceivedApiController extends \yii\rest\ActiveController
{
    public $modelClass = CashReceived::class;

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
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['update']);
    }
    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();
        $source_cash_receive = Yii::$app->getRequest()->getBodyParams();

        if (!empty($source_cash_receive)) {
            try {
                if ($flag = true) {

                    foreach ($source_cash_receive as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM cash_received WHERE cash_received.id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_cash_received = CashReceived::findOne($val['id']);
                            $update_cash_received->document_recieved_id = $val['document_recieved_id'];
                            $update_cash_received->book_id = $val['book_id'];
                            $update_cash_received->mfo_pap_code_id = $val['mfo_pap_code_id'];
                            $update_cash_received->date = $val['date'];
                            $update_cash_received->reporting_period = $val['reporting_period'];
                            $update_cash_received->nca_no = $val['nca_no'];
                            $update_cash_received->nta_no = $val['nta_no'];
                            $update_cash_received->nft_no = $val['nft_no'];
                            $update_cash_received->purpose = $val['purpose'];
                            $update_cash_received->amount = $val['amount'];
                            $update_cash_received->account_number = $val['account_number'];


                            if ($update_cash_received->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return json_encode('wala na save sa Document Recieve update');
                            }
                        } else {
                            $new_cash_received = new CashReceived();
                            $new_cash_received->id = $val['id'];
                            $new_cash_received->document_recieved_id = $val['document_recieved_id'];
                            $new_cash_received->book_id = $val['book_id'];
                            $new_cash_received->mfo_pap_code_id = $val['mfo_pap_code_id'];
                            $new_cash_received->date = $val['date'];
                            $new_cash_received->reporting_period = $val['reporting_period'];
                            $new_cash_received->nca_no = $val['nca_no'];
                            $new_cash_received->nta_no = $val['nta_no'];
                            $new_cash_received->nft_no = $val['nft_no'];
                            $new_cash_received->purpose = $val['purpose'];
                            $new_cash_received->amount = $val['amount'];
                            $new_cash_received->account_number = $val['account_number'];

                            if ($new_cash_received->save(false)) {
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
