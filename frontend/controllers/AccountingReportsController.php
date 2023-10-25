<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\models\VwTransactionstrackingSearch;

class AccountingReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'transactions-tracking',

                ],
                'rules' => [
                    [
                        'actions' => [
                            'transactions-tracking',

                        ],
                        'allow' => true,
                        'roles' => ['view_transactions_tracking']
                    ],

                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetSubsidiaryLedger()
    {

        if ($_POST) {
            $to_reporting_period = $_POST['print_reporting_period'];
            $book_id = $_POST['print_book_id'];
            $uacs  = $_POST['print_uacs'];
            $year  = DateTime::createFromFormat('Y-m', $to_reporting_period)->format('Y');
            $from_reporting_period = $year . '-01';
            $params = [];
            $sql1 = Yii::$app->db->getQueryBuilder()->buildCondition('jev_accounting_entries.object_code LIKE :uacs', $params);
            $sql2 = Yii::$app->db->getQueryBuilder()->buildCondition('jev_beginning_balance_item.object_code LIKE :uacs', $params);
            $query = $this->generateSubLedger($from_reporting_period, $to_reporting_period, $book_id, $year, '', $sql1, $sql2, $uacs);
            $result = ArrayHelper::index($query, 'row_num', 'head');
            $book  = Yii::$app->db->createCommand("SELECT books.name FROM books WHERE id =:id")
                ->bindValue(':id', $book_id)
                ->queryScalar();
            return json_encode(['query' => $query, 'for_print' => $result, 'year' => $year, 'book_name' => $book]);
        }
        return $this->render('subsidiary_ledger_view');
    }
    public function generateSubLedger($from_reporting_period, $to_reporting_period, $book_id, $year, $object_code = '', $sql1 = '', $sql2 = '', $uacs = '')
    {
        $and = !empty($sql1) ? 'AND' : '';

        $query = Yii::$app->db->createCommand("SELECT  * FROM (SELECT
           ROW_NUMBER() OVER (
            PARTITION BY object_code 
            ORDER BY object_code ) row_num,
            accounting_entries.date,
            accounting_entries.particular,
            accounting_entries.jev_number, 
            IFNULL(accounting_entries.debit,0) as debit,
            IFNULL(accounting_entries.credit,0) as credit,
            accounting_entries.object_code,
            accounting_codes.account_title,
            CONCAT(accounting_entries.object_code,'-',
            accounting_codes.account_title) as head,
            accounting_codes.normal_balance
            FROM(
            SELECT  
            books.id as book_id,
            jev_preparation.reporting_period,
            jev_preparation.date,
            jev_preparation.explaination as particular,
            jev_preparation.jev_number,
            jev_accounting_entries.debit,
            jev_accounting_entries.credit,
            jev_accounting_entries.object_code,
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as uacs
            FROM jev_accounting_entries
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            LEFT JOIN books ON jev_preparation.book_id =  books.id
            WHERE jev_preparation.reporting_period <= :to_reporting_period
            AND jev_preparation.reporting_period >=:from_reporting_period
            AND books.id = :book_id
            $and $sql1
            ) as accounting_entries
            INNER JOIN accounting_codes ON accounting_entries.object_code = accounting_codes.object_code
            WHERE accounting_entries.object_code !=accounting_entries.uacs
       
            UNION ALL 
            SELECT
            0 as row_num,
            '' as `date`,
            'beginning_balance' as particular,
            '' as jev_number,
            IFNULL(jev_beginning_balance_item.debit,0) as debit,
            IFNULL(jev_beginning_balance_item.credit,0) as credit,
            jev_beginning_balance_item.object_code,
            accounting_codes.account_title,
            CONCAT(jev_beginning_balance_item.object_code,'-',
            accounting_codes.account_title) as head,
            accounting_codes.normal_balance
            FROM jev_beginning_balance_item 
            LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
            LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
            WHERE jev_beginning_balance.`year` = :_year
            AND jev_beginning_balance.book_id  = :book_id
            AND accounting_codes.object_code !=accounting_codes.coa_object_code
            $and $sql2
            ) as q 
            ORDER BY q.date ASC
            ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':_year', $year)
            ->bindValue(':object_code', $object_code)
            ->bindValue(':uacs', $uacs . '%')
            ->queryAll();

        return $query;
    }
    public function actionTransactionsTracking()
    {
        $searchModel = new VwTransactionstrackingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('transaction_tracking', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
