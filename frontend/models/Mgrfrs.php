<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\models\CashDeposits;
use app\components\helpers\MyHelper;

/**
 * This is the model class for table "mgrfrs".
 *
 * @property int $id
 * @property int|null $fk_bank_branch_detail_id
 * @property int|null $fk_municipality_id
 * @property int|null $fk_barangay_id
 * @property int|null $fk_office_id
 * @property string|null $purok
 * @property string|null $authorized_personnel
 * @property string|null $contact_number
 * @property string|null $saving_account_number
 * @property string|null $email_address
 * @property string|null $investment_type
 * @property string|null $investment_description
 * @property string|null $project_consultant
 * @property string|null $project_objective
 * @property string|null $project_beneficiary
 * @property float $matching_grant_amount
 * @property float $equity_amount
 * @property string $created_at
 *
 * @property BankBranchDetails $fkBankBranchDetail
 * @property Barangays $fkBarangay
 * @property Municipalities $fkMunicipality
 * @property Office $fkOffice
 */
class Mgrfrs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mgrfrs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_bank_branch_detail_id', 'fk_municipality_id', 'fk_barangay_id', 'fk_office_id', 'fk_province_id'], 'integer'],
            [['investment_type', 'investment_description', 'project_consultant', 'project_objective', 'project_beneficiary', 'organization_name'], 'string'],
            [[
                'matching_grant_amount',
                'equity_amount',
                'fk_municipality_id',
                'fk_province_id',
                'fk_barangay_id',
                'authorized_personnel',
                'contact_number',
                'email_address',
                'investment_type',
                'organization_name',
                'fk_bank_branch_detail_id',
                'fk_office_id'
            ], 'required'],
            [['matching_grant_amount', 'equity_amount'], 'number'],
            [['created_at'], 'safe'],
            ['email_address', 'trim'],
            ['email_address', 'email'], // This 
            [['purok', 'authorized_personnel', 'contact_number', 'saving_account_number', 'email_address', 'serial_number'], 'string', 'max' => 255],
            [['fk_bank_branch_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankBranchDetails::class, 'targetAttribute' => ['fk_bank_branch_detail_id' => 'id']],
            [['fk_barangay_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barangays::class, 'targetAttribute' => ['fk_barangay_id' => 'id']],
            [['fk_municipality_id'], 'exist', 'skipOnError' => true, 'targetClass' => Municipalities::class, 'targetAttribute' => ['fk_municipality_id' => 'id']],
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
            'fk_bank_branch_detail_id' => ' Bank Branch Detail ',
            'fk_municipality_id' => 'City/Municipality ',
            'fk_barangay_id' => ' Barangay ',
            'fk_office_id' => ' Office ',
            'purok' => 'Sitio/Purok',
            'authorized_personnel' => 'Name of Authorized Representative',
            'contact_number' => 'Contact Number of Authorized Representative',
            'saving_account_number' => 'Saving Account Number',
            'email_address' => 'Email Address',
            'investment_type' => 'Type of proposed Investment',
            'investment_description' => 'Description of Investment',
            'project_consultant' => 'Who did you consult when developing the idea for the project?',
            'project_objective' => 'Objective of the Proposed Project',
            'project_beneficiary' => 'Who will benefit from the proposed project?',
            'matching_grant_amount' => 'Amount of Matching Grant',
            'equity_amount' => 'Amount of Equity',
            'created_at' => 'Created At',
            'fk_province_id' => 'Province',
            'organization_name' => 'Organization Name',
            'serial_number' => 'Serial Number',

        ];
    }

    /**
     * Gets query for [[FkBankBranchDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankBranchDetail()
    {
        return $this->hasOne(BankBranchDetails::class, ['id' => 'fk_bank_branch_detail_id']);
    }

    /**
     * Gets query for [[FkBarangay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangay()
    {
        return $this->hasOne(Barangays::class, ['id' => 'fk_barangay_id']);
    }

    /**
     * Gets query for [[FkMunicipality]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMunicipality()
    {
        return $this->hasOne(Municipalities::class, ['id' => 'fk_municipality_id']);
    }
    /**
     * Gets query for [[FkProvince]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(Provinces::class, ['id' => 'fk_province_id']);
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
                if (empty($this->id)) {
                    $this->id = MyHelper::getUuid();
                }
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

        $lastNum = Yii::$app->db->createCommand("SELECT 
            CAST(SUBSTRING_INDEX(mgrfrs.serial_number,'-',-1) AS UNSIGNED) as last_num
            FROM 
            mgrfrs
            WHERE 
            fk_office_id = :office_id
            ORDER BY last_num DESC
            LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->queryScalar();
        $num = !empty($lastNum) ? intval($lastNum) + 1 : 1;

        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    public static function searchSerialNumber($text, $offset, $limit)
    {
        return Mgrfrs::find()
            ->addSelect([
                new Expression('CAST(mgrfrs.id as CHAR(50)) as id'),
                new Expression('mgrfrs.serial_number as text'),
            ])
            ->andWhere(['like', 'mgrfrs.serial_number', $text])
            ->offset($offset)
            ->limit($limit)
            ->asArray()->all();
    }
    public function getMgrfrDetails()
    {

        return self::find()
            ->addSelect([
                new Expression('CAST(mgrfrs.id as CHAR(50)) as id'),
                'mgrfrs.serial_number',
                'mgrfrs.organization_name',
                'barangays.barangay_name',
                'municipalities.municipality_name',
                'provinces.province_name',
                'office.office_name',
                'mgrfrs.purok',
                'mgrfrs.authorized_personnel',
                'mgrfrs.contact_number',
                'mgrfrs.saving_account_number',
                'mgrfrs.email_address',
                'mgrfrs.investment_type',
                'mgrfrs.investment_description',
                'mgrfrs.project_consultant',
                'mgrfrs.project_objective',
                'mgrfrs.project_beneficiary',
                'mgrfrs.matching_grant_amount',
                'mgrfrs.equity_amount',
                new Expression("banks.`name` as bank_name"),
                "bank_branches.branch_name",
                "bank_branch_details.bank_manager",
                new Expression("bank_branch_details.address as bank_address")

            ])
            ->join('LEFT JOIN', 'barangays', ' mgrfrs.fk_barangay_id = barangays.id')
            ->join('LEFT JOIN', 'provinces', 'mgrfrs.fk_province_id = provinces.id')
            ->join('LEFT JOIN', 'municipalities', 'mgrfrs.fk_municipality_id = municipalities.id')
            ->join('LEFT JOIN', 'office', 'mgrfrs.fk_office_id = office.id')
            ->join("LEFT JOIN", "bank_branch_details", "mgrfrs.fk_bank_branch_detail_id = bank_branch_details.id")
            ->join("LEFT JOIN", "bank_branches", "bank_branch_details.fk_bank_branch_id = bank_branches.id")
            ->join("LEFT JOIN", "banks", "bank_branches.fk_bank_id = banks.id")

            ->where('mgrfrs.id = :id', ['id' => $this->id])
            ->asArray()
            ->one();
    }

    /**
     *Filter Format
     *$liquidatedFilters = [
     *    [
     *        'value' => '2023-01',
     *        'operator' => '=',
     *        'column' => 'tbl_mg_liquidations.reporting_period'
     *    ]
     *];
     */

    public  function getCashBalanceById($liquidatedFilters = [], $depositFilters = [])
    {
        return self::find()
            ->addSelect([
                'mgrfrs.id',
                new Expression('COALESCE(deposit.total_deposit_equity,0) - COALESCE(liquidated.total_liquidation_equity,0) as balance_equity'),
                new Expression('COALESCE(deposit.total_deposit_grant,0) - COALESCE(liquidated.total_liquidation_grant,0) as balance_grant'),
                new Expression('COALESCE(deposit.total_deposit_other_amount,0) - COALESCE(liquidated.total_liquidation_other_amount,0) as balance_other_amount')

            ])
            ->leftJoin(
                ['liquidated' => static::buildLiquidationQuery($liquidatedFilters)],
                'mgrfrs.id = liquidated.fk_mgrfr_id'
            )
            ->leftJoin(
                ['deposit' => static::buildDepositQuery($depositFilters)],
                'mgrfrs.id = deposit.fk_mgrfr_id'
            )
            ->andWhere('mgrfrs.id = :id', ['id' => $this->id])
            ->asArray()
            ->one();
        // ->createCommand()->getRawSql();
    }
    protected function buildLiquidationQuery($filters = [])
    {
        $qry =   Mgrfrs::find()
            ->addSelect([
                'due_diligence_reports.fk_mgrfr_id',
                new Expression('COALESCE(SUM(tbl_notification_to_pay.equity_amount),0) as total_liquidation_equity'),
                new Expression('COALESCE(SUM(tbl_notification_to_pay.matching_grant_amount),0) as total_liquidation_grant'),
                new Expression('COALESCE(SUM(tbl_notification_to_pay.other_amount),0) as total_liquidation_other_amount')
            ])
            ->join('JOIN', 'due_diligence_reports', ' mgrfrs.id = due_diligence_reports.fk_mgrfr_id')
            ->join('JOIN', 'tbl_notification_to_pay', ' due_diligence_reports.id = tbl_notification_to_pay.fk_due_diligence_report_id')
            ->join('JOIN', 'tbl_mg_liquidation_items', ' tbl_notification_to_pay.id = tbl_mg_liquidation_items.fk_notification_to_pay_id')
            ->join('JOIN', 'tbl_mg_liquidations', ' tbl_mg_liquidation_items.fk_mg_liquidation_id = tbl_mg_liquidations.id')
            ->andWhere('tbl_mg_liquidation_items.is_deleted = 0');

        if (!empty($filters)) {
            foreach ($filters as $val) {
                $qry->andWhere([$val['operator'], $val['column'], $val['value']]);
            }
        }
        return    $qry->groupBy('due_diligence_reports.fk_mgrfr_id');
    }
    protected function buildDepositQuery($filters = [])
    {
        $qry =  CashDeposits::find()
            ->addSelect([
                "cash_deposits.fk_mgrfr_id",
                new Expression("COALESCE(SUM(cash_deposits.equity_amount),0) as total_deposit_equity"),
                new Expression("COALESCE(SUM(cash_deposits.matching_grant_amount),0) as total_deposit_grant"),
                new Expression("COALESCE(SUM(cash_deposits.other_amount),0) as total_deposit_other_amount")
            ]);
        if (!empty($filters)) {
            foreach ($filters as $val) {
                $qry->andWhere([$val['operator'], $val['column'], $val['value']]);
            }
        }
        return  $qry->groupBy(' cash_deposits.fk_mgrfr_id');
    }
    public function getLiquidations($reporting_period)
    {

        return self::find()
            ->addSelect([
                "tbl_mg_liquidation_items.`date`",
                "tbl_mg_liquidation_items.dv_number",
                "due_diligence_reports.supplier_name",
                "due_diligence_reports.comments",
                "tbl_notification_to_pay.matching_grant_amount",
                "tbl_notification_to_pay.equity_amount",
                "tbl_notification_to_pay.other_amount"
            ])
            ->join("JOIN", "due_diligence_reports", " mgrfrs.id = due_diligence_reports.fk_mgrfr_id")
            ->join("JOIN", "tbl_notification_to_pay", " due_diligence_reports.id = tbl_notification_to_pay.fk_due_diligence_report_id")
            ->join("JOIN", "tbl_mg_liquidation_items", " tbl_notification_to_pay.id = tbl_mg_liquidation_items.fk_notification_to_pay_id")
            ->join("JOIN", "tbl_mg_liquidations", " tbl_mg_liquidation_items.fk_mg_liquidation_id = tbl_mg_liquidations.id")
            ->andWhere([
                "tbl_mg_liquidation_items.is_deleted" => 0
            ])
            ->andWhere([
                "tbl_mg_liquidations.reporting_period" => $reporting_period
            ])
            ->andWhere(['mgrfrs.id' => $this->id])
            ->asArray()
            ->all();
    }
    public function getCashDepositsByPeriod($reporting_period)
    {
        return CashDeposits::find()
            ->addSelect([
                "cash_deposits.particular",
                "cash_deposits.equity_amount",
                "cash_deposits.matching_grant_amount",
                "cash_deposits.other_amount"
            ])
            ->andWhere([
                "cash_deposits.fk_mgrfr_id" => $this->id,
            ])
            ->andWhere([
                "cash_deposits.reporting_period" => $reporting_period
            ])
            ->asArray()
            ->all();
    }
}
