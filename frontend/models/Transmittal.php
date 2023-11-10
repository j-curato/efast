<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transmittal".
 *
 * @property int $id
 * @property int|null $cash_disbursement_id
 * @property string|null $transmittal_number
 * @property string|null $location
 *
 * @property CashDisbursement $cashDisbursement
 */
class Transmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transmittal_number', 'date'], 'required'],
            [['fk_approved_by', 'fk_officer_in_charge'], 'integer'],
            [['transmittal_number'], 'string', 'max' => 100],
            [['location', 'date'], 'string', 'max' => 20],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'transmittal_number' => 'Transmittal Number',
            'location' => 'Location',
            'date' => 'Date',
            'fk_approved_by' => 'Approved By',
            'fk_officer_in_charge' => 'Officer In-Charge',
        ];
    }

    /**
     * Gets query for [[CashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'cash_disbursement_id']);
    }
    public function getTransmittalEntries()
    {
        return $this->hasMany(TransmittalEntries::class, ['transmittal_id' => 'id']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getOfficerInCharge()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_officer_in_charge']);
    }
    public function getViewItems()
    {
        return Yii::$app->db->createCommand("SELECT 
            transmittal_entries.id as item_id,
            dv_aucs.id as dv_id,
            cash_disbursement.issuance_date,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.ada_number,
            cash_disbursement.reporting_period,
            payee.account_name as payee,
            dv_aucs.particular,
            dv_aucs.dv_number,
            t_dv.amtDisbursed,
            t_dv.taxWitheld,
            cash_disbursement.is_cancelled
            FROM transmittal_entries
            JOIN dv_aucs ON transmittal_entries.fk_dv_aucs_id = dv_aucs.id
            JOIN cash_disbursement_items ON dv_aucs.id = cash_disbursement_items.fk_dv_aucs_id
            JOIN cash_disbursement ON cash_disbursement_items.fk_cash_disbursement_id = cash_disbursement.id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN (SELECT 
            dv_aucs_entries.dv_aucs_id,
            SUM(dv_aucs_entries.amount_disbursed)as amtDisbursed,
            SUM(COALESCE(dv_aucs_entries.vat_nonvat,0) + COALESCE(dv_aucs_entries.ewt_goods_services,0)+COALESCE(dv_aucs_entries.compensation,0))as taxWitheld
            FROM dv_aucs_entries 
            WHERE dv_aucs_entries.is_deleted = 0
            GROUP BY dv_aucs_entries.dv_aucs_id ) as t_dv ON dv_aucs.id = t_dv.dv_aucs_id 
            WHERE 
            transmittal_entries.is_deleted = 0
            AND transmittal_entries.transmittal_id = :id
            AND cash_disbursement_items.is_deleted = 0
            AND cash_disbursement.is_cancelled = 0
            AND NOT EXISTS (SELECT c.parent_disbursement FROM cash_disbursement  c WHERE c.is_cancelled  = 1
            AND c.parent_disbursement IS NOT NULL AND c.parent_disbursement  = cash_disbursement.id)")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getItems()
    {
        return Yii::$app->db->createCommand("SELECT 
                transmittal_entries.id as item_id,
                dv_aucs.id as dv_id,
                cash_disbursement.issuance_date,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.ada_number,
                cash_disbursement.reporting_period,
                payee.account_name as payee,
                dv_aucs.particular,
                dv_aucs.dv_number,
                t_dv.amtDisbursed,
                t_dv.taxWitheld,
                cash_disbursement.is_cancelled
                FROM transmittal_entries
                JOIN dv_aucs ON transmittal_entries.fk_dv_aucs_id = dv_aucs.id
                JOIN cash_disbursement_items ON dv_aucs.id = cash_disbursement_items.fk_dv_aucs_id
                JOIN cash_disbursement ON cash_disbursement_items.fk_cash_disbursement_id = cash_disbursement.id
                LEFT JOIN payee ON dv_aucs.payee_id = payee.id
                LEFT JOIN (SELECT 
                dv_aucs_entries.dv_aucs_id,
                SUM(dv_aucs_entries.amount_disbursed)as amtDisbursed,
                SUM(COALESCE(dv_aucs_entries.vat_nonvat,0) + COALESCE(dv_aucs_entries.ewt_goods_services,0)+COALESCE(dv_aucs_entries.compensation,0))as taxWitheld
                FROM dv_aucs_entries 
                WHERE dv_aucs_entries.is_deleted = 0
                GROUP BY dv_aucs_entries.dv_aucs_id ) as t_dv ON dv_aucs.id = t_dv.dv_aucs_id 
                WHERE 
                transmittal_entries.is_deleted = 0
                AND transmittal_entries.transmittal_id = :id
                AND cash_disbursement_items.is_deleted = 0
                AND cash_disbursement.is_cancelled = 0
                AND NOT EXISTS (SELECT c.parent_disbursement FROM cash_disbursement  c WHERE c.is_cancelled  = 1
                AND c.parent_disbursement IS NOT NULL AND c.parent_disbursement  = cash_disbursement.id)")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
}
