<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%inspection_report_items}}".
 *
 * @property int $id
 * @property int|null $fk_inspection_report_id
 * @property int|null $fk_request_for_inspection_item_id
 * @property int|null $is_deleted
 *
 * @property InspectionReport $fkInspectionReport
 */
class InspectionReportItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%inspection_report_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'fk_inspection_report_id', 'fk_request_for_inspection_item_id', 'is_deleted'], 'integer'],
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
            'fk_request_for_inspection_item_id' => 'Fk Request For Inspection Item ID',
            'is_deleted' => 'Is Deleted',
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
