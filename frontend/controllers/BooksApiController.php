<?php

namespace frontend\controllers;

use common\models\Books;
use ErrorException;
use Yii;
use yii\filters\RateLimiter;

class BooksApiController extends \yii\rest\ActiveController
{
    public $modelClass = Books::class;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['only'] = ['create', 'delete', 'update', 'index', 'view'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];
        $behaviors['rateLimiter'] = [
            'class' => \RazonYang\Yii2\RateLimiter\Redis\RateLimiter::class,
            'redis' => 'redis', // redis component name or definition
            'capacity' => 1,
            'rate' => 0.72,
            'limitPeriod' => 3600,
            'prefix' => 'rate_limiter:',
            'ttl' => 3600,
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
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `books` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $book = Books::findOne($val['id']);
                        } else {
                            $book = new Books();
                            $book->id = $val['id'];
                        }
                        $book->name = $val['name'];
                        $book->account_number = $val['account_number'];
                        $book->type = $val['type'];
                        if ($book->save(false)) {
                        } else {
                            $transaction->rollBack();
                            $flag = false;
                            return false;
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
