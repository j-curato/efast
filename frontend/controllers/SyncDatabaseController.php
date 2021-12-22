<?php

namespace frontend\controllers;

use app\models\Advances;
use app\models\AuthorizationCode;
use app\models\Books;
use app\models\CashFlow;
use app\models\Cdr;
use app\models\ChartOfAccounts;
use app\models\CheckRange;
use app\models\Cibr;
use app\models\DocumentRecieve;
use app\models\FinancingSourceCode;
use app\models\FundCategoryAndClassificationCode;
use app\models\FundClusterCode;
use app\models\FundSource;
use app\models\FundSourceType;
use app\models\Fur;
use app\models\Liquidation;
use app\models\LiquidationEntries;
use app\models\MajorAccounts;
use app\models\MrdClassification;
use app\models\NatureOfTransaction;
use app\models\NetAssetEquity;
use app\models\Payee;
use app\models\PoAsignatory;
use app\models\PoTransaction;
use app\models\ResponsibilityCenter;
use app\models\Rod;
use app\models\RodEntries;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use app\models\SubMajorAccounts;
use ErrorException;
use Yii;

class SyncDatabaseController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if ($action->id == 'process-ors') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id == 'dv-accounting-entries') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id == 'dv-aucs-entries') {
            $this->enableCsrfValidation = false;
        }
        if ($action->id == 'tracking-sheet') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    // public function actionRoLanToCloud()
    // {
    //     if ($_POST) {
    //         $db = Yii::$app->ryn_db;
    //         $local_db = Yi            $db = Yii::$app->ryn_db;
    //         $local_db = Yii::$app->db;
    //         // Advances
    //         $advances = $db->createCommand("SELECT * FROM advances")->queryAll();
    //         // advances_entries
    //         $advances_entries = $db->createCommand("SELECT * FROM advances_entries")->queryAll();
    //         // authorization_code
    //         $authorization_code = $db->createCommand("SELECT * FROM authorization_code")->queryAll();
    //         // books
    //         $books = $db->createCommand('SELECT * FROM books')->queryAll();
    //         // cash_adjustment
    //         $cash_adjustment = $db->createCommand('SELECT * FROM cash_adjustment')->queryAll();
    //         // cash_flow
    //         $cash_flow = $db->createCommand('SELECT * FROM cash_flow')->queryAll();
    //         // cash_recieved
    //         $cash_recieved = $db->createCommand('SELECT * FROM cash_recieved')->queryAll();
    //         // chart_of_accounts
    //         $chart_of_accounts = $db->createCommand('SELECT * FROM chart_of_accounts')->queryAll();
    //         // document_recieve
    //         $document_recieve = $db->createCommand('SELECT * FROM document_recieve')->queryAll();
    //         // dv_accounting_entries
    //         $dv_accounting_entries = $db->createCommand('SELECT * FROM dv_accounting_entries')->queryAll();
    //         // dv_aucs
    //         $dv_aucs = $db->createCommand('SELECT * FROM dv_aucs')->queryAll();
    //         // dv_aucs_entries
    //         $dv_aucs_entries = $db->createCommand('SELECT * FROM dv_aucs_entries')->queryAll();
    //         // event
    //         $event = $db->createCommand('SELECT * FROM event')->queryAll();
    //         // financing_source_code
    //         $financing_source_code = $db->createCommand('SELECT * FROM financing_source_code')->queryAll();
    //         // fund_category_and_classification_code
    //         $fund_category_and_classification_code = $db->createCommand('SELECT * FROM fund_category_and_classification_code')->queryAll();
    //         // fund_cluster_code
    //         $fund_cluster_code = $db->createCommand('SELECT * FROM fund_cluster_code')->queryAll();
    //         // fund_source
    //         $fund_source = $db->createCommand('SELECT * FROM fund_source')->queryAll();
    //         // fund_source_type
    //         $fund_source_type = $db->createCommand('SELECT * FROM fund_source_type')->queryAll();
    //         // jev_accounting_entries
    //         $jev_accounting_entries = $db->createCommand('SELECT * FROM jev_accounting_entries')->queryAll();
    //         // jev_preparation
    //         $jev_preparation = $db->createCommand('SELECT * FROM jev_preparation')->queryAll();
    //         // jev_reporting_period
    //         $jev_reporting_period = $db->createCommand('SELECT * FROM jev_reporting_period')->queryAll();
    //         // major_accounts
    //         $major_accounts = $db->createCommand('SELECT * FROM major_accounts')->queryAll();
    //         // mfo_pap_code
    //         $mfo_pap_code = $db->createCommand('SELECT * FROM mfo_pap_code')->queryAll();
    //         // mrd_classification
    //         $mrd_classification = $db->createCommand('SELECT * FROM mrd_classification')->queryAll();
    //         // nature_of_transaction
    //         $nature_of_transaction = $db->createCommand('SELECT * FROM nature_of_transaction')->queryAll();
    //         // net_asset_equity
    //         $net_asset_equity = $db->createCommand('SELECT * FROM net_asset_equity')->queryAll();
    //         // ors_reporting_period
    //         $ors_reporting_period = $db->createCommand('SELECT * FROM ors_reporting_period')->queryAll();
    //         // payee
    //         $payee = $db->createCommand('SELECT * FROM payee')->queryAll();
    //         // process_ors
    //         $process_ors = $db->createCommand('SELECT * FROM process_ors')->queryAll();
    //         // process_ors_entries
    //         $process_ors_entries = $db->createCommand('SELECT * FROM process_ors_entries')->queryAll();
    //         // record_allotment_entries
    //         $record_allotment_entries = $db->createCommand('SELECT * FROM record_allotment_entries')->queryAll();
    //         // record_allotments
    //         $record_allotments = $db->createCommand('SELECT * FROM record_allotments')->queryAll();
    //         // responsibility_center
    //         $responsibility_center = $db->createCommand('SELECT * FROM responsibility_center')->queryAll();
    //         // sub_accounts1
    //         $sub_account1 = $db->createCommand('SELECT * FROM sub_accounts1')->queryAll();
    //         // sub_accounts2
    //         $sub_account2 = $db->createCommand('SELECT * FROM sub_accounts2')->queryAll();
    //         // sub_major_accounts
    //         $sub_major_accounts = $db->createCommand('SELECT * FROM sub_major_accounts')->queryAll();
    //         // sub_major_accounts_2
    //         $sub_major_accounts = $db->createCommand('SELECT * FROM sub_major_accounts')->queryAll();
    //         // tracking_sheet
    //         $tracking_sheet = $db->createCommand('SELECT * FROM tracking_sheet')->queryAll();
    //         // transaction
    //         $transaction = $db->createCommand("SELECT * FROM `transaction`")->queryAll();
    //         // transmittal
    //         $transmittal = $db->createCommand("SELECT * FROM `transmittal`")->queryAll();
    //         // transmittal_entries
    //         $transmittal_entries = $db->createCommand("SELECT * FROM `transmittal_entries`")->queryAll();
    //         //assignatory
    //         $assignatory = $db->createCommand('SELECT * FROM assignatory')->queryAll();
    //         $authorization_code = $db->createCommand('SELECT * FROM authorization_code')->queryAll();

    //         return json_encode(
    //             $process_ors
    //         );
    //         return json_encode([
    //             'payee' => $payee,
    //             'chart_of_accounts' => $chart_of_accounts,
    //             'major_accounts' => $major_accounts,
    //             'sub_major_accounts' => $sub_major_accounts,
    //             'sub_account1' => $sub_account1,
    //             'sub_account2' => $sub_account2,
    //             'books' => $books,
    //             'cash_flow' => $cash_flow,
    //             'nature_of_transaction' => $nature_of_transaction,
    //             'mrd_classification' => $mrd_classification,
    //             'assignatory' => $assignatory,
    //             'ors_reporting_period' => $ors_reporting_period,
    //             'jev_reporting_period' => $jev_reporting_period,
    //             'fund_source_type' => $fund_source_type,
    //             'responsibility_center' => $responsibility_center,
    //             'document_recieve' => $document_recieve,
    //             'fund_cluster_code' => $fund_cluster_code,
    //             'financing_source_code' => $financing_source_code,
    //             'authorization_code' => $authorization_code,
    //             'fund_category_and_classification_code' => $fund_category_and_classification_code,
    //             'mfo_pap_code' => $mfo_pap_code,
    //             'fund_source' => $fund_source,
    //             // 'dv_aucs_entries' => $dv_aucs_entries,
    //             // 'advances' => $advances,
    //             // 'advances_entries' => $advances_entries,
    //             'authorization_code' => $authorization_code,
    //             // 'cash_adjustment' => $cash_adjustment,
    //             // 'cash_recieved' => $cash_recieved,
    //             // 'dv_accounting_entries' => $dv_accounting_entries,
    //             // 'dv_aucs' => $dv_aucs,
    //             // 'event' => $event,
    //             // 'jev_accounting_entries' => $jev_accounting_entries,
    //             // 'jev_preparation' => $jev_preparation,
    //             'net_asset_equity' => $net_asset_equity,
    //             // 'process_ors' => $process_ors,
    //             // 'process_ors_entries' => $process_ors_entries,
    //             // 'record_allotment_entries' => $record_allotment_entries,
    //             // 'record_allotments' => $record_allotments,
    //             // 'tracking_sheet' => $tracking_sheet,
    //             // 'transaction' => $transaction,
    //             // 'transmittal' => $transmittal,
    //             // 'transmittal_entries' => $transmittal_entries,
    //         ]);
    //     }
    // }
    public function actionPayee()
    {

        if ($_POST) {
            $db = Yii::$app->ryn_db;

            $source_payee = $db->createCommand("SELECT * FROM `payee`")->queryAll();
            $target_payee =  Yii::$app->cloud_db->createCommand("SELECT * FROM `payee`")->queryAll();
            $source_payee_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_payee), array_map('serialize', $target_payee))

            );
            return json_encode($source_payee_difference);
        }
    }
    public function actionTransaction()
    {
        if ($_POST) {

            $db = Yii::$app->ryn_db;
            $source_transaction = $db->createCommand("SELECT * FROM `transaction`")->queryAll();
            $target_transaction =  Yii::$app->cloud_db->createCommand("SELECT * FROM `transaction`")->queryAll();
            $source_transaction_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_transaction), array_map('serialize', $target_transaction))

            );
            return json_encode($source_transaction_difference);
        }
    }

    public function actionTrackingSheet()
    {
        if ($_POST) {

            $db = Yii::$app->ryn_db;
            $source_tracking_sheet = $db->createCommand("SELECT * FROM `tracking_sheet`")->queryAll();
            $target_tracking_sheet =  Yii::$app->cloud_db->createCommand("SELECT * FROM `tracking_sheet`")->queryAll();
            $source_tracking_sheet_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_tracking_sheet), array_map('serialize', $target_tracking_sheet))

            );
            return json_encode($source_tracking_sheet_difference);
        }
    }
    public function actionRecordAllotment()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            // RECORD ALLOTMENTS
            $source_record_allotments = $db->createCommand('SELECT * FROM record_allotments')->queryAll();
            $target_record_allotments = Yii::$app->cloud_db->createCommand('SELECT * FROM record_allotments')->queryAll();

            $source_record_allotments_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_record_allotments), array_map('serialize', $target_record_allotments))

            );
            // RECORD ALLOTMENT ENTRIES
            $source_record_allotment_entries = $db->createCommand('SELECT * FROM record_allotment_entries')->queryAll();
            $target_record_allotment_entries = Yii::$app->cloud_db->createCommand('SELECT * FROM record_allotment_entries')->queryAll();
            $source_record_allotment_entries_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_record_allotment_entries), array_map('serialize', $target_record_allotment_entries))

            );

            return json_encode(
                [
                    'record_allotments' => $source_record_allotments_difference,
                    'record_allotment_entries' => $source_record_allotment_entries_difference,
                ]
            );
        }
    }
    public function actionProcessOrs()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            // PROCESS ORS
            $source_process_ors = $db->createCommand('SELECT * FROM process_ors')->queryAll();
            $target_process_ors = Yii::$app->cloud_db->createCommand('SELECT * FROM process_ors')->queryAll();
            $source_process_ors_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_process_ors), array_map('serialize', $target_process_ors))

            );
            // PROCESS ORS ENTRIES
            $source_process_ors_entries = $db->createCommand('SELECT * FROM process_ors_entries')->queryAll();
            $target_process_ors_entries = Yii::$app->cloud_db->createCommand('SELECT * FROM process_ors_entries')->queryAll();
            $source_process_ors_entries_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_process_ors_entries), array_map('serialize', $target_process_ors_entries))

            );
            return json_encode(
                [
                    'process_ors' => $source_process_ors_difference,
                    'process_ors_entries' => $source_process_ors_entries_difference,
                    // '_csrf' => Yii::$app->request->getCsrfToken()
                ]
            );
        }
    }
    public function actionDvAucs()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            $source_dv_aucs = $db->createCommand('SELECT * FROM dv_aucs')->queryAll();
            $target_dv_aucs = Yii::$app->cloud_db->createCommand('SELECT * FROM dv_aucs')->queryAll();
            $source_dv_aucs_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_dv_aucs), array_map('serialize', $target_dv_aucs))

            );
            return json_encode($source_dv_aucs_difference);
        }
    }
    public function actionDvAucsEntries()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            $source_dv_aucs_entries = $db->createCommand("SELECT * FROM `dv_aucs_entries`")->queryAll();
            $target_dv_aucs_entries =  Yii::$app->cloud_db->createCommand("SELECT * FROM `dv_aucs_entries`")->queryAll();
            $source_dv_aucs_entries_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_dv_aucs_entries), array_map('serialize', $target_dv_aucs_entries))

            );
            $to_delete = array_map(
                'unserialize',
                array_diff(array_map('serialize', $target_dv_aucs_entries), array_map('serialize', $source_dv_aucs_entries))

            );
            // return json_encode(array_column($to_delete,'id'));
            return json_encode([
                'new_dv_aucs_entries' => $source_dv_aucs_entries_difference,
                'to_delete' => array_column($to_delete, 'id')
            ]);
        }
    }
    public function actionDvAccountingEntries()
    {
        if ($_POST) {

            $db = Yii::$app->ryn_db;
            $source_dv_accounting_entries = $db->createCommand("SELECT * FROM `dv_accounting_entries`")->queryAll();
            $target_dv_accounting_entries =  Yii::$app->cloud_db->createCommand("SELECT * FROM `dv_accounting_entries`")->queryAll();
            $source_dv_accounting_entries_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_dv_accounting_entries), array_map('serialize', $target_dv_accounting_entries))

            );
            $to_delete = array_map(
                'unserialize',
                array_diff(array_map('serialize', $target_dv_accounting_entries), array_map('serialize', $source_dv_accounting_entries))

            );
            // return json_encode(array_column($to_delete,'id'));
            return json_encode([
                'new_dv_accounting_entries' => $source_dv_accounting_entries_difference,
                'to_delete' => array_column($to_delete, 'id')
            ]);
        }
    }
    public function actionAdvances()
    {
        if ($_POST) {

            $db = Yii::$app->ryn_db;
            $source_advances = $db->createCommand("SELECT * FROM `advances`")->queryAll();
            $target_advances =  Yii::$app->cloud_db->createCommand("SELECT * FROM `advances`")->queryAll();
            $source_advances_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_advances), array_map('serialize', $target_advances))

            );
            // return json_encode(array_column($to_delete,'id'));
            return json_encode($source_advances_difference);
        }
    }
    public function actionAdvancesEntries()
    {
        if ($_POST) {

            $db = Yii::$app->ryn_db;
            $source_advances_entries = $db->createCommand("SELECT * FROM `advances_entries`")->queryAll();
            $target_advances_entries =  Yii::$app->cloud_db->createCommand("SELECT * FROM `advances_entries`")->queryAll();
            $source_advances_entries_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_advances_entries), array_map('serialize', $target_advances_entries))

            );
            // return json_encode(array_column($to_delete,'id'));

            return json_encode($source_advances_entries_difference);
        }
    }
    public function actionCashDisbursement()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            // $cash_disbursement = $db->createCommand('SELECT * FROM cash_disbursement')->queryAll();



            $source_cash_disbursement = $db->createCommand("SELECT * FROM `cash_disbursement`")->queryAll();
            $target_cash_disbursement =  Yii::$app->cloud_db->createCommand("SELECT * FROM `cash_disbursement`")->queryAll();
            $source_cash_disbursement_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_cash_disbursement), array_map('serialize', $target_cash_disbursement))

            );
            return json_encode($source_cash_disbursement_difference);
        }
    }
    public function actionChartOfAccount()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            $source_chart_of_account = $db->createCommand('SELECT * FROM chart_of_accounts')->queryAll();
            $target_chart_of_account =  Yii::$app->cloud_db->createCommand("SELECT * FROM `chart_of_account`")->queryAll();
            $source_chart_of_account_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_chart_of_account), array_map('serialize', $target_chart_of_account))

            );
            return json_encode($source_chart_of_account_difference);
        }
    }
    public function actionSubAccount1()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            $source_sub_account1 = $db->createCommand('SELECT * FROM sub_accounts1')->queryAll();
            $target_sub_accounts1 =  Yii::$app->cloud_db->createCommand("SELECT * FROM `sub_accounts1`")->queryAll();
            $source_sub_accounts1_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_sub_account1), array_map('serialize', $target_sub_accounts1))

            );
            return json_encode($source_sub_accounts1_difference);
        }
    }
    public function actionSubAccount2()
    {
        if ($_POST) {
            $db = Yii::$app->ryn_db;
            $source_sub_account2 = $db->createCommand('SELECT * FROM sub_accounts2')->queryAll();
            $target_sub_accounts2 =  Yii::$app->cloud_db->createCommand("SELECT * FROM `sub_accounts2`")->queryAll();
            $source_sub_accounts2_difference = array_map(
                'unserialize',
                array_diff(array_map('serialize', $source_sub_account2), array_map('serialize', $target_sub_accounts2))

            );
            return json_encode($source_sub_accounts2_difference);
        }
    }



    // public function actionUpdateDatabase()
    // {
    //     if ($_POST) {
    //         $db = Yii::$app->ryn_db;
    //       $source_payee_difference = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_payee), array_map('serialize', $target_payee))

    //         );

    //         if (!empty($source_payee_difference)) {
    //             try {
    //                 foreach ($source_payee_difference as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM payee WHERE payee.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_payee = Payee::findOne($val['id']);
    //                         $update_payee->id = $val['id'];
    //                         $update_payee->account_name = $val['account_name'];
    //                         $update_payee->registered_name = $val['registered_name'];
    //                         $update_payee->contact_person = $val['contact_person'];
    //                         $update_payee->registered_address = $val['registered_address'];
    //                         $update_payee->contact = $val['contact'];
    //                         $update_payee->remark = $val['remark'];
    //                         $update_payee->tin_number = $val['tin_number'];
    //                         $update_payee->isEnable = $val['isEnable'];
    //                         if ($update_payee->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save');
    //                         }
    //                     } else {
    //                         $db->createCommand("INSERT INTO payee (id,account_name,)")->query();
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }

    //         // books
    //         $source_books = $data['books'];
    //         $target_books = $db->createCommand('SELECT * FROM books')->queryAll();
    //         $books_difference = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_books), array_map('serialize', $target_books))
    //         );
    //         if (!empty($books_difference)) {
    //             try {
    //                 foreach ($books_difference as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM books WHERE books.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_book = Books::findOne($val['id']);
    //                         $update_book->id = $val['id'];
    //                         $update_book->name = $val['name'];
    //                         $update_book->account_number = $val['account_number'];
    //                         if ($update_book->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa books update');
    //                         }
    //                     } else {
    //                         $new_book = new Books();

    //                         $new_book->id = $val['id'];
    //                         $new_book->name = $val['name'];
    //                         $new_book->account_number = $val['account_number'];

    //                         if ($new_book->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Books';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }

    //         // major_accounts
    //         $source_major_accounts = $data['major_accounts'];
    //         $target_major_accounts = $db->createCommand('SELECT * FROM major_accounts')->queryAll();
    //         $source_major_accounts_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_major_accounts), array_map('serialize', $target_major_accounts))
    //         );
    //         if (!empty($source_major_accounts_diff)) {
    //             try {
    //                 foreach ($source_major_accounts_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM major_accounts WHERE major_accounts.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_major_accounts = MajorAccounts::findOne($val['id']);
    //                         $update_major_accounts->name = $val['name'];
    //                         $update_major_accounts->object_code = $val['object_code'];


    //                         if ($update_major_accounts->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Major Accounts update');
    //                         }
    //                     } else {
    //                         $new_major_accounts = new MajorAccounts();

    //                         $new_major_accounts->id = $val['id'];
    //                         $new_major_accounts->name = $val['name'];
    //                         $new_major_accounts->object_code = $val['object_code'];

    //                         if ($new_major_accounts->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Major Accounts';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // sub_major_accounts
    //         $source_sub_major_accounts = $data['sub_major_accounts'];
    //         $target_sub_major_accounts = $db->createCommand('SELECT * FROM sub_major_accounts')->queryAll();
    //         $source_sub_major_accounts_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_sub_major_accounts), array_map('serialize', $target_sub_major_accounts))
    //         );
    //         if (!empty($source_sub_major_accounts_diff)) {
    //             try {
    //                 foreach ($source_sub_major_accounts_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM sub_major_accounts WHERE sub_major_accounts.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_sub_major_accounts = SubMajorAccounts::findOne($val['id']);
    //                         $update_sub_major_accounts->name = $val['name'];
    //                         $update_sub_major_accounts->object_code = $val['object_code'];


    //                         if ($update_sub_major_accounts->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Sub Major Accounts update');
    //                         }
    //                     } else {
    //                         $new_sub_major_accounts = new SubMajorAccounts();
    //                         $new_sub_major_accounts->id = $val['id'];
    //                         $new_sub_major_accounts->name = $val['name'];
    //                         $new_sub_major_accounts->object_code = $val['object_code'];
    //                         if ($new_sub_major_accounts->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Sub Major Accounts';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }

    //         // nature_of_transaction
    //         $source_nature_of_transaction = $data['nature_of_transaction'];
    //         $target_nature_of_transaction = $db->createCommand('SELECT * FROM nature_of_transaction')->queryAll();
    //         $source_nature_of_transaction_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_nature_of_transaction), array_map('serialize', $target_nature_of_transaction))
    //         );
    //         if (!empty($source_nature_of_transaction_diff)) {
    //             try {
    //                 foreach ($source_nature_of_transaction_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM nature_of_transaction WHERE nature_of_transaction.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_nature_of_transaction = NatureOfTransaction::findOne($val['id']);
    //                         $update_nature_of_transaction->name = $val['name'];


    //                         if ($update_nature_of_transaction->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Nature of Transaction update');
    //                         }
    //                     } else {
    //                         $new_nature_of_transaction = new NatureOfTransaction();
    //                         $new_nature_of_transaction->id = $val['id'];
    //                         $new_nature_of_transaction->name = $val['name'];
    //                         if ($new_nature_of_transaction->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Nature of Transaction';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // cash_flow
    //         $source_cash_flow = $data['cash_flow'];
    //         $target_cash_flow = $db->createCommand('SELECT * FROM cash_flow')->queryAll();
    //         $source_cash_flow_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_cash_flow), array_map('serialize', $target_cash_flow))
    //         );
    //         if (!empty($source_cash_flow_diff)) {
    //             try {
    //                 foreach ($source_cash_flow_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM cash_flow WHERE cash_flow.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_cash_flow = CashFlow::findOne($val['id']);
    //                         $update_cash_flow->name = $val['name'];


    //                         if ($update_cash_flow->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa CashFlow update');
    //                         }
    //                     } else {
    //                         $new_cash_flow = new CashFlow();
    //                         $new_cash_flow->id = $val['id'];
    //                         $new_cash_flow->name = $val['name'];
    //                         if ($new_cash_flow->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa CashFlow';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // mrd_classification
    //         $source_mrd_classification = $data['mrd_classification'];
    //         $target_mrd_classification = $db->createCommand('SELECT * FROM mrd_classification')->queryAll();
    //         $source_mrd_classification_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_mrd_classification), array_map('serialize', $target_mrd_classification))
    //         );
    //         if (!empty($source_mrd_classification_diff)) {
    //             try {
    //                 foreach ($source_mrd_classification_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM mrd_classification WHERE mrd_classification.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_mrd_classification = MrdClassification::findOne($val['id']);
    //                         $update_mrd_classification->name = $val['name'];
    //                         $update_mrd_classification->description = $val['description'];

    //                         if ($update_mrd_classification->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa MRD Classification update');
    //                         }
    //                     } else {
    //                         $new_mrd_classification = new MrdClassification();
    //                         $new_mrd_classification->id = $val['id'];
    //                         $new_mrd_classification->name = $val['name'];
    //                         $new_mrd_classification->description = $val['description'];
    //                         if ($new_mrd_classification->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa MRD Classification';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // // fund_source_type
    //         // $source_fund_source_type = $data['fund_source_type'];
    //         // $target_fund_source_type = $db->createCommand('SELECT * FROM fund_source_type')->queryAll();
    //         // $source_fund_source_type_diff = array_map(
    //         //     'unserialize',
    //         //     array_diff(array_map('serialize', $source_fund_source_type), array_map('serialize', $target_fund_source_type))
    //         // );
    //         // if (!empty($source_fund_source_type_diff)) {
    //         //     try {
    //         //         foreach ($source_fund_source_type_diff as $val) {
    //         //             $query = $db->createCommand("SELECT EXISTS (SELECT * FROM fund_source_type WHERE fund_source_type.id = :id)")
    //         //                 ->bindValue(':id', $val['id'])
    //         //                 ->queryScalar();
    //         //             if (intval($query) === 1) {
    //         //                 $update_fund_source_type = FundSourceType::findOne($val['id']);
    //         //                 $update_fund_source_type->name = $val['name'];
    //         //                 $update_fund_source_type->division = $val['division'];



    //         //                 if ($update_fund_source_type->save(false)) {
    //         //                 } else {
    //         //                     return json_encode('wala na save sa Fund Source Type update');
    //         //                 }
    //         //             } else {
    //         //                 $new_fund_source_type = new FundSourceType();
    //         //                 $new_fund_source_type->id = $val['id'];
    //         //                 $new_fund_source_type->name = $val['name'];
    //         //                 $new_fund_source_type->division = $val['division'];
    //         //                 if ($new_fund_source_type->save(false)) {
    //         //                 } else {
    //         //                     return 'wala na sulod  sa Fund Source Type';
    //         //                 }
    //         //             }
    //         //         }
    //         //     } catch (ErrorException $e) {
    //         //         return json_encode($e->getMessage());
    //         //     }
    //         // }

    //         // fund_source
    //         $source_fund_source = $data['fund_source'];
    //         $target_fund_source = $db->createCommand('SELECT * FROM fund_source')->queryAll();
    //         $source_fund_source_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_fund_source), array_map('serialize', $target_fund_source))
    //         );
    //         if (!empty($source_fund_source_diff)) {
    //             try {
    //                 foreach ($source_fund_source_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM fund_source WHERE fund_source.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_fund_source = FundSource::findOne($val['id']);
    //                         $update_fund_source->name = $val['name'];
    //                         $update_fund_source->description = $val['description'];
    //                         if ($update_fund_source->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Fund Source update');
    //                         }
    //                     } else {
    //                         $new_fund_source = new FundSource();
    //                         $new_fund_source->id = $val['id'];
    //                         $new_fund_source->name = $val['name'];
    //                         $new_fund_source->description = $val['description'];
    //                         if ($new_fund_source->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Fund Source ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // responsibility_center
    //         $source_responsibility_center = $data['responsibility_center'];
    //         $target_responsibility_center = $db->createCommand('SELECT * FROM responsibility_center')->queryAll();
    //         $source_responsibility_center_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_responsibility_center), array_map('serialize', $target_responsibility_center))
    //         );
    //         if (!empty($source_responsibility_center_diff)) {
    //             try {
    //                 foreach ($source_responsibility_center_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM responsibility_center WHERE responsibility_center.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_responsibility_center = ResponsibilityCenter::findOne($val['id']);
    //                         $update_responsibility_center->name = $val['name'];
    //                         $update_responsibility_center->description = $val['description'];
    //                         if ($update_responsibility_center->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Responsibility Center update');
    //                         }
    //                     } else {
    //                         $new_responsibility_center = new ResponsibilityCenter();
    //                         $new_responsibility_center->id = $val['id'];
    //                         $new_responsibility_center->name = $val['name'];
    //                         $new_responsibility_center->description = $val['description'];
    //                         if ($new_responsibility_center->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Responsibility Center ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // document_recieve
    //         $source_document_recieve = $data['document_recieve'];
    //         $target_document_recieve = $db->createCommand('SELECT * FROM document_recieve')->queryAll();
    //         $source_document_recieve_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_document_recieve), array_map('serialize', $target_document_recieve))
    //         );
    //         if (!empty($source_document_recieve_diff)) {
    //             try {
    //                 foreach ($source_document_recieve_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM document_recieve WHERE document_recieve.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_document_recieve = DocumentRecieve::findOne($val['id']);
    //                         $update_document_recieve->name = $val['name'];
    //                         $update_document_recieve->description = $val['description'];
    //                         if ($update_document_recieve->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_document_recieve = new DocumentRecieve();
    //                         $new_document_recieve->id = $val['id'];
    //                         $new_document_recieve->name = $val['name'];
    //                         $new_document_recieve->description = $val['description'];
    //                         if ($new_document_recieve->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // fund_cluster_code
    //         $source_fund_cluster_code = $data['fund_cluster_code'];
    //         $target_fund_cluster_code = $db->createCommand('SELECT * FROM fund_cluster_code')->queryAll();
    //         $source_fund_cluster_code_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_fund_cluster_code), array_map('serialize', $target_fund_cluster_code))
    //         );
    //         if (!empty($source_fund_cluster_code_diff)) {
    //             try {
    //                 foreach ($source_fund_cluster_code_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM fund_cluster_code WHERE fund_cluster_code.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_fund_cluster_code = FundClusterCode::findOne($val['id']);
    //                         $update_fund_cluster_code->name = $val['name'];
    //                         $update_fund_cluster_code->description = $val['description'];
    //                         if ($update_fund_cluster_code->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_fund_cluster_code = new FundClusterCode();
    //                         $new_fund_cluster_code->id = $val['id'];
    //                         $new_fund_cluster_code->name = $val['name'];
    //                         $new_fund_cluster_code->description = $val['description'];
    //                         if ($new_fund_cluster_code->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // financing_source_code
    //         $source_financing_source_code = $data['financing_source_code'];
    //         $target_financing_source_code = $db->createCommand('SELECT * FROM financing_source_code')->queryAll();
    //         $source_financing_source_code_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_financing_source_code), array_map('serialize', $target_financing_source_code))
    //         );
    //         if (!empty($source_financing_source_code_diff)) {
    //             try {
    //                 foreach ($source_financing_source_code_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM financing_source_code WHERE financing_source_code.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_financing_source_code = FinancingSourceCode::findOne($val['id']);
    //                         $update_financing_source_code->name = $val['name'];
    //                         $update_financing_source_code->description = $val['description'];
    //                         if ($update_financing_source_code->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_financing_source_code = new FinancingSourceCode();
    //                         $new_financing_source_code->id = $val['id'];
    //                         $new_financing_source_code->name = $val['name'];
    //                         $new_financing_source_code->description = $val['description'];
    //                         if ($new_financing_source_code->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // authorization_code
    //         $source_authorization_code = $data['authorization_code'];
    //         $target_authorization_code = $db->createCommand('SELECT * FROM authorization_code')->queryAll();
    //         $source_authorization_code_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_authorization_code), array_map('serialize', $target_authorization_code))
    //         );
    //         if (!empty($source_authorization_code_diff)) {
    //             try {
    //                 foreach ($source_authorization_code_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM authorization_code WHERE authorization_code.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_authorization_code = AuthorizationCode::findOne($val['id']);
    //                         $update_authorization_code->name = $val['name'];
    //                         $update_authorization_code->description = $val['description'];
    //                         if ($update_authorization_code->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_authorization_code = new AuthorizationCode();
    //                         $new_authorization_code->id = $val['id'];
    //                         $new_authorization_code->name = $val['name'];
    //                         $new_authorization_code->description = $val['description'];
    //                         if ($new_authorization_code->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }

    //         // fund_category_and_classification_code
    //         $source_fund_category_and_classification_code = $data['fund_category_and_classification_code'];
    //         $target_fund_category_and_classification_code = $db->createCommand('SELECT * FROM fund_category_and_classification_code')->queryAll();
    //         $source_fund_category_and_classification_code_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_fund_category_and_classification_code), array_map('serialize', $target_fund_category_and_classification_code))
    //         );
    //         if (!empty($source_fund_category_and_classification_code_diff)) {
    //             try {
    //                 foreach ($source_fund_category_and_classification_code_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM fund_category_and_classification_code WHERE fund_category_and_classification_code.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_fund_category_and_classification_code = FundCategoryAndClassificationCode::findOne($val['id']);
    //                         $update_fund_category_and_classification_code->name = $val['name'];
    //                         $update_fund_category_and_classification_code->description = $val['description'];
    //                         $update_fund_category_and_classification_code->from = $val['from'];
    //                         $update_fund_category_and_classification_code->to = $val['to'];



    //                         if ($update_fund_category_and_classification_code->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_fund_category_and_classification_code = new FundCategoryAndClassificationCode();
    //                         $new_fund_category_and_classification_code->id = $val['id'];
    //                         $new_fund_category_and_classification_code->name = $val['name'];
    //                         $new_fund_category_and_classification_code->description = $val['description'];
    //                         $new_fund_category_and_classification_code->from = $val['from'];
    //                         $new_fund_category_and_classification_code->to = $val['to'];
    //                         if ($new_fund_category_and_classification_code->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         // net_asset_equity
    //         $source_net_asset_equity = $data['net_asset_equity'];
    //         $target_net_asset_equity = $db->createCommand('SELECT * FROM net_asset_equity')->queryAll();
    //         $source_net_asset_equity_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_net_asset_equity), array_map('serialize', $target_net_asset_equity))
    //         );
    //         if (!empty($source_net_asset_equity_diff)) {
    //             try {
    //                 foreach ($source_net_asset_equity_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM net_asset_equity WHERE net_asset_equity.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_net_asset_equity = NetAssetEquity::findOne($val['id']);
    //                         $update_net_asset_equity->group = $val['group'];
    //                         $update_net_asset_equity->specific_change = $val['specific_change'];



    //                         if ($update_net_asset_equity->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_net_asset_equity = new NetAssetEquity();
    //                         $new_net_asset_equity->id = $val['id'];
    //                         $new_net_asset_equity->group = $val['group'];
    //                         $new_net_asset_equity->specific_change = $val['specific_change'];
    //                         if ($new_net_asset_equity->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }


    //         // CHART OF ACCOUNTS
    //         $source_chart_of_accounts = $data['chart_of_accounts'];
    //         $target_chart_of_accounts = $db->createCommand('SELECT * FROM chart_of_accounts')->queryAll();
    //         $source_chart_of_accounts_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_chart_of_accounts), array_map('serialize', $target_chart_of_accounts))
    //         );
    //         if (!empty($source_chart_of_accounts_diff)) {
    //             try {
    //                 foreach ($source_chart_of_accounts_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM chart_of_accounts WHERE chart_of_accounts.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $p = ChartOfAccounts::findOne($val['id']);
    //                         $p->uacs = $val['uacs'];
    //                         $p->general_ledger = $val['general_ledger'];
    //                         $p->major_account_id = $val['major_account_id'];
    //                         $p->sub_major_account = $val['sub_major_account'];
    //                         $p->sub_major_account_2_id = $val['sub_major_account_2_id'];
    //                         $p->account_group = $val['account_group'];
    //                         $p->current_noncurrent = $val['current_noncurrent'];
    //                         $p->enable_disable = $val['enable_disable'];
    //                         $p->normal_balance = $val['normal_balance'];
    //                         $p->is_active = $val['is_active'];
    //                         if ($p->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save');
    //                         }
    //                     } else {
    //                         $new_chart = new ChartOfAccounts();

    //                         $new_chart->id = $val['id'];
    //                         $new_chart->uacs = $val['uacs'];
    //                         $new_chart->general_ledger = $val['general_ledger'];
    //                         $new_chart->major_account_id = $val['major_account_id'];
    //                         $new_chart->sub_major_account = $val['sub_major_account'];
    //                         $new_chart->sub_major_account_2_id = $val['sub_major_account_2_id'];
    //                         $new_chart->account_group = $val['account_group'];
    //                         $new_chart->current_noncurrent = $val['current_noncurrent'];
    //                         $new_chart->enable_disable = $val['enable_disable'];
    //                         $new_chart->normal_balance = $val['normal_balance'];
    //                         $new_chart->is_active = $val['is_active'];

    //                         if ($new_chart->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa chart of accounts';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }

    //         // sub_account1
    //         $source_sub_account1 = $data['sub_account1'];
    //         $target_sub_account1 = $db->createCommand('SELECT * FROM sub_accounts1')->queryAll();
    //         $source_sub_account1_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_sub_account1), array_map('serialize', $target_sub_account1))
    //         );
    //         if (!empty($source_sub_account1_diff)) {
    //             try {
    //                 foreach ($source_sub_account1_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM sub_accounts1 WHERE sub_accounts1.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_subAccount1 = SubAccounts1::findOne($val['id']);
    //                         $update_subAccount1->chart_of_account_id = $val['chart_of_account_id'];
    //                         $update_subAccount1->object_code = $val['object_code'];
    //                         $update_subAccount1->name = $val['name'];
    //                         $update_subAccount1->is_active = $val['is_active'];

    //                         if ($update_subAccount1->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save');
    //                         }
    //                     } else {
    //                         $new_subAccounts1 = new SubAccounts1();

    //                         $new_subAccounts1->id = $val['id'];
    //                         $new_subAccounts1->chart_of_account_id = $val['chart_of_account_id'];
    //                         $new_subAccounts1->object_code = $val['object_code'];
    //                         $new_subAccounts1->name = $val['name'];
    //                         $new_subAccounts1->is_active = $val['is_active'];
    //                         if ($new_subAccounts1->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa chart of accounts';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }

    //         // sub_account2
    //         $source_sub_account2 = $data['sub_account2'];
    //         $target_sub_account2 = $db->createCommand('SELECT * FROM sub_accounts2')->queryAll();
    //         $source_sub_account2_diff = array_map(
    //             'unserialize',
    //             array_diff(array_map('serialize', $source_sub_account2), array_map('serialize', $target_sub_account2))
    //         );
    //         if (!empty($source_sub_account2_diff)) {
    //             try {
    //                 foreach ($source_sub_account2_diff as $val) {
    //                     $query = $db->createCommand("SELECT EXISTS (SELECT * FROM sub_accounts2 WHERE sub_accounts2.id = :id)")
    //                         ->bindValue(':id', $val['id'])
    //                         ->queryScalar();
    //                     if (intval($query) === 1) {
    //                         $update_sub_account2 = SubAccounts2::findOne($val['id']);
    //                         $update_sub_account2->sub_accounts1_id = $val['sub_accounts1_id'];
    //                         $update_sub_account2->object_code = $val['object_code'];
    //                         $update_sub_account2->name = $val['name'];
    //                         $update_sub_account2->is_active = $val['is_active'];
    //                         if ($update_sub_account2->save(false)) {
    //                         } else {
    //                             return json_encode('wala na save sa Document Recieve update');
    //                         }
    //                     } else {
    //                         $new_sub_account2 = new SubAccounts2();
    //                         $new_sub_account2->id = $val['id'];
    //                         $new_sub_account2->sub_accounts1_id = $val['sub_accounts1_id'];
    //                         $new_sub_account2->object_code = $val['object_code'];
    //                         $new_sub_account2->name = $val['name'];
    //                         $new_sub_account2->is_active = $val['is_active'];
    //                         if ($new_sub_account2->save(false)) {
    //                         } else {
    //                             return 'wala na sulod  sa Document Recieve ';
    //                         }
    //                     }
    //                 }
    //             } catch (ErrorException $e) {
    //                 return json_encode($e->getMessage());
    //             }
    //         }
    //         $transaction->commit();
    //         return 'success';
    //     }
    // }


    public function actionUpdateLan()
    {
        $cloud_liquidation = Yii::$app->cloud_db->createCommand("SELECT * FROM liquidation")->queryAll();
        $cloud_liquidation_entries = Yii::$app->cloud_db->createCommand("SELECT * FROM liquidation_entries")->queryAll();
        $cloud_po_transaction = Yii::$app->cloud_db->createCommand("SELECT * FROM po_transaction")->queryAll();
        $cloud_check_range = Yii::$app->cloud_db->createCommand("SELECT * FROM check_range")->queryAll();
        $cloud_po_asignatory = Yii::$app->cloud_db->createCommand("SELECT * FROM po_asignatory")->queryAll();
        $cloud_po_responsibility_center = Yii::$app->cloud_db->createCommand("SELECT * FROM po_responsibility_center")->queryAll();
        $cloud_cdr = Yii::$app->cloud_db->createCommand("SELECT * FROM cdr")->queryAll();
        $cloud_cibr = Yii::$app->cloud_db->createCommand("SELECT * FROM cibr")->queryAll();
        $cloud_fur = Yii::$app->cloud_db->createCommand("SELECT * FROM fur")->queryAll();
        $cloud_rod = Yii::$app->cloud_db->createCommand("SELECT * FROM rod")->queryAll();
        $cloud_rod_entries = Yii::$app->cloud_db->createCommand("SELECT * FROM rod_entries")->queryAll();


        $lan_liquidation = Yii::$app->db->createCommand("SELECT * FROM liquidation")->queryAll();
        $lan_liquidation_entries = Yii::$app->db->createCommand("SELECT * FROM liquidation_entries")->queryAll();
        $lan_po_transaction = Yii::$app->db->createCommand("SELECT * FROM po_transaction")->queryAll();
        $lan_check_range = Yii::$app->db->createCommand("SELECT * FROM check_range")->queryAll();
        $lan_po_asignatory = Yii::$app->db->createCommand("SELECT * FROM po_asignatory")->queryAll();
        $lan_po_responsibility_center = Yii::$app->db->createCommand("SELECT * FROM po_responsibility_center")->queryAll();
        $lan_cdr = Yii::$app->db->createCommand("SELECT * FROM cdr")->queryAll();
        $lan_cibr = Yii::$app->db->createCommand("SELECT * FROM cibr")->queryAll();
        $lan_fur = Yii::$app->db->createCommand("SELECT * FROM fur")->queryAll();
        $lan_rod = Yii::$app->db->createCommand("SELECT * FROM rod")->queryAll();
        $lan_rod_entries = Yii::$app->db->createCommand("SELECT * FROM rod_entries")->queryAll();


        $liquidation_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_liquidation), array_map('serialize', $lan_liquidation))

        );
        $liquidation_to_delete = array_map(
            'unserialize',
            array_diff(array_map('serialize', $lan_liquidation), array_map('serialize', $cloud_liquidation))

        );

        $liquidation_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_liquidation_entries), array_map('serialize', $lan_liquidation_entries))

        );
        $liquidation_entries_to_delete = array_map(
            'unserialize',
            array_diff(array_map('serialize', $lan_liquidation_entries), array_map('serialize', $cloud_liquidation_entries))

        );
        $po_transaction_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_po_transaction), array_map('serialize', $lan_po_transaction))

        );
        $check_range_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_check_range), array_map('serialize', $lan_check_range))

        );
        $po_asignatory_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_po_asignatory), array_map('serialize', $lan_po_asignatory))

        );
        $po_asignatory_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_po_responsibility_center), array_map('serialize', $lan_po_responsibility_center))

        );
        $cdr_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_cdr), array_map('serialize', $lan_cdr))

        );
        $cibr_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_cibr), array_map('serialize', $lan_cibr))

        );
        $fur_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_fur), array_map('serialize', $lan_fur))

        );
        $rod_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_rod), array_map('serialize', $lan_rod))

        );
        $rod_entries_difference = array_map(
            'unserialize',
            array_diff(array_map('serialize', $cloud_rod_entries), array_map('serialize', $lan_rod_entries))

        );

        $rod_entries_to_delete = array_map(
            'unserialize',
            array_diff(array_map('serialize', $lan_rod_entries), array_map('serialize', $cloud_rod_entries))

        );



        // return json_encode($liquidation_difference);

        if (!empty($liquidation_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($liquidation_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `liquidation` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_liquidation = Liquidation::findOne($val['id']);
                            $update_liquidation->payee_id = $val['payee_id'];
                            $update_liquidation->responsibility_center_id = $val['responsibility_center_id'];
                            $update_liquidation->check_date = $val['check_date'];
                            $update_liquidation->check_number = $val['check_number'];
                            $update_liquidation->dv_number = $val['dv_number'];
                            $update_liquidation->particular = $val['particular'];
                            $update_liquidation->reporting_period = $val['reporting_period'];
                            $update_liquidation->is_cancelled = $val['is_cancelled'];
                            $update_liquidation->created_at = $val['created_at'];
                            $update_liquidation->po_transaction_id = $val['po_transaction_id'];
                            $update_liquidation->check_range_id = $val['check_range_id'];
                            $update_liquidation->is_locked = $val['is_locked'];
                            $update_liquidation->status = $val['status'];
                            $update_liquidation->province = $val['province'];
                            $update_liquidation->payee = $val['payee'];
                            $update_liquidation->is_final = $val['is_final'];
                            $update_liquidation->document_link = $val['document_link'];
                            $update_liquidation->cancel_reporting_period = $val['cancel_reporting_period'];
                            $update_liquidation->exclude_in_raaf = $val['exclude_in_raaf'];



                            if ($update_liquidation->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return false;
                            }
                        } else {
                            $new_liquidation = new Liquidation();
                            $new_liquidation->id = $val['id'];
                            $new_liquidation->payee_id = $val['payee_id'];
                            $new_liquidation->responsibility_center_id = $val['responsibility_center_id'];
                            $new_liquidation->check_date = $val['check_date'];
                            $new_liquidation->check_number = $val['check_number'];
                            $new_liquidation->dv_number = $val['dv_number'];
                            $new_liquidation->particular = $val['particular'];
                            $new_liquidation->reporting_period = $val['reporting_period'];
                            $new_liquidation->is_cancelled = $val['is_cancelled'];
                            $new_liquidation->created_at = $val['created_at'];
                            $new_liquidation->po_transaction_id = $val['po_transaction_id'];
                            $new_liquidation->check_range_id = $val['check_range_id'];
                            $new_liquidation->is_locked = $val['is_locked'];
                            $new_liquidation->status = $val['status'];
                            $new_liquidation->province = $val['province'];
                            $new_liquidation->payee = $val['payee'];
                            $new_liquidation->is_final = $val['is_final'];
                            $new_liquidation->document_link = $val['document_link'];
                            $new_liquidation->cancel_reporting_period = $val['cancel_reporting_period'];
                            $new_liquidation->exclude_in_raaf = $val['exclude_in_raaf'];
                            if ($new_liquidation->save(false)) {
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
        if (!empty($liquidation_to_delete)) {
            foreach ($liquidation_to_delete as $val) {
                $li = Liquidation::findOne($val['id']);
                $li->delete();
            }
        }
        if (!empty($liquidation_entries_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($liquidation_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `liquidation_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_liquidation_entries = LiquidationEntries::findOne($val['id']);
                            $update_liquidation_entries->liquidation_id = $val['liquidation_id'];
                            $update_liquidation_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $update_liquidation_entries->withdrawals = $val['withdrawals'];
                            $update_liquidation_entries->vat_nonvat = $val['vat_nonvat'];
                            $update_liquidation_entries->expanded_tax = $val['expanded_tax'];
                            $update_liquidation_entries->reporting_period = $val['reporting_period'];
                            $update_liquidation_entries->advances_entries_id = $val['advances_entries_id'];
                            $update_liquidation_entries->liquidation_damage = $val['liquidation_damage'];
                            $update_liquidation_entries->is_realign = $val['is_realign'];
                            $update_liquidation_entries->new_chart_of_account_id = $val['new_chart_of_account_id'];

                            if ($update_liquidation_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Liquidation Entries Update';
                            }
                        } else {
                            $new_liquidation_entries = new LiquidationEntries();
                            $new_liquidation_entries->id = $val['id'];
                            $new_liquidation_entries->liquidation_id = $val['liquidation_id'];
                            $new_liquidation_entries->chart_of_account_id = $val['chart_of_account_id'];
                            $new_liquidation_entries->withdrawals = $val['withdrawals'];
                            $new_liquidation_entries->vat_nonvat = $val['vat_nonvat'];
                            $new_liquidation_entries->expanded_tax = $val['expanded_tax'];
                            $new_liquidation_entries->reporting_period = $val['reporting_period'];
                            $new_liquidation_entries->advances_entries_id = $val['advances_entries_id'];
                            $new_liquidation_entries->liquidation_damage = $val['liquidation_damage'];
                            $new_liquidation_entries->is_realign = $val['is_realign'];
                            $new_liquidation_entries->new_chart_of_account_id = $val['new_chart_of_account_id'];

                            if ($new_liquidation_entries->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Liquidation Entries New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($liquidation_entries_to_delete)) {
            foreach ($liquidation_entries_to_delete as $val) {
                $li = LiquidationEntries::findOne($val['id']);
                $li->delete();
            }
        }
        if (!empty($po_transaction_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($po_transaction_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `po_transaction` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_transaction = PoTransaction::findOne($val['id']);
                            $update_po_transaction->payee = $val['payee'];
                            $update_po_transaction->particular = $val['particular'];
                            $update_po_transaction->amount = $val['amount'];
                            $update_po_transaction->payroll_number = $val['payroll_number'];
                            $update_po_transaction->tracking_number = $val['tracking_number'];
                            $update_po_transaction->po_responsibility_center_id = $val['po_responsibility_center_id'];
                            $update_po_transaction->province = $val['province'];



                            if ($update_po_transaction->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in PoTransaction Update';
                            }
                        } else {
                            $new_po_transaction = new PoTransaction();
                            $new_po_transaction->id = $val['id'];
                            $new_po_transaction->payee = $val['payee'];
                            $new_po_transaction->particular = $val['particular'];
                            $new_po_transaction->amount = $val['amount'];
                            $new_po_transaction->payroll_number = $val['payroll_number'];
                            $new_po_transaction->tracking_number = $val['tracking_number'];
                            $new_po_transaction->po_responsibility_center_id = $val['po_responsibility_center_id'];
                            $new_po_transaction->province = $val['province'];

                            if ($new_po_transaction->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in PoTransaction New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($check_range_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($check_range_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `check_range` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_check_range = CheckRange::findOne($val['id']);
                            $update_check_range->from = $val['from'];
                            $update_check_range->to = $val['to'];
                            $update_check_range->province = $val['province'];
                            $update_check_range->reporting_period = $val['reporting_period'];
                            $update_check_range->begin_balance = $val['begin_balance'];

                            if ($update_check_range->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Check Range Update';
                            }
                        } else {
                            $new_check_range = new CheckRange();
                            $new_check_range->id = $val['id'];
                            $new_check_range->from = $val['from'];
                            $new_check_range->to = $val['to'];
                            $new_check_range->province = $val['province'];
                            $new_check_range->reporting_period = $val['reporting_period'];
                            $new_check_range->begin_balance = $val['begin_balance'];

                            if ($new_check_range->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Check Range New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($po_asignatory_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($po_asignatory_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `po_asignatory` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_asignatory = PoAsignatory::findOne($val['id']);
                            $update_po_asignatory->name = $val['name'];
                            $update_po_asignatory->position = $val['position'];
                            $update_po_asignatory->province = $val['province'];



                            if ($update_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in PoAsignatory Update';
                            }
                        } else {
                            $new_po_asignatory = new PoAsignatory();
                            $new_po_asignatory->id = $val['id'];
                            $new_po_asignatory->name = $val['name'];
                            $new_po_asignatory->position = $val['position'];
                            $new_po_asignatory->province = $val['province'];
                            if ($new_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in PoAsignatory New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($cdr_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($cdr_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `cdr` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_asignatory = Cdr::findOne($val['id']);
                            $update_po_asignatory->serial_number = $val['serial_number'];
                            $update_po_asignatory->reporting_period = $val['reporting_period'];
                            $update_po_asignatory->province = $val['province'];
                            $update_po_asignatory->book_name = $val['book_name'];
                            $update_po_asignatory->report_type = $val['report_type'];
                            $update_po_asignatory->is_final = $val['is_final'];
                            $update_po_asignatory->document_link = $val['document_link'];

                            if ($update_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Cdr Update';
                            }
                        } else {
                            $new_po_asignatory = new Cdr();
                            $new_po_asignatory->id = $val['id'];
                            $new_po_asignatory->serial_number = $val['serial_number'];
                            $new_po_asignatory->reporting_period = $val['reporting_period'];
                            $new_po_asignatory->province = $val['province'];
                            $new_po_asignatory->book_name = $val['book_name'];
                            $new_po_asignatory->report_type = $val['report_type'];
                            $new_po_asignatory->is_final = $val['is_final'];
                            $new_po_asignatory->document_link = $val['document_link'];
                            if ($new_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Cdr New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($cibr_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($cibr_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `cibr` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_asignatory = Cibr::findOne($val['id']);
                            $update_po_asignatory->serial_number = $val['serial_number'];
                            $update_po_asignatory->reporting_period = $val['reporting_period'];
                            $update_po_asignatory->province = $val['province'];
                            $update_po_asignatory->book_name = $val['book_name'];
                            $update_po_asignatory->is_final = $val['is_final'];
                            $update_po_asignatory->document_link = $val['document_link'];


                            if ($update_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Cibr Update';
                            }
                        } else {
                            $new_po_asignatory = new Cibr();
                            $new_po_asignatory->id = $val['id'];
                            $new_po_asignatory->serial_number = $val['serial_number'];
                            $new_po_asignatory->reporting_period = $val['reporting_period'];
                            $new_po_asignatory->province = $val['province'];
                            $new_po_asignatory->book_name = $val['book_name'];
                            $new_po_asignatory->is_final = $val['is_final'];
                            $new_po_asignatory->document_link = $val['document_link'];
                            if ($new_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Cibr New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($fur_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($fur_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `fur` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_asignatory = Fur::findOne($val['id']);
                            $update_po_asignatory->reporting_period = $val['reporting_period'];
                            $update_po_asignatory->province = $val['province'];
                            $update_po_asignatory->created_at = $val['created_at'];
                            $update_po_asignatory->document_link = $val['document_link'];



                            if ($update_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Fur Update';
                            }
                        } else {
                            $new_po_asignatory = new Fur();
                            $new_po_asignatory->id = $val['id'];
                            $new_po_asignatory->reporting_period = $val['reporting_period'];
                            $new_po_asignatory->province = $val['province'];
                            $new_po_asignatory->created_at = $val['created_at'];
                            $new_po_asignatory->document_link = $val['document_link'];

                            if ($new_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Fur New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($rod_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($rod_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `rod` WHERE rod_number = :id)")
                            ->bindValue(':id', $val['rod_number'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_asignatory = Rod::findOne($val['rod_number']);
                            $update_po_asignatory->rod_number = $val['rod_number'];
                            $update_po_asignatory->province = $val['province'];

                            if ($update_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Rod Update';
                            }
                        } else {
                            $new_po_asignatory = new Rod();
                            $new_po_asignatory->rod_number = $val['rod_number'];
                            $new_po_asignatory->province = $val['province'];


                            if ($new_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in Rod New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($rod_entries_difference)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($flag = true) {
                    foreach ($rod_entries_difference as $val) {
                        $query = Yii::$app->db->createCommand("SELECT EXISTS (SELECT * FROM `rod_entries` WHERE id = :id)")
                            ->bindValue(':id', $val['id'])
                            ->queryScalar();
                        if (intval($query) === 1) {
                            $update_po_asignatory = RodEntries::findOne($val['id']);
                            $update_po_asignatory->rod_number = $val['rod_number'];
                            $update_po_asignatory->advances_entries_id = $val['advances_entries_id'];

                            if ($update_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in RodEntries Update';
                            }
                        } else {
                            $new_po_asignatory = new RodEntries();
                            $new_po_asignatory->id = $val['id'];
                            $new_po_asignatory->rod_number = $val['rod_number'];
                            $new_po_asignatory->advances_entries_id = $val['advances_entries_id'];


                            if ($new_po_asignatory->save(false)) {
                            } else {
                                $transaction->rollBack();
                                return 'Save Fail in RodEntries New';
                            }
                        }
                    }
                }

                $transaction->commit();
            } catch (ErrorException $e) {
                return json_encode($e->getMessage());
            }
        }
        if (!empty($rod_entries_to_delete)) {
            foreach ($rod_entries_to_delete as $val) {
                $q  = RodEntries::findOne($val['id']);
                $q->delete();
            }
        }
    }
}
