<?php

namespace frontend\controllers;

use app\models\CashDisbursementDisableReportingPeriod;
use app\models\DvAucsDisableReportingPeriod;
use app\models\JevReportingPeriod;
use app\models\OrsReportingPeriod;
use app\models\RecordAllotmentDisableReportingPeriod;

class LockReportingPeriodController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionInsert()
    {
        if ($_POST) {
            $data = $_POST['data'];
            $reporting_period = $_POST['reporting_period'];
            // $book_id = $_POST['book_id'];
            // $reference = $_POST['reference'];

            foreach ($data as $d) {

                if ($d === 'process_ors') {
                    $ors_rp = new OrsReportingPeriod();
                    $ors_rp->reporting_period = $reporting_period;
                    if ($ors_rp->save(false)) {
                    }
                } else if ($d === 'dv_aucs') {
                    $d = new DvAucsDisableReportingPeriod();
                    $d->reporting_period = $reporting_period;
                    if($d->save(false)){

                    }
                } else if ($d === 'cash_disbursement') {
                    $c  = new CashDisbursementDisableReportingPeriod();
                    $c->reporting_period = $reporting_period;
                    if ($c->save(false)) {
                    }
                } else if ($d === 'cash_reciept') {
                } else if ($d === 'record_allotment') {
                    $r = new RecordAllotmentDisableReportingPeriod();
                    $r->reporting_period = $reporting_period;
                    if ($r->save(false)) {
                    }
                } else if ($d === 'process_burs') {
                } else if ($d === 'jev') {
                    $j = new JevReportingPeriod();
                    $j->reporting_period = $reporting_period;
                    // $j->book_id = $book_id ;
                    // $j->reference = $reference;
                    if ($j->save(false)) {
                    }
                }
            }
            return 'success';
        }
    }
}
