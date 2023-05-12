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
                'is_cancelled'
            ], 'integer'],
            [['pr_date', 'created_at', 'cancelled_at'], 'safe'],
            [['aoq_number'], 'string', 'max' => 255],
            [['aoq_number'], 'unique'],
            [['pr_date', 'pr_rfq_id'], 'required'],
            [[
                'aoq_number',
                'pr_date',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'aoq_number' => 'Aoq Number',
            'pr_rfq_id' => 'RFQ Number',
            'pr_date' => 'Date',
            'created_at' => 'Created At',
            'is_cancelled' => 'Is Cancel',
            'cancelled_at=' => 'Cancelled  At'
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
