<?php

namespace app\models;

use Yii;
use DateTime;

/**
 * This is the model class for table "general_ledger".
 *
 * @property int $id
 * @property string $reporting_period
 * @property string $object_code
 * @property int $book_id
 * @property string $created_at
 */
class GeneralLedger extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_ledger';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reporting_period', 'object_code', 'book_id'], 'required'],
            [['book_id'], 'integer'],
            [['created_at'], 'safe'],
            [['reporting_period'], 'string', 'max' => 20],
            [['object_code'], 'string', 'max' => 255],
            [[
                'id',
                'reporting_period',
                'object_code',
                'book_id',
                'created_at',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'object_code' => 'Object Code',
            'book_id' => 'Book',
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getGeneralLedger()
    {
        return $this->hasOne(AccountingCodes::class, ['object_code' => 'object_code']);
    }

    public function queryGeneralLedger()
    {
        $to_reporting_period = $this->reporting_period;
        $reporting_period = DateTime::createFromFormat('Y-m', $to_reporting_period);
        $from_reporting_period = $reporting_period->format('Y') . '-01';
        $query = Yii::$app->db->createCommand("SELECT
                accounting_entries.*,
                chart_of_accounts.normal_balance,
                (CASE 
                    WHEN chart_of_accounts.normal_balance = 'Debit' THEN accounting_entries.debit - accounting_entries.credit
                    ELSE accounting_entries.credit - accounting_entries.debit
                END) as total
                FROM(
                SELECT  
                jev_preparation.reporting_period,
                jev_preparation.date,
                jev_preparation.explaination as particular,
                jev_preparation.jev_number,
                COALESCE(jev_accounting_entries.debit,0) as debit,
                COALESCE(jev_accounting_entries.credit,0) as credit,
                SUBSTRING_INDEX(jev_accounting_entries.object_code,'_',1) as uacs
                
                FROM jev_accounting_entries
                LEFT JOIN jev_preparation ON jev_accounting_entries.jev_preparation_id = jev_preparation.id
                LEFT JOIN books ON jev_preparation.book_id =  books.id
                WHERE jev_accounting_entries.object_code LIKE :object_code
                AND jev_preparation.reporting_period <= :to_reporting_period
                AND jev_preparation.reporting_period >=:from_reporting_period
                AND books.id = :book_id
                ) as accounting_entries
                INNER  JOIN chart_of_accounts ON accounting_entries.uacs = chart_of_accounts.uacs
                ORDER BY accounting_entries.`date`
              ")
            ->bindValue(':from_reporting_period', $from_reporting_period)
            ->bindValue(':to_reporting_period', $to_reporting_period)
            ->bindValue(':book_id', $this->book_id)
            ->bindValue(':object_code', $this->object_code . '%')
            ->queryAll();
        return $query;
    }
    public function getBeginningBalance()
    {
        $year = DateTime::createFromFormat('Y-m', $this->reporting_period)->format('Y');
        $beginning_balance = Yii::$app->db->createCommand("SELECT 
            SUBSTRING_INDEX(accounting_codes.object_code,'_',1) as object_code,
                COALESCE(SUM(jev_beginning_balance_item.credit),0)as credit,
                COALESCE(SUM(jev_beginning_balance_item.debit),0) as debit,
            (CASE
            WHEN accounting_codes.normal_balance = 'Debit' THEN COALESCE(SUM(jev_beginning_balance_item.debit),0)  - COALESCE(SUM(jev_beginning_balance_item.credit),0)
            ELSE COALESCE(SUM(jev_beginning_balance_item.credit),0) - COALESCE(SUM(jev_beginning_balance_item.debit),0)
            END) as balance
            FROM jev_beginning_balance_item 
            LEFT JOIN jev_beginning_balance ON jev_beginning_balance_item.jev_beginning_balance_id =jev_beginning_balance.id
            LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
            LEFT JOIN books ON jev_beginning_balance.book_id = books.id
            WHERE 
            jev_beginning_balance.`year` = :_year
            AND jev_beginning_balance.book_id = :book_id
            AND jev_beginning_balance_item.object_code LIKE :object_code
            GROUP BY accounting_codes.object_code,
            accounting_codes.normal_balance")
            ->bindValue(':_year', $year)
            ->bindValue(':book_id', $this->book_id)
            ->bindValue(':object_code', $this->object_code . '%')
            ->queryOne();
        return $beginning_balance;
    }
}
