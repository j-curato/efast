<?php

namespace frontend\controllers;

use common\models\Advances;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class AdvancesApiController extends \yii\rest\ActiveController
{
    public $modelClass = Advances::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update', 'index', 'view'];
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
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `advances` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_advances = Advances::findOne($val['id']);
                            $update_advances->province=$val['province'];
                            $update_advances->report_type=$val['report_type'];
                            $update_advances->particular=$val['particular'];
                            $update_advances->nft_number=$val['nft_number'];
                            $update_advances->created_at=$val['created_at'];
                            $update_advances->reporting_period=$val['reporting_period'];
                            $update_advances->book_id=$val['book_id'];
                            $update_advances->advances_type=$val['advances_type'];
                            

                            if ($update_advances->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_advances = new Advances();
                            $new_advances->id=$val['id'];
                            $new_advances->province=$val['province'];
                            $new_advances->report_type=$val['report_type'];
                            $new_advances->particular=$val['particular'];
                            $new_advances->nft_number=$val['nft_number'];
                            $new_advances->created_at=$val['created_at'];
                            $new_advances->reporting_period=$val['reporting_period'];
                            $new_advances->book_id=$val['book_id'];
                            $new_advances->advances_type=$val['advances_type'];
                            if ($new_advances->save(false)) {
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
