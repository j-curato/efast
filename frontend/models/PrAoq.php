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
            [['pr_rfq_id'], 'integer'],
            [['pr_date', 'created_at'], 'safe'],
            [['aoq_number'], 'string', 'max' => 255],
            [['aoq_number'], 'unique'],
            [['pr_date', 'pr_rfq_id'], 'required'],
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
        ];
    }
    public function getPrAoqEntries()
    {
        return $this->hasMany(PrAoqEntries::class, ['pr_aoq_id' => 'id']);
    }
}
