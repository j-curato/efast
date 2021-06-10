<?php

namespace frontend\controllers;

class DatabaseViewController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionSubAccounts()
    {
        $res = (new \yii\db\Query())
            ->select("*")
            ->from('sub_accounts_view')
            ->all();
        return json_encode($res);
    }
}
