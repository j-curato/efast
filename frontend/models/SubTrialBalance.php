<?php

namespace app\models;

use Yii;
use DateTime;

/**
 * This is the model class for table "sub_trial_balance".
 *
 * @property int $id
 * @property string $reporting_period
 * @property int $book_id
 * @property string $created_at
 */
class SubTrialBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_trial_balance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'book_id'], 'required'],
            [['book_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 20],
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
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    private function querySubTrialBalance(
        $to_reporting_period,
        $book_id
    ) {
        $r_period_date = DateTime::createFromFormat('Y-m', $to_reporting_period);
        $year = $r_period_date->format('Y');
        $from_reporting_period = $year . '-01';
        $query = Yii::$app->db->createCommand("SELECT 
        accounting_codes.object_code,
        accounting_codes.account_title as account_title,
        accounting_codes.normal_balance,
        (CASE
        WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
        ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
        END) as total_debit_credit,
        beginning_balance.total_beginning_balance as begin_balance
        FROM (
        SELECT
        jev_accounting_entries.object_code
        FROM jev_accounting_entries 
        LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
        WHERE 
         jev_preparation.book_id = :book_id
        AND jev_preparation.reporting_period <= :to_reporting_period
        GROUP BY jev_accounting_entries.object_code
        UNION
        SELECT 
           jev_beginning_balance_item.object_code
            FROM jev_beginning_balance
            LEFT JOIN jev_beginning_balance_item ON jev_beginning_balance.id = jev_beginning_balance_item.jev_beginning_balance_id
        WHERE  jev_beginning_balance.book_id=:book_id
        GROUP BY object_code
        ) as jev_object_codes
        LEFT JOIN (SELECT
        SUM(jev_accounting_entries.debit) as debit,
        SUM(jev_accounting_entries.credit) as credit,
        jev_accounting_entries.object_code 
        FROM jev_accounting_entries 
        LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
        WHERE 
         jev_preparation.book_id = :book_id
        AND jev_preparation.reporting_period >= :from_reporting_period
        AND jev_preparation.reporting_period <= :to_reporting_period
        GROUP BY jev_accounting_entries.object_code) as accounting_entries ON jev_object_codes.object_code = accounting_entries.object_code
        LEFT JOIN (SELECT 
            accounting_codes.object_code,
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
            AND jev_beginning_balance.book_id = :book_id) as beginning_balance ON jev_object_codes.object_code = beginning_balance.object_code
        LEFT JOIN accounting_codes ON jev_object_codes.object_code = accounting_codes.object_code
        
        WHERE   (CASE
        WHEN accounting_codes.normal_balance = 'Debit' THEN IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.debit,0) - IFNULL(accounting_entries.credit,0))
        ELSE IFNULL(beginning_balance.total_beginning_balance,0)+(IFNULL(accounting_entries.credit,0) - IFNULL(accounting_entries.debit,0))
        END) !=0

        ORDER BY object_code
        ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':book_id', $book_id)
            ->bindValue(':_year', $year)
            ->queryAll();
        return $query;
    }
    public function getItems()
    {

        return $this->querySubTrialBalance($this->reporting_period, $this->book_id);
    }
}
