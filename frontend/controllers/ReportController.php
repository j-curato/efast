<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\ArrayHelper;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPendingOrs()
    {

        return $this->render('pending_ors');
    }
    public function actionUnobligatedTransaction()
    {

        return $this->render('unobligated_transaction');
    }
    public function actionPendingDv()
    {

        return $this->render('pending_dv');
    }
    public function actionUnpaidObligation()
    {
        return $this->render('unpaid_obligation');
    }
    public function actionSaob()
    {

        $query = Yii::$app->db->createCommand("SELECT ors.total_obligation,
        ors.reporting_period,
        record_allotment_entries.id,
        record_allotment_entries.amount,
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger from record_allotment_entries,chart_of_accounts,(
        
        SELECT SUM(raoud_entries.amount) as total_obligation,
        raouds.record_allotment_entries_id,
        raouds.reporting_period
        
         from raouds,raoud_entries,chart_of_accounts
        where raouds.id = raoud_entries.raoud_id
        AND raouds.process_ors_id IS NOT NULL
        AND raouds.reporting_period IN('2021-01','2021-02')
        GROUP BY raouds.reporting_period,raouds.record_allotment_entries_id 
        ORDER BY raouds.reporting_period,raouds.record_allotment_entries_id ) as ors
        where record_allotment_entries.id = ors.record_allotment_entries_id
        AND record_allotment_entries.chart_of_account_id =chart_of_accounts.id
        
        ORDER BY record_allotment_entries.id
            
            ")->queryAll();
        $result = ArrayHelper::index($query, null, [function ($element) {
            return $element['uacs'];
        }, ]);

        
        ob_clean();
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
        return ob_get_clean();
        // return $this->render('saob',[
        //     'query'=>$query
        // ]);
    }


    public function actionGetCash()
    {
        $total_cash_disbursed = Yii::$app->db->createCommand("SELECT books.`name`,
         SUM(dv_aucs_entries.amount_disbursed)as total_disbursed 
        FROM cash_disbursement,dv_aucs,dv_aucs_entries,books
        WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
        AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
        AND cash_disbursement.book_id = books.id
        GROUP BY cash_disbursement.book_id")->queryAll();
        // $cash_recieved = Yii::$app->db->createCommand("SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved")->queryOne();
        $date = '2021-01-05';
        $query = (new \yii\db\Query())
            ->select([
                'SUM(dv_aucs_entries.amount_disbursed) as total_disbursed',
                "(SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved) as total_cash_recieved",
                "( (SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved) - SUM(dv_aucs_entries.amount_disbursed)) as cash_balance"
            ])
            ->from('cash_disbursement')
            ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id = dv_aucs.id')
            ->join('LEFT JOIN', 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
            // ->where('cash_disbursement.issuance_date =:issuance_date',['issuance_date'=>$date])
            ->one();

        $total_amount_pending = (new \yii\db\Query())
            ->select("SUM(dv_aucs_entries.amount_disbursed) as total_amount_pending")
            ->from('dv_aucs')
            ->join('LEFT JOIN', 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
            ->where("dv_aucs.id NOT IN 
              (SELECT DISTINCT cash_disbursement.dv_aucs_id from cash_disbursement WHERE cash_disbursement.dv_aucs_id IS NOT NULL)")
            ->one();
        $query['total_amount_pending'] = $total_amount_pending['total_amount_pending'];
        $query['cash_balance_per_accounting'] = $query['cash_balance'] - $total_amount_pending['total_amount_pending'];

        return json_encode($query);
    }

    public function actionSample()
    {
        $dv = (new \yii\db\Query())
            ->select('SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,SUM(dv_aucs_entries.vat_nonvat) as total_vat,
                    SUM(dv_aucs_entries.ewt_goods_services) as total_ewt,
                    SUM(dv_aucs_entries.compensation) as total_compensation
                    ')
            ->from('dv_aucs_entries')
            ->where('dv_aucs_entries.process_ors_id =:process_ors_id', ['process_ors_id' => 2008])
            ->one();

        return json_encode($dv);
    }
}
