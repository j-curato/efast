<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_aoq".
 *
 * @property int $id
 * @property string|null $aoq_number
 * @property int|null $pr_rfq_id
 * @property string|null $pr_date
 * @property string $created_at
 */
class PrAoq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_aoq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'pr_rfq_id',
                'is_cancelled',
                'fk_office_id',
                'aoq_number'
            ], 'integer'],
            [['pr_date', 'created_at', 'cancelled_at'], 'safe'],
            [['aoq_number'], 'string', 'max' => 255],
            [['aoq_number'], 'unique'],
            [['pr_date', 'pr_rfq_id', 'fk_office_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'aoq_number' => 'AOQ No.',
            'pr_rfq_id' => 'RFQ No.',
            'pr_date' => 'Date',
            'created_at' => 'Created At',
            'is_cancelled' => 'Is Cancel',
            'cancelled_at=' => 'Cancelled  At',
            'fk_office_id' => 'Office'
        ];
    }
    public function getPrAoqEntries()
    {
        return $this->hasMany(PrAoqEntries::class, ['pr_aoq_id' => 'id']);
    }
    public function getRfq()
    {
        return $this->hasOne(PrRfq::class, ['id' => 'pr_rfq_id']);
    }
}
