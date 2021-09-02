<?php

namespace frontend\controllers;

use Yii;

class SyncDatabaseController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionRoLanToCloud()
    {
        if ($_POST) {

            $db = Yii::$app->db;
            $payee = $db->createCommand('SELECT * FROM payee')->queryAll();
            $chart_of_accounts = $db->createCommand('SELECT * FROM chart_of_accounts')->queryAll();
            $major_accounts = $db->createCommand('SELECT * FROM major_accounts')->queryAll();
            $sub_major_accounts = $db->createCommand('SELECT * FROM sub_major_accounts')->queryAll();
            $sub_account1 = $db->createCommand('SELECT * FROM sub_accounts1')->queryAll();
            $sub_account2 = $db->createCommand('SELECT * FROM sub_accounts2')->queryAll();
            $books = $db->createCommand('SELECT * FROM books')->queryAll();
            $cash_flow = $db->createCommand('SELECT * FROM cash_flow')->queryAll();
            $nature_of_transaction = $db->createCommand('SELECT * FROM nature_of_transaction')->queryAll();
            $mrd_classification = $db->createCommand('SELECT * FROM mrd_classification')->queryAll();
            $assignatory = $db->createCommand('SELECT * FROM assignatory')->queryAll();
            $ors_reporting_period = $db->createCommand('SELECT * FROM ors_reporting_period')->queryAll();
            $jev_reporting_period = $db->createCommand('SELECT * FROM jev_reporting_period')->queryAll();
            $fund_source_type = $db->createCommand('SELECT * FROM fund_source_type')->queryAll();
            $responsibility_center = $db->createCommand('SELECT * FROM responsibility_center')->queryAll();
            $document_recieve = $db->createCommand('SELECT * FROM document_recieve')->queryAll();
            $fund_cluster_code = $db->createCommand('SELECT * FROM fund_cluster_code')->queryAll();
            $financing_source_code = $db->createCommand('SELECT * FROM financing_source_code')->queryAll();

            return json_encode([
                'payee' => $payee,
                'chart_of_accounts' => $chart_of_accounts,
                'major_accounts' => $major_accounts,
                'sub_major_accounts' => $sub_major_accounts,
                'sub_account1' => $sub_account1,
                'sub_account2' => $sub_account2,
                'books' => $books,
                'cash_flow' => $cash_flow,
                'nature_of_transaction' => $nature_of_transaction,
                'mrd_classification' => $mrd_classification,
                'assignatory' => $assignatory,
                'ors_reporting_period' => $ors_reporting_period,
                'jev_reporting_period' => $jev_reporting_period,
                'fund_source_type' => $fund_source_type,
            ]);
        }
    }

    public function actionUpdateDatabase()
    {
        if ($_POST) {
            $db = Yii::$app->cloud_db;
            $data = json_decode(json_decode($_POST['json']), true);
            $source_payee = $data['payee'];
            $source_chart_of_accounts = $data['chart_of_accounts'];
            $source_major_accounts = $data['major_accounts'];
            $source_sub_major_accounts = $data['sub_major_accounts'];
            $source_sub_account1 = $data['sub_account1'];
            $source_sub_account2 = $data['sub_account2'];
            $source_books = $data['books'];
            $source_cash_flow = $data['cash_flow'];
            $source_nature_of_transaction = $data['nature_of_transaction'];
            $source_mrd_classification = $data['mrd_classification'];
            $source_assignatory = $data['assignatory'];
            $source_ors_reporting_period = $data['ors_reporting_period'];
            $source_jev_reporting_period = $data['jev_reporting_period'];
            $source_fund_source_type = $data['fund_source_type'];

            $target_payee = $db->createCommand('SELECT * FROM payee')->queryAll();
            $target_chart_of_accounts = $db->createCommand('SELECT * FROM chart_of_accounts')->queryAll();
            $target_major_accounts = $db->createCommand('SELECT * FROM major_accounts')->queryAll();
            $target_sub_major_accounts = $db->createCommand('SELECT * FROM sub_major_accounts')->queryAll();
            $target_sub_account1 = $db->createCommand('SELECT * FROM sub_accounts1')->queryAll();
            $target_sub_account2 = $db->createCommand('SELECT * FROM sub_accounts2')->queryAll();
            $target_books = $db->createCommand('SELECT * FROM books')->queryAll();
            $target_cash_flow = $db->createCommand('SELECT * FROM cash_flow')->queryAll();
            $target_nature_of_transaction = $db->createCommand('SELECT * FROM nature_of_transaction')->queryAll();
            $target_mrd_classification = $db->createCommand('SELECT * FROM mrd_classification')->queryAll();
            $target_assignatory = $db->createCommand('SELECT * FROM assignatory')->queryAll();
            $target_ors_reporting_period = $db->createCommand('SELECT * FROM ors_reporting_period')->queryAll();
            $target_jev_reporting_period = $db->createCommand('SELECT * FROM jev_reporting_period')->queryAll();
            $target_fund_source_type = $db->createCommand('SELECT * FROM fund_source_type')->queryAll();

            $payee_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_payee), array_map('serialize', $target_payee))
            );
            $chart_of_accounts_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_chart_of_accounts), array_map('serialize', $target_chart_of_accounts))
            );
            $major_accounts_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_major_accounts), array_map('serialize', $target_major_accounts))
            );
            $sub_major_accounts_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_sub_major_accounts), array_map('serialize', $target_sub_major_accounts))
            );
            $sub_account1_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_sub_account1), array_map('serialize', $target_sub_account1))
            );
            $sub_account2_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_sub_account2), array_map('serialize', $target_sub_account2))
            );
            $books_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_books), array_map('serialize', $target_books))
            );
            $cash_flow_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_cash_flow), array_map('serialize', $target_cash_flow))
            );
            $nature_of_transaction_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_nature_of_transaction), array_map('serialize', $target_nature_of_transaction))
            );
            $mrd_classification_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_mrd_classification), array_map('serialize', $target_mrd_classification))
            );
            $assignatory_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_assignatory), array_map('serialize', $target_assignatory))
            );
            $ors_reporting_period_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_ors_reporting_period), array_map('serialize', $target_ors_reporting_period))
            );
            $jev_reporting_period_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_jev_reporting_period), array_map('serialize', $target_jev_reporting_period))
            );
            $fund_source_type_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_fund_source_type), array_map('serialize', $target_fund_source_type))
            );


            ob_clean();
            echo "<pre>";
            var_dump($fund_source_type_difference);
            echo "</pre>";
            return ob_get_clean();
        }
    }
}
