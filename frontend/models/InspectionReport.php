<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%inspection_report}}".
 *
 * @property int $id
 * @property string $ir_number
 * @property string $created_at
 */
class InspectionReport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inspection_report}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ir_number'], 'required'],
            [['id'], 'integer'],
            [['created_at'], 'safe'],
            [['ir_number',], 'string', 'max' => 255],
            [['ir_number'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ir_number' => 'Ir Number',

            'created_at' => 'Created At',
        ];
    }
    public function getInspectionReportItems()
    {
        return $this->hasMany(InspectionReportItems::class, ['fk_inspection_report_id' => 'id']);
    }
}
