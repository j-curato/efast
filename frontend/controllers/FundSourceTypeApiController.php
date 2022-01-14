<?php

namespace frontend\controllers;

use common\models\FundSourceType;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class FundSourceTypeApiController extends \yii\rest\ActiveController
{

    public $modelClass = FundSourceType::class;

    public function behaviors()
    {
        $behaviors  = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'update', 'delete', 'index', 'view'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        return array_merge(['corsFilter' => Cors::class], $behaviors);
    }
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['delete']);
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['update']);
    }
    public function actionCreate()
    {

        $transaction = Yii::$app->db->beginTransaction();
        $source_json = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_json)) {
            try {
                if ($flag = true) {
                    foreach ($source_json as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `fund_source_type` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_fund_source_type = FundSourceType::findOne($val['id']);
                            $update_fund_source_type->name = $val['name'];
                            $update_fund_source_type->division = $val['division'];


                            if ($update_fund_source_type->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return false;
                            }
                        } else {
                            $new_fund_source_type = new FundSourceType();
                            $new_fund_source_type->id = $val['id'];
                            $new_fund_source_type->name = $val['name'];
                            $new_fund_source_type->division = $val['division'];

                            if ($new_fund_source_type->save(false)) {
                            } else {
                                $transaction->rollBack();
                                $flag = false;
                                return false;
                            }
                        }
                    }
                }

                if ($flag) {

                    $transaction->commit();
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
