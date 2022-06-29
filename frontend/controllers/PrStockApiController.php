<?php

namespace frontend\controllers;

use common\models\PrStock;
use ErrorException;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class PrStockApiController extends \yii\rest\ActiveController
{
    public $modelClass = PrStock::class;
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
        $source_json = Yii::$app->getRequest()->getBodyParams();
        if (!empty($source_json)) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                if ($flag = true) {
                    foreach ($source_json as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `pr_stock` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) == 1) {
                            $stock = PrStock::findOne($val['id']);
                        } else {
                            $stock = new PrStock();
                            $stock->id = $val['id'];
                        }
                        $stock->stock_title = $val['stock_title'];
                        $stock->bac_code = $val['bac_code'];
                        $stock->unit_of_measure_id = $val['unit_of_measure_id'];
                        $stock->amount = $val['amount'];
                        $stock->chart_of_account_id = $val['chart_of_account_id'];
                        $stock->part = $val['part'];
                        $stock->type = $val['type'];
                        $stock->created_at = $val['created_at'];
                        $stock->is_final = $val['is_final'];

                        if ($stock->save(false)) {
                            return 'success';
                        } else {
                            $transaction->rollBack();
                            $flag = false;
                            return 'failed in new';
                            die();
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    return 'success commit';
                } else {
                    return 'qwe';
                }
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
    }
}
