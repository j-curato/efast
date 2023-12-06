<?php

namespace app\models;

use Yii;
use app\models\Office;
use app\components\helpers\MyHelper;
use yii\db\Expression;

/**
 * This is the model class for table "cash_deposits".
 *
 * @property int $id
 * @property int|null $fk_mgrfr_id
 * @property string $serial_number
 * @property string $reporting_period
 * @property string $date
 * @property string $particular
 * @property float|null $matching_grant_amount
 * @property float|null $equity_amount
 * @property float|null $other_amount
 * @property string $created_at
 *
 * @property Mgrfrs $fkMgrfr
 */
class CashDeposits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cash_deposits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_mgrfr_id', 'fk_office_id'], 'integer'],
            [['reporting_period', 'date', 'particular', 'fk_mgrfr_id', 'fk_office_id'], 'required'],
            [['date', 'created_at'], 'safe'],
            [['particular'], 'string'],
            [['matching_grant_amount', 'equity_amount', 'other_amount'], 'number'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['fk_mgrfr_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mgrfrs::class, 'targetAttribute' => ['fk_mgrfr_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_mgrfr_id' => ' MG RFR Serial No.',
            'serial_number' => 'Serial Number',
            'reporting_period' => 'Reporting Period',
            'date' => 'Date',
            'particular' => 'Particular',
            'matching_grant_amount' => 'Matching Grant ',
            'equity_amount' => 'Equity ',
            'other_amount' => 'Others ',
            'fk_office_id' => 'Office ',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkMgrfr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMgrfr()
    {
        return $this->hasOne(Mgrfrs::class, ['id' => 'fk_mgrfr_id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {

            if (empty($this->id)) {
                $this->id = MyHelper::getUuid();
            }
            if (empty($this->serial_number)) {
                $this->serial_number = $this->generateSerialNumber();
            }
        }
        return true;
    }
    private function generateSerialNumber()
    {
        $lastNum = Yii::$app->db->createCommand("SELECT 
                CAST(SUBSTRING_INDEX(cash_deposits.serial_number,'-',-1) AS UNSIGNED) as last_num
                FROM cash_deposits
                ORDER BY last_num DESC LIMIT 1")
            ->queryScalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;
        return date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

}
