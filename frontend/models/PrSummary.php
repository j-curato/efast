<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%pr_summary}}".
 *
 * @property string|null $po_number
 * @property string|null $aoq_number
 * @property string|null $rfq_number
 * @property string|null $pr_number
 * @property string|null $payee
 * @property string|null $purpose
 * @property int|null $po_id
 * @property int|null $aoq_id
 * @property int|null $rfq_id
 * @property int|null $pr_id
 */
class PrSummary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pr_summary}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purpose'], 'string'],
            [['po_id', 'aoq_id', 'rfq_id', 'pr_id'], 'integer'],
            [['po_number', 'aoq_number', 'rfq_number', 'pr_number', 'payee'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'po_number' => 'Po Number',
            'aoq_number' => 'Aoq Number',
            'rfq_number' => 'Rfq Number',
            'pr_number' => 'Pr Number',
            'payee' => 'Payee',
            'purpose' => 'Purpose',
            'po_id' => 'Po ID',
            'aoq_id' => 'Aoq ID',
            'rfq_id' => 'Rfq ID',
            'pr_id' => 'Pr ID',
        ];
    }
}
