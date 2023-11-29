<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_fmi_project_completions".
 *
 * @property int $id
 * @property int|null $fk_office_id
 * @property int|null $fk_fmi_subproject_id
 * @property string $serial_number
 * @property string $completion_date
 * @property string $turnover_date
 * @property string|null $spcr_link
 * @property string|null $certificate_of_project_link
 * @property string|null $certificate_of_turnover_link
 * @property string $reporting_period
 * @property string $created_at
 *
 * @property FmiSubprojects $fkFmiSubproject
 * @property Office $fkOffice
 */
class FmiProjectCompletions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_project_completions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'completion_date', 'turnover_date', 'reporting_period'], 'required'],
            [['id', 'fk_office_id', 'fk_fmi_subproject_id'], 'integer'],
            [['completion_date', 'turnover_date', 'created_at'], 'safe'],
            [['spcr_link', 'certificate_of_project_link', 'certificate_of_turnover_link'], 'string'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_fmi_subproject_id' => 'id']],
            [['fk_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['fk_office_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_office_id' => 'Fk Office ID',
            'fk_fmi_subproject_id' => 'Fk Fmi Subproject ID',
            'serial_number' => 'Serial Number',
            'completion_date' => 'Completion Date',
            'turnover_date' => 'Turnover Date',
            'spcr_link' => 'SPRC Link',
            'certificate_of_project_link' => 'Certificate Of Project Link',
            'certificate_of_turnover_link' => 'Certificate Of Turnover Link',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkFmiSubproject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubproject()
    {
        return $this->hasOne(FmiSubprojects::class, ['id' => 'fk_fmi_subproject_id']);
    }

    /**
     * Gets query for [[FkOffice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
}
