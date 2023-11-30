<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;
use yii\db\Expression;

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
    public function behaviors()
    {
        return [
            GenerateIdBehavior::class,
            HistoryLogsBehavior::class
        ];
    }
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
            [['completion_date', 'turnover_date', 'reporting_period', 'date'], 'required'],
            [['id', 'fk_office_id', 'fk_fmi_subproject_id'], 'integer'],
            [['completion_date', 'turnover_date', 'created_at', 'date'], 'safe'],
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
            'fk_office_id' => ' Office ',
            'fk_fmi_subproject_id' => 'Subproject Serial Number',
            'serial_number' => 'Serial Number',
            'completion_date' => 'Completion Date',
            'turnover_date' => 'Turnover Date',
            'spcr_link' => 'SPRC Link',
            'certificate_of_project_link' => 'Certificate Of Project Link',
            'certificate_of_turnover_link' => 'Certificate Of Turnover Link',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
            'date' => 'Date',
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
    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->serial_number)) {
                    $this->serial_number = $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNumber()
    {
        $lastNum = $this->querySerialLastNumber();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
    private function querySerialLastNumber()
    {
        return self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED) AS last_num")
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->orderBy(['last_num' => SORT_DESC])
            ->limit(1)
            ->scalar();
    }
}
