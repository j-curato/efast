<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sliies".
 *
 * @property int $id
 * @property int|null $fk_cash_disbursement_id
 * @property string $serial_number
 * @property string $created_at
 *
 * @property CashDisbursement $fkCashDisbursement
 */
class Sliies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sliies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fk_cash_disbursement_id',
                'fk_certified_correct_by',
                'fk_approved_by',
                'fk_accounting_head',
            ], 'integer'],
            [['serial_number'], 'required'],
            [['created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['fk_cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['fk_cash_disbursement_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_cash_disbursement_id' => 'Fk Cash Disbursement ID',
            'serial_number' => 'Serial Number',
            'created_at' => 'Created At',
            'fk_certified_correct_by' => 'Certified Correct',
            'fk_approved_by' => 'Approved By',
            'fk_accounting_head' => 'Accounting Unit',

        ];
    }

    /**
     * Gets query for [[FkCashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'fk_cash_disbursement_id']);
    }
}
