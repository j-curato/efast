<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "tbl_fmi_fund_releases".
 *
 * @property int $id
 * @property string $serial_number
 * @property int|null $fk_fmi_subproject_id
 * @property int|null $fk_tranche_id
 * @property int|null $fk_cash_disbursement_id
 * @property string $created_at
 *
 * @property CashDisbursement $fkCashDisbursement
 * @property FmiSubprojects $fkSubproject
 * @property FmiTranches $fkTranche
 */
class FmiFundReleases extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
            GenerateIdBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_fund_releases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_fmi_subproject_id', 'fk_tranche_id', 'fk_cash_disbursement_id'], 'required'],
            [['fk_fmi_subproject_id', 'fk_tranche_id', 'fk_cash_disbursement_id'], 'integer'],
            [['created_at'], 'safe'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['fk_cash_disbursement_id'], 'exist', 'skipOnError' => true, 'targetClass' => CashDisbursement::class, 'targetAttribute' => ['fk_cash_disbursement_id' => 'id']],
            [['fk_fmi_subproject_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiSubprojects::class, 'targetAttribute' => ['fk_fmi_subproject_id' => 'id']],
            [['fk_tranche_id'], 'exist', 'skipOnError' => true, 'targetClass' => FmiTranches::class, 'targetAttribute' => ['fk_tranche_id' => 'id']],
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
            'fk_fmi_subproject_id' => ' Subproject ',
            'fk_tranche_id' => ' Tranche ',
            'fk_cash_disbursement_id' => ' Cash Disbursement ',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkCashDisbursement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCashDisbursement()
    {
        return $this->hasOne(CashDisbursement::class, ['id' => 'fk_cash_disbursement_id']);
    }

    /**
     * Gets query for [[Subproject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFmiSubproject()
    {
        return $this->hasOne(FmiSubprojects::class, ['id' => 'fk_fmi_subproject_id']);
    }

    /**
     * Gets query for [[Tranche]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranche()
    {
        return $this->hasOne(FmiTranches::class, ['id' => 'fk_tranche_id']);
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
        return  date('Y') .'-'. str_pad($num, 5, '0', STR_PAD_LEFT);
    }
    private function querySerialLastNumber()
    {
        return self::find()
            ->addSelect([
                new Expression("CAST(SUBSTRING_INDEX(serial_number,'-',-1) AS UNSIGNED) as last_num")
            ])
            ->orderBy(['last_num' => SORT_DESC])
            ->limit(1)
            ->scalar();
    }
}
