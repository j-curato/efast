<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_fmi_physical_progress".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_fmi_subproject_id
 * @property string $date
 * @property int $physical_target
 * @property int $physical_accomplished
 * @property string $created_at
 *
 * @property FmiSubprojects $fkFmiSubproject
 */
class FmiPhysicalProgress extends \yii\db\ActiveRecord
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
        return 'tbl_fmi_physical_progress';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_fmi_subproject_id', 'date', 'physical_target', 'physical_accomplished', 'fk_office_id', 'reporting_period'], 'required'],
            [['id', 'fk_fmi_subproject_id', 'physical_target', 'physical_accomplished', 'fk_office_id'], 'integer'],
            [['physical_accomplished', 'physical_target'], 'number', 'min' => 0, 'max' => 100],
            [['date', 'created_at'], 'safe'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_fmi_subproject_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'fk_fmi_subproject_id' => ' Subproject Serial Number',
            'date' => 'Date',
            'physical_target' => 'Physical Target (%)',
            'physical_accomplished' => 'Physical Accomplished (%)',
            'created_at' => 'Created At',
            'fk_office_id' => 'Office',
            'reporting_period' => 'Reporting Period',
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
        $num = !empty($lastNum) ? floatval($lastNum) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
    private function querySerialLastNumber()
    {
        return self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num")
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->orderBy(['last_num' => SORT_DESC])
            ->limit(1)
            ->scalar();
    }
}
