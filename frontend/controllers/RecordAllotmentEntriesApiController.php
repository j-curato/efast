<?php

namespace frontend\controllers;

use common\models\RecordAllotmentEntries;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class RecordAllotmentEntriesApiController extends \yii\rest\ActiveController
{

    public $modelClass=RecordAllotmentEntries::class;

    public function behaviors()
    {
        $behaviors=parent::behaviors();
        $behaviors['authenticator']['only']=['update','delete','view','index','update'];
        $behaviors['authenticator']['authMehtods']=[
            HttpBearerAuth::class
        ];
        return $behaviors;
    }
    public function actions()
    {
        $actions=parent::actions();
        unset($actions['update']);
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['view']);
        unset($actions['index']);
    }
    public function actionCreate()
    {
        
        $transaction = Yii::$app->db->beginTransaction();
        $source_jason = Yii::$app->getRequest()->getBodyParams();
        $source_record_allotment_entries = $source_jason;
        $target_record_allotment_entries = Yii::$app->db->createCommand("SELECT * FROM `record_allotment_entries`")->queryAll();
        $source_record_allotment_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_record_allotment_entries), array_map('serialize', $target_record_allotment_entries))

        );

        if (!empty($source_record_allotment_entries_difference)) {
            try {

                if ($flag = true) {

                    foreach ($source_record_allotment_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `record_allotment_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_record_allotment_entries = RecordAllotmentEntries::findOne($val['id']);
                            $update_record_allotment_entries->record_allotment_id=$val['record_allotment_id'];
                            $update_record_allotment_entries->chart_of_account_id=$val['chart_of_account_id'];
                            $update_record_allotment_entries->amount=$val['amount'];
                            
                            if ($update_record_allotment_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;

                            }
                        } else {
                            $new_record_allotment_entries = new RecordAllotmentEntries();
                            $new_record_allotment_entries->id = $val['id'];
                            $new_record_allotment_entries->record_allotment_id=$val['record_allotment_id'];
                            $new_record_allotment_entries->chart_of_account_id=$val['chart_of_account_id'];
                            $new_record_allotment_entries->amount=$val['amount'];
                           
                            if ($new_record_allotment_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
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
        return 'success';

    }
}
