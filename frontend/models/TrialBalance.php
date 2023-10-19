<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "trial_balance".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int $book_id
 * @property string $entry_type
 */
class TrialBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'book_id', 'entry_type'], 'required'],
            [['book_id'], 'integer'],
            [['reporting_period'], 'string', 'max' => 20],
            [['entry_type'], 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reporting_period' => 'Reporting Period',
            'book_id' => 'Book ',
            'entry_type' => ' Type',
        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    private function queryTrialBalance($to_reporting_period, $book_id, $entry_type)
    {
        $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
        $year = $r_period_date->format('Y');
        $from_reporting_period = $year . '-01';

        $sql = '';
        $type = '';
        $params = [];
        if ($entry_type !== 'post-closing') {
            $sql = ' AND ';
            if ($entry_type === 'pre-closing') {
                $type = 'Non-Closing';
            }
            if ($entry_type == 'closing') {
                $type = 'Closing';
            }
            $sql .= Yii::$app->db->getQueryBuilder()->buildCondition('jev_preparation.entry_type = :entry_type', $params);
        }

        $query = Yii::$app->db->createCommand("SELECT 
            chart_of_accounts.uacs as object_code,
            chart_of_accounts.general_ledger as account_title,
            chart_of_accounts.normal_balance,
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) as total_debit_credit,
            begin_balance.total_beginning_balance as begin_balance

            
            FROM (
            
            SELECT
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as obj_code
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
            jev_preparation.book_id = :book_id

            AND jev_preparation.reporting_period <=:to_reporting_period
            GROUP BY obj_code
            UNION
            SELECT 
                SUBSTRING_INDEX(jev_beginning_balance_item.object_code,'_',1) as object_code
                FROM jev_beginning_balance
                LEFT JOIN jev_beginning_balance_item ON jev_beginning_balance.id = jev_beginning_balance_item.jev_beginning_balance_id
            WHERE  jev_beginning_balance.book_id=:book_id
            GROUP BY object_code
            ) as jev_object_codes
            
            LEFT JOIN (
            SELECT
            
            SUM(jev_accounting_entries.debit) as debit,
            SUM(jev_accounting_entries.credit) as credit,
            SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as chart
            FROM jev_accounting_entries 
            LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
            WHERE 
            jev_preparation.book_id = :book_id
            AND jev_preparation.reporting_period >=:from_reporting_period
            AND jev_preparation.reporting_period <=:to_reporting_period
            $sql
            GROUP BY chart)
            as accounting_entries ON jev_object_codes.obj_code = accounting_entries.chart
            LEFT JOIN (SELECT 
                    b_balance.object_code,
                    SUM(b_balance.total_beginning_balance) as total_beginning_balance
                    FROM (
                    SELECT 
                    SUBSTRING_INDEX(accounting_codes.object_code,'_',1) as object_code,
                    (CASE
                    WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(jev_beginning_balance_item.debit,0)  - IFNULL(jev_beginning_balance_item.credit,0)
                    ELSE IFNULL(jev_beginning_balance_item.credit,0) - IFNULL(jev_beginning_balance_item.debit,0)
                    END) as total_beginning_balance
                    FROM jev_beginning_balance_item 
                    LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
                    LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
                    LEFT JOIN books ON jev_beginning_balance.book_id = books.id
                    WHERE 
                    jev_beginning_balance.`year` = :_year
                    AND jev_beginning_balance.book_id = :book_id
                    ) b_balance
                    GROUP BY b_balance.object_code

            
            
            ) as begin_balance  ON jev_object_codes.obj_code = begin_balance.object_code
            LEFT JOIN chart_of_accounts ON jev_object_codes.obj_code = chart_of_accounts.uacs
            
            WHERE 
            (CASE
            WHEN chart_of_accounts.normal_balance = 'Debit' THEN IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
            ELSE IFNULL(begin_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
            END) !=0
            ORDER BY chart_of_accounts.uacs  ASC", $params)
            ->bindValue(':_year', $year)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':entry_type', $type)
            ->queryAll();
        return $query;
    }
    public function getItems()
    {
        return $this->queryTrialBalance($this->reporting_period, $this->book_id, $this->entry_type);
    }
    public static function generateTrialBalance($reporting_period, $book_id, $entry_type)
    {
        return self::queryTrialBalance($reporting_period, $book_id, $entry_type);
    }
}
