<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%inspection_report_no_po_items}}".
 *
 * @property int $id
 * @property int|null $fk_inspection_report_id
 * @property int|null $fk_rfi_without_po_item_id
 * @property string $created_at
 *
 * @property InspectionReport $fkInspectionReport
 */
class InspectionReportNoPoItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inspection_report_no_po_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'fk_inspection_report_id', 'fk_rfi_without_po_item_id'], 'integer'],
            [['created_at'], 'safe'],
            [['id'], 'unique'],
            [['fk_inspection_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => InspectionReport::className(), 'targetAttribute' => ['fk_inspection_report_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_inspection_report_id' => 'Fk Inspection Report ID',
            'fk_rfi_without_po_item_id' => 'Fk Rfi Without Po Item ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkInspectionReport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkInspectionReport()
    {
        return $this->hasOne(InspectionReport::className(), ['id' => 'fk_inspection_report_id']);
    }
}
