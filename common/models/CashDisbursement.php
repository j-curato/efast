<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%cash_disbursement}}".
 *
 * @property int $id
 * @property int|null $book_id
 * @property int|null $dv_aucs_id
 * @property string|null $reporting_period
 * @property string|null $mode_of_payment
 * @property string|null $check_or_ada_no
 * @property int $is_cancelled
 * @property string|null $issuance_date
 * @property string|null $ada_number
 * @property string|null $begin_time
 * @property string|null $out_time
 * @property int|null $parent_disbursement
 *
 * @property AdvancesEntries[] $advancesEntries
 * @property Books $book
 * @property DvAucs $dvAucs
 * @property TransmittalEntries[] $transmittalEntries
 */
class CashDisbursement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cash_disbursement}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'dv_aucs_id', 'is_cancelled', 'parent_disbursement'], 'integer'],
            [['begin_time', 'out_time'], 'safe'],
            [['reporting_period', 'mode_of_payment', 'issuance_date'], 'string', 'max' => 50],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::className(), 'targetAttribute' => ['book_id' => 'id']],
            [['dv_aucs_id'], 'exist', 'skipOnError' => true, 'targetClass' => DvAucs::className(), 'targetAttribute' => ['dv_aucs_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ID',
            'dv_aucs_id' => 'Dv Aucs ID',
            'reporting_period' => 'Reporting Period',
            'mode_of_payment' => 'Mode Of Payment',
            'check_or_ada_no' => 'Check Or Ada No',
            'is_cancelled' => 'Is Cancelled',
            'issuance_date' => 'Issuance Date',
            'ada_number' => 'Ada Number',
            'begin_time' => 'Begin Time',
            'out_time' => 'Out Time',
            'parent_disbursement' => 'Parent Disbursement',
        ];
    }

    /**
     * Gets query for [[AdvancesEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AdvancesEntriesQuery
     */
    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::className(), ['cash_disbursement_id' => 'id']);
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\BooksQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::className(), ['id' => 'book_id']);
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\DvAucsQuery
     */
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::className(), ['id' => 'dv_aucs_id']);
    }

    /**
     * Gets query for [[TransmittalEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TransmittalEntriesQuery
     */
    public function getTransmittalEntries()
    {
        return $this->hasMany(TransmittalEntries::className(), ['cash_disbursement_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CashDisbursementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CashDisbursementQuery(get_called_class());
    }
}
