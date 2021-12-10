<?php

namespace frontend\controllers;

use common\models\TrackingSheet;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class TrackingSheetApiController extends \yii\rest\ActiveController
{
    public $modelClass = TrackingSheet::class;
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
        unset($actions['index']);
        unset($actions['delete']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['view']);
    }
    public function actionCreate()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();
        $source_tracking_sheet = $source_json;
        $target_tracking_sheet = Yii::$app->db->createCommand("SELECT * FROM `tracking_sheet`")->queryAll();
        $source_tracking_sheet_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $source_tracking_sheet), array_map('serialize', $target_tracking_sheet))

        );


        if (!empty($source_json)) {
            try {

                if ($flag = true) {

                    foreach ($source_json as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `tracking_sheet` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_tracking_sheet = TrackingSheet::findOne($val['id']);

                        
                            $update_tracking_sheet->payee_id=$val['payee_id'];
                            $update_tracking_sheet->process_ors_id=$val['process_ors_id'];
                            $update_tracking_sheet->tracking_number=$val['tracking_number'];
                            $update_tracking_sheet->particular=$val['particular'];
                            $update_tracking_sheet->transaction_type=$val['transaction_type'];
                            $update_tracking_sheet->gross_amount=$val['gross_amount'];
                            $update_tracking_sheet->created_at=$val['created_at'];
                            
                            if ($update_tracking_sheet->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_tracking_sheet = new TrackingSheet();
                            $new_tracking_sheet->id=$val['id'];
                            $new_tracking_sheet->payee_id=$val['payee_id'];
                            $new_tracking_sheet->process_ors_id=$val['process_ors_id'];
                            $new_tracking_sheet->tracking_number=$val['tracking_number'];
                            $new_tracking_sheet->particular=$val['particular'];
                            $new_tracking_sheet->transaction_type=$val['transaction_type'];
                            $new_tracking_sheet->gross_amount=$val['gross_amount'];
                            $new_tracking_sheet->created_at=$val['created_at'];
                            if ($new_tracking_sheet->save(false)) {
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
