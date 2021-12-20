<?php

namespace frontend\controllers;

use common\models\AdvancesEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class AdvancesEntriesApiController extends \yii\rest\ActiveController
{
    public $modelClass = AdvancesEntries::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['update', 'delete', 'view', 'create', 'index'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['index']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_json)) {
            try {
                if ($flag = true) {
                    foreach ($source_json as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `advances_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_advances_entries = AdvancesEntries::findOne($val['id']);
                            $update_advances_entries->advances_id=$val['advances_id'];
                            $update_advances_entries->cash_disbursement_id=$val['cash_disbursement_id'];
                            $update_advances_entries->sub_account1_id=$val['sub_account1_id'];
                            $update_advances_entries->amount=$val['amount'];
                            $update_advances_entries->object_code=$val['object_code'];
                            $update_advances_entries->fund_source=$val['fund_source'];
                            $update_advances_entries->book_id=$val['book_id'];
                            $update_advances_entries->reporting_period=$val['reporting_period'];
                            $update_advances_entries->fund_source_type=$val['fund_source_type'];
                            $update_advances_entries->division=$val['division'];
                            $update_advances_entries->advances_type=$val['advances_type'];
                            $update_advances_entries->report_type=$val['report_type'];
                            $update_advances_entries->is_deleted=$val['is_deleted'];
                            
                            if ($update_advances_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_advances_entries = new AdvancesEntries();
                            $new_advances_entries->id=$val['id'];
                            $new_advances_entries->advances_id=$val['advances_id'];
                            $new_advances_entries->cash_disbursement_id=$val['cash_disbursement_id'];
                            $new_advances_entries->sub_account1_id=$val['sub_account1_id'];
                            $new_advances_entries->amount=$val['amount'];
                            $new_advances_entries->object_code=$val['object_code'];
                            $new_advances_entries->fund_source=$val['fund_source'];
                            $new_advances_entries->book_id=$val['book_id'];
                            $new_advances_entries->reporting_period=$val['reporting_period'];
                            $new_advances_entries->fund_source_type=$val['fund_source_type'];
                            $new_advances_entries->division=$val['division'];
                            $new_advances_entries->advances_type=$val['advances_type'];
                            $new_advances_entries->report_type=$val['report_type'];
                            $new_advances_entries->is_deleted=$val['is_deleted'];
                          
                            if ($new_advances_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
