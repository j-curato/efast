<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "radai".
 *
 * @property int $id
 * @property string $date
 * @property string $reporting_period
 * @property int $fk_book_id
 * @property string $serial_number
 * @property string $created_at
 *
 * @property Books $fkBook
 */
class Radai extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'radai';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'date', 'reporting_period', 'fk_book_id', 'serial_number'], 'required'],
            [['id', 'fk_book_id'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['reporting_period', 'serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['fk_book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'reporting_period' => 'Reporting Period',
            'fk_book_id' => ' Book ',
            'serial_number' => 'Serial Number',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkBook]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }
    public function getRadaiItemsCheckNumbers()
    {

        return Yii::$app->db->createCommand("SELECT cash_disbursement.check_or_ada_no 
        FROM `radai_items` 
        JOIN lddap_adas ON radai_items.fk_lddap_ada_id   =lddap_adas.id
        JOIN cash_disbursement ON lddap_adas.fk_cash_disbursement_id = cash_disbursement.id
        WHERE radai_items.fk_radai_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getItemsPerDv()
    {
        return Yii::$app->db->createCommand("SELECT
                lddap_adas.serial_number as lddap_ada_number,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.ada_number,
                dv_aucs_index.ttlAmtDisbursed,
                dv_aucs_index.ttlTax,
                dv_aucs_index.payee,
                dv_aucs_index.orsNums,
                dv_aucs_index.dv_number,
                mode_of_payments.`name` as mode_of_payment_name,
                CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as uacs,
                cash_disbursement.issuance_date as check_date
                
                    FROM radai_items
                JOIN lddap_adas ON radai_items.fk_lddap_ada_id   = lddap_adas.id
                JOIN cash_disbursement ON lddap_adas.fk_cash_disbursement_id = cash_disbursement.id
                JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
                LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
                LEFT JOIN chart_of_accounts  ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
                WHERE 
                radai_items.is_deleted = 0
                AND cash_disbursement_items.is_deleted = 0
                AND radai_items.fk_radai_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
}
