<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use DateTime;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_fmi_bank_deposits".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $deposit_date
 * @property string $reporting_period
 * @property int $fk_fmi_bank_deposit_type_id
 * @property int $fk_fmi_subproject_id
 * @property string $created_at
 *
 * @property FmiBankDepositTypes $fkFmiBankDepositType
 * @property FmiSubprojects $fkFmiSubproject
 */
class FmiBankDeposits extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'generateId' => [
                'class' => GenerateIdBehavior::class
            ],
            'history' => [

                'class' => HistoryLogsBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_bank_deposits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'deposit_date', 'reporting_period', 'fk_fmi_bank_deposit_type_id', 'fk_fmi_subproject_id',
                'particular',
                'deposit_amount',
                'fk_office_id'
            ], 'required'],
            [['id', 'fk_fmi_bank_deposit_type_id', 'fk_fmi_subproject_id', 'fk_office_id'], 'integer'],
            [['deposit_date', 'created_at'], 'safe'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['deposit_amount'], 'number'],
            [['id'], 'unique'],
            [['particular'], 'string'],
            [['fk_fmi_bank_deposit_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiBankDepositTypes::class, 'targetAttribute' => ['fk_fmi_bank_deposit_type_id' => 'id']],
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
            'deposit_date' => 'Deposit Date',
            'reporting_period' => 'Reporting Period',
            'fk_fmi_bank_deposit_type_id' => ' FMI Bank Deposit Type ',
            'fk_fmi_subproject_id' => ' FMI Subproject ',
            'created_at' => 'Created At',
            'particular' => 'Particular',
            'deposit_amount' => 'Deposit Amount',
            'fk_office_id' => 'Office',
        ];
    }

    /**
     * Gets query for [[FmiBankDepositType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiBankDepositType()
    {
        return $this->hasOne(FmiBankDepositTypes::class, ['id' => 'fk_fmi_bank_deposit_type_id']);
    }

    /**
     * Gets query for [[FmiSubproject]].
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
            if (!$this->isNewRecord) {

                if (!empty($this->getDirtyAttributes()['reporting_period'])) {
                    $oldYear = (new DateTime($this->getOldAttribute('reporting_period')))->format('Y');
                    $newYear = (new DateTime($this->getDirtyAttributes()['reporting_period']))->format('Y');
                    if ($oldYear !== $newYear) {
                        $this->serial_number = $this->generateSerialNumber();
                    }
                }
                // if (!empty($this->getDirtyAttributes()['fk_fmi_bank_deposit_type_id'])) {
                //     if ($this->getOldAttribute('fk_fmi_bank_deposit_type_id') != $this->fk_fmi_bank_deposit_type_id) {
                //         $this->serial_number = $this->updateSerialNumber();
                //     }
                // }
            }
            return true;
        }
        return false;
    }
    private function updateSerialNumber()
    {
        $serial_number =  explode('-', $this->serial_number);
        // $serial_number[0] = strtoupper($this->office->office_name);
        $serial_number[1] = $this->fmiBankDepositType->deposit_type;
        return implode('-', $serial_number);
    }
    private function generateSerialNumber()
    {
        $year = DateTime::createFromFormat('Y-m', $this->reporting_period)->format('Y');
        $lastNum  = self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) AS last_num")
            ])
            ->andWhere(['fk_office_id' => $this->fk_office_id])
            ->andWhere([
                'LIKE',
                'serial_number',
                $year
            ])
            ->orderBy(['last_num' => SORT_DESC])
            ->limit(1)
            ->scalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;
        return strtoupper($this->office->office_name) . '-'
            //  . $this->fmiBankDepositType->deposit_type . '-' 
            . $year . '-' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }
}
