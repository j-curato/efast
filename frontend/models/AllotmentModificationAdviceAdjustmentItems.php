<?php

namespace app\models;

use Yii;
use yii\validators\CompareValidator;

/**
 * This is the model class for table "allotment_modification_advice_adjustment_items".
 *
 * @property int $id
 * @property int $fk_record_allotment_entry_id
 * @property int $fk_allotment_modification_advice_id
 * @property float $amount
 * @property int $is_deleted
 * @property string $created_at
 *
 * @property AllotmentModificationAdvice $fkAllotmentModificationAdvice
 * @property RecordAllotmentEntries $fkRecordAllotmentEntry
 */
class AllotmentModificationAdviceAdjustmentItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'allotment_modification_advice_adjustment_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_record_allotment_entry_id', 'fk_allotment_modification_advice_id', 'amount'], 'required'],
            [['fk_record_allotment_entry_id', 'fk_allotment_modification_advice_id', 'is_deleted'], 'integer'],
            [['amount'], 'number', 'max' => 0, 'message' => 'qwe'],
            [['created_at'], 'safe'],
            ['amount', 'validateAllotmentBalance'],
            [['fk_allotment_modification_advice_id'], 'exist', 'skipOnError' => true, 'targetClass' => AllotmentModificationAdvice::class, 'targetAttribute' => ['fk_allotment_modification_advice_id' => 'id']],
            [['fk_record_allotment_entry_id'], 'exist', 'skipOnError' => true, 'targetClass' => RecordAllotmentEntries::class, 'targetAttribute' => ['fk_record_allotment_entry_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_record_allotment_entry_id' => 'Fk Record Allotment Entry ID',
            'fk_allotment_modification_advice_id' => 'Fk Allotment Modification Advice ID',
            'amount' => 'Amount',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
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

    // public function beforeValidate()
    // {

    //     if (!empty($this->fk_record_allotment_entry_id)) {

    //         $allotmentBal = Yii::$app->db->createCommand("SELECT 
    //         record_allotment_detailed.balAfterObligation
    //          FROM record_allotment_detailed 
    //         WHERE 
    //         record_allotment_detailed.allotment_entry_id = :id")
    //             ->bindValue(':id', $this->fk_record_allotment_entry_id)
    //             ->queryScalar();
    //         $old = $this->getOldAttributes();
    //         $oldAmount = !empty($this->id) ? floatval(abs($old['amount'])) : 0;
    //         $balance = floatval($allotmentBal) + $oldAmount    - abs($this->amount);
    //         echo abs($this->amount);
    //     }
    //     die();
    // }

    /**
     * Gets query for [[FkAllotmentModificationAdvice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkAllotmentModificationAdvice()
    {
        return $this->hasOne(AllotmentModificationAdvice::class, ['id' => 'fk_allotment_modification_advice_id']);
    }

    /**
     * Gets query for [[FkRecordAllotmentEntry]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkRecordAllotmentEntry()
    {
        return $this->hasOne(RecordAllotmentEntries::class, ['id' => 'fk_record_allotment_entry_id']);
    }
}
