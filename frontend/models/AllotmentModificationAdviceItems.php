<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "allotment_modification_advice_items".
 *
 * @property int $id
 * @property int|null $fk_allotment_modification_advice_id
 * @property int|null $fk_record_allotment_entries_id
 * @property float $amount
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property AllotmentModificationAdvice $fkAllotmentModificationAdvice
 * @property RecordAllotmentEntries $fkRecordAllotmentEntries
 */
class AllotmentModificationAdviceItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allotment_modification_advice_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_allotment_modification_advice_id',  'is_deleted'], 'integer'],
            [[
                'amount',
                'fk_allotment_modification_advice_id',
                'fk_office_id',
                'fk_division_id',
                'fk_chart_of_account_id'
            ], 'required'],
            [['amount'], 'number'],
            [['created_at'], 'safe'],
            [['fk_allotment_modification_advice_id'], 'exist', 'skipOnError' => true, 'targetClass' => AllotmentModificationAdvice::class, 'targetAttribute' => ['fk_allotment_modification_advice_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_allotment_modification_advice_id' => 'Fk Allotment Modification Advice ID',
            'amount' => 'Amount',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
            'fk_office_id' => 'Office',
            'fk_division_id' => 'Division',
            'fk_chart_of_account_id' => 'Chart of Account'
        ];
    }

    /**
     * Gets query for [[FkAllotmentModificationAdvice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAllotmentModificationAdvice()
    {
        return $this->hasOne(AllotmentModificationAdvice::class, ['id' => 'fk_allotment_modification_advice_id']);
    }

    /**
     * Gets query for [[FkRecordAllotmentEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
}
