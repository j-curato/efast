<?php

namespace app\models;

use app\components\helpers\MyHelper;
use Yii;

/**
 * This is the model class for table "due_diligence_report_items".
 *
 * @property int $id
 * @property int $fk_due_diligence_report_id
 * @property string $customer_name
 * @property int|null $is_deleted
 * @property string $created_at
 *
 * @property DueDiligenceReports $fkDueDiligenceReport
 */
class DueDiligenceReportItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'due_diligence_report_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_due_diligence_report_id', 'customer_name'], 'required'],
            [['id', 'fk_due_diligence_report_id', 'is_deleted'], 'integer'],
            [['created_at'], 'safe'],
            [['customer_name'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['fk_due_diligence_report_id'], 'exist', 'skipOnError' => true, 'targetClass' => DueDiligenceReports::class, 'targetAttribute' => ['fk_due_diligence_report_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_due_diligence_report_id' => 'Fk Due Diligence Report ID',
            'customer_name' => 'Customer Name',
            'is_deleted' => 'Is Deleted',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkDueDiligenceReport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkDueDiligenceReport()
    {
        return $this->hasOne(DueDiligenceReports::class, ['id' => 'fk_due_diligence_report_id']);
    }
    public function beforeSave($insert)
    {

        if ($this->isNewRecord) {
            if (empty($this->id)) {
                $this->id = MyHelper::getUuid();
            }
        }
        return true;
    }
}
