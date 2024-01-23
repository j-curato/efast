<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "acic_in_bank".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $date
 * @property string $created_at
 *
 * @property AcicInBankItems[] $acicInBankItems
 */
class AcicInBank extends \yii\db\ActiveRecord
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
        return 'acic_in_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['serial_number', 'date'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[AcicInBankItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcicInBankItems()
    {
        return $this->hasMany(AcicInBankItems::className(), ['fk_acic_in_bank' => 'id']);
    }
}
