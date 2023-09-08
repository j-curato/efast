<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "record_allotment_adjustments".
 *
 * @property int $id
 * @property int $fk_record_allotment_id
 * @property int $fk_record_allotment_entry_id
 * @property float $amount
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property RecordAllotmentEntries $fkRecordAllotmentEntries
 * @property RecordAllotments $fkRecordAllotment
 */
class RecordAllotmentAdjustments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_allotment_adjustments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_record_allotment_id', 'fk_record_allotment_entry_id', 'amount'], 'required'],
            [['fk_record_allotment_id', 'fk_record_allotment_entry_id', 'is_deleted'], 'integer'],
            [['amount'], 'number', 'max' => 0],
            ['amount', 'validateAllotmentBalance'],
            [['created_at'], 'safe'],
            [['fk_record_allotment_entry_id'], 'exist', 'skipOnError' => true, 'targetClass' => RecordAllotmentEntries::class, 'targetAttribute' => ['fk_record_allotment_entry_id' => 'id']],
            [['fk_record_allotment_id'], 'exist', 'skipOnError' => true, 'targetClass' => RecordAllotments::class, 'targetAttribute' => ['fk_record_allotment_id' => 'id']],
        ];
    }

    public function validateAllotmentBalance($attribute, $params, $validator)
    {
        if (!empty($this->fk_record_allotment_entry_id)) {
            $allotmentBal = Yii::$app->db->createCommand("SELECT 
            record_allotment_detailed.balAfterObligation
             FROM record_allotment_detailed 
            WHERE 
            record_allotment_detailed.allotment_entry_id = :id")
                ->bindValue(':id', $this->fk_record_allotment_entry_id)
                ->queryScalar();
            $oldAmount = !empty($this->id) ? floatval(abs($this->getOldAttributes()['amount'])) : 0;
            $balance = floatval($allotmentBal) + $oldAmount    - abs($this->amount);
            if ($balance < 0) {
                $this->addError($attribute, 'Balance of the Allotment is only  ' . number_format(floatval($allotmentBal)));
                return false;
                die();
            }
        }
        return true;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_record_allotment_id' => 'Fk Record Allotment ID',
            'fk_record_allotment_entry_id' => 'Fk Record Allotment Entries ID',
            'amount' => 'Amount',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkRecordAllotmentEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRecordAllotmentEntries()
    {
        return $this->hasOne(RecordAllotmentEntries::class, ['id' => 'fk_record_allotment_entry_id']);
    }

    /**
     * Gets query for [[FkRecordAllotment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRecordAllotment()
    {
        return $this->hasOne(RecordAllotments::class, ['id' => 'fk_record_allotment_id']);
    }
}
