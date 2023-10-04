<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use app\models\Books;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class CashReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'cadadr',
                    'dv-cadadr',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'cadadr',
                        ],
                        'allow' => true,
                        'roles' => ['cadadr']
                    ],
                    [
                        'actions' => [
                            'dv-cadadr',
                        ],
                        'allow' => true,
                        'roles' => ['cadadr_per_dv']
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
    public function actionCadadr()
    {
        if (Yii::$app->request->post()) {
            $from_reporting_period = Yii::$app->request->post('from_reporting_period');
            $to_reporting_period = Yii::$app->request->post('to_reporting_period');
            $book_id = Yii::$app->request->post('book_id');
            // $from_reporting_period = $_POST['from_reporting_period'];
            // $to_reporting_period = $_POST['to_reporting_period'];
            // $book = $_POST['book'];
            $qry = Yii::$app->db->createCommand("CALL prc_Cadadr(:from_period,:to_period,:book_id)")
                ->bindValue(':from_period', $from_reporting_period)
                ->bindValue(':to_period', $to_reporting_period)
                ->bindValue(':book_id', $book_id)
                ->queryAll();
            $book_name = Books::findOne($book_id)->name;
            $begin_balance = strtolower($book_name) === 'rapid lp' ? 667232.17 : 0;
            if (strtotime($from_reporting_period) > strtotime('2023-01')) {
                $check_bgn_bal = Yii::$app->db->createCommand("CALL prc_CadadrBgnBal(:reporting_period,:book_id)")
                    ->bindValue(':book_id', $book_id)
                    ->bindValue(':reporting_period', $from_reporting_period)
                    ->queryScalar();
                $laps_amt = Yii::$app->db->createCommand("SELECT
                     SUM(cash_adjustment.amount) as ttl
                    FROM 
                    cash_adjustment
                    WHERE 
                    cash_adjustment.book_id = :book_id
                    AND cash_adjustment.reporting_period >= '2023-01'
                    AND cash_adjustment.reporting_period < :from_period")
                    ->bindValue(':from_period', $from_reporting_period)
                    ->bindValue(':book_id', $book_id)
                    ->queryScalar();;
                $begin_balance = floatval($begin_balance) + floatval($check_bgn_bal) - floatval($laps_amt);
            }

            $adjustments = Yii::$app->db->createCommand("SELECT * 
               FROM cash_adjustment
               LEFT JOIN books ON cash_adjustment.book_id = books.id
               WHERE cash_adjustment.reporting_period <= :to_reporting_period
               AND cash_adjustment.reporting_period >= :from_reporting_period
               AND books.id = :book")

                ->bindValue(':book', $book_id)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->queryAll();
            $cancelled_checks = ArrayHelper::index($qry, null, ['is_cancelled']);
            $per_mode_of_payment = ArrayHelper::index($cancelled_checks[0] ?? [], null, ['mode_of_payment_name']);
            return json_encode([
                'results' => $qry,
                'adjustments' => $adjustments,
                'cancelled_checks' => $cancelled_checks[1] ?? [],
                'begin_balance' => $begin_balance,
                'per_mode_of_payment' => $per_mode_of_payment,
            ]);
        }
        return $this->render('cadadr');
    }
    public function actionDvCadadr()
    {
        if ($_POST) {
            $from_reporting_period = $_POST['from_reporting_period'];
            $to_reporting_period = $_POST['to_reporting_period'];
            $book = $_POST['book'];


            $query = Yii::$app->db->createCommand("SELECT * FROM dv_cadadr
            WHERE 
            reporting_period >= :from_reporting_period
            AND reporting_period <= :to_reporting_period
            AND book_name = :book
            ORDER BY issuance_date
            ")
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':book', $book)
                ->queryAll();
            $d = new DateTime($from_reporting_period);
            $w = new DateTime('2021-10');
            $begin_balance = 0;
            if ($d->format('Y-m') >= $w->format('Y-m')) {
                $begin_balance = Yii::$app->db->createCommand("SELECT 
                    IFNULL(SUM(total_nca_recieve) - (SUM(total_check_issued)+SUM(total_ada_issued)),0) as begin_balance
                   FROM cadadr_balances
                   WHERE 
                   cadadr_balances.reporting_period < :from_reporting_period 
                   and
                   cadadr_balances.reporting_period >= '2021-10' 
                   AND cadadr_balances.book_name = :book
                   ")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
                $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
                    SUM(cash_adjustment.amount) as total_amount
                    FROM  cash_adjustment
                    LEFT JOIN books ON cash_adjustment.book_id = books.id
                    WHERE 
                    cash_adjustment.reporting_period < :from_reporting_period 
                    AND
                    cash_adjustment.reporting_period >= '2021-10' 
                    AND books.name = :book")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
            } else {
                $begin_balance = Yii::$app->db->createCommand("SELECT 
                    IFNULL(SUM(total_nca_recieve) - (SUM(total_check_issued)+SUM(total_ada_issued)),0) as begin_balance
                   FROM cadadr_balances
                   WHERE 
                   cadadr_balances.reporting_period < :from_reporting_period 
                   AND cadadr_balances.book_name = :book
                   ")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
                $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
                    SUM(cash_adjustment.amount) as total_amount
                    FROM  cash_adjustment
                    LEFT JOIN books ON cash_adjustment.book_id = books.id
                    WHERE 
                    cash_adjustment.reporting_period < :from_reporting_period 
                    AND books.name = :book")
                    ->bindValue(':from_reporting_period', $from_reporting_period)
                    ->bindValue(':book', $book)
                    ->queryScalar();
            }



            // $adjustment_begin_balance = Yii::$app->db->createCommand("SELECT
            // SUM(cash_adjustment.amount) as total_amount
            // FROM  cash_adjustment
            // LEFT JOIN books ON cash_adjustment.book_id = books.id
            // WHERE 
            // cash_adjustment.reporting_period < :from_reporting_period 
            // AND books.name = :book")
            //     ->bindValue(':from_reporting_period', $from_reporting_period)
            //     ->bindValue(':book', $book)
            //     ->queryScalar();
            $begin_balance  += $adjustment_begin_balance;
            $adjustment = Yii::$app->db->createCommand("SELECT * 
           FROM cash_adjustment
           WHERE reporting_period <= :to_reporting_period
           AND reporting_period >= :from_reporting_period
           ")
                ->bindValue(':to_reporting_period', $to_reporting_period)
                ->bindValue(':from_reporting_period', $from_reporting_period)
                ->queryAll();
            // $result2 = ArrayHelper::index($query, null, [function ($element) {
            //     return $element['division'];
            // }, 'mfo_name', 'major_name', 'sub_major_name',]);
            // echo "<pre>";
            // var_dump($query);
            // echo "</pre>";
            // die();

            return json_encode(['results' => $query, 'begin_balance' => $begin_balance, 'adjustment' => $adjustment]);
        }
        return $this->render('dv_cadadr');
    }
}
