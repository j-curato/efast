<?php

namespace frontend\controllers;

class PrFormsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionSuppliesLedgerCard()
    {
        return $this->render('supplies_ledger_card');
        
    }
    public function actionStockCard()
    {
        return $this->render('stock_card');

    }
    public function actionInventoryCustodianSlip()
    {
        return $this->render('inventory_custodian_slip');

    }
}
