<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_fmi_bank_account_closures".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_fmi_subproject_id
 * @property string $reporting_period
 * @property string $date
 * @property string|null $bank_certification_link
 * @property string $created_at
 *
 * @property FmiSubprojects $fkFmiSubproject
 */
class FmiBankAccountClosures extends \yii\db\ActiveRecord
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
        return 'tbl_fmi_bank_account_closures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_fmi_subproject_id', 'reporting_period', 'date', 'fk_office_id'], 'required'],
            [['fk_fmi_subproject_id', 'id', 'fk_office_id'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['bank_certification_link'], 'string'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
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
            'fk_fmi_subproject_id' => '  Subproject Serial Number',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'bank_certification_link' => 'Bank Certification Link',
            'created_at' => 'Created At',
            'fk_office_id' => 'Office'
        ];
    }

    /**
     * Gets query for [[FkFmiSubproject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkFmiSubproject()
    {
        return $this->hasOne(FmiSubprojects::class, ['id' => 'fk_fmi_subproject_id']);
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
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1)AS UNSIGNED)as last_num")
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->orderBy(['last_num' => SORT_DESC])
            ->limit(1)
            ->scalar();
    }
}
