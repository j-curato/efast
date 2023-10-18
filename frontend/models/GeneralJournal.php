<?php

namespace app\models;

use Yii;
use DateTime;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "general_journal".
 *
 * @property int $id
 * @property int $book_id
 * @property string|null $reporting_period
 */
class GeneralJournal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_journal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id'], 'required'],
            [['book_id'], 'integer'],
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
            'book_id' => 'Book',
            'reporting_period' => 'Reporting Period',
        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    private function queryGeneralJournal($book_id = '', $reporting_period = '')
    {

        $query = Yii::$app->db->createCommand("SELECT 
                jev_preparation.date,
                jev_preparation.jev_number,
                jev_preparation.explaination,
                COALESCE(jev_accounting_entries.debit,0)as debit,
                COALESCE(jev_accounting_entries.credit,0)as credit,
                accounting_codes.object_code,
                accounting_codes.account_title
                FROM jev_preparation
                INNER JOIN jev_accounting_entries ON jev_preparation.id  = jev_accounting_entries.jev_preparation_id
                INNER JOIN accounting_codes ON jev_accounting_entries.object_code = accounting_codes.object_code
                WHERE
                jev_preparation.ref_number = 'GJ'
                AND jev_preparation.reporting_period = :reporting_period
                AND jev_preparation.book_id = :book_id")
            ->bindValue('book_id', $book_id)
            ->bindValue('reporting_period', $reporting_period)
            ->queryAll();
        $result = ArrayHelper::index($query, null, 'jev_number');
        return $result;
    }
    public function getItems()
    {
        return $this->queryGeneralJournal($this->book_id, $this->reporting_period);
    }
    public static function generateGeneralJournal($book_id, $reporting_period)
    {
        return self::queryGeneralJournal($book_id, $reporting_period);
    }
}
