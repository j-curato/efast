<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_rapid_mg_database".
 *
 * @property int $id
 * @property string|null $office_name
 * @property string|null $province_name
 * @property string|null $municipality_name
 * @property string|null $barangay_name
 * @property string|null $organization_name
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
 * @property string|null $bank_manager
 * @property string|null $address
 * @property string|null $bank_name
 * @property float|null $total_deposit_equity
 * @property float|null $total_deposit_grant
 * @property float|null $total_deposit_other_amount
 * @property float|null $total_liquidation_grant
 * @property float|null $total_liquidation_equity
 * @property float|null $total_liquidation_other_amount
 * @property float|null $balance_equity
 * @property float|null $balance_grant
 * @property float|null $balance_other_amount
 * @property int|null $notification_to_pay_count
 * @property int|null $due_diligence_report_count
 */
class RapidMgDatabase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_rapid_mg_database';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'matching_grant_amount', 'equity_amount'], 'required'],
            [['id', 'notification_to_pay_count', 'due_diligence_report_count'], 'integer'],
            [['organization_name', 'investment_type', 'investment_description', 'project_consultant', 'project_objective', 'project_beneficiary', 'address'], 'string'],
            [['matching_grant_amount', 'equity_amount', 'total_deposit_equity', 'total_deposit_grant', 'total_deposit_other_amount', 'total_liquidation_grant', 'total_liquidation_equity', 'total_liquidation_other_amount', 'balance_equity', 'balance_grant', 'balance_other_amount'], 'number'],
            [['office_name', 'province_name', 'municipality_name', 'barangay_name', 'purok', 'authorized_personnel', 'contact_number', 'saving_account_number', 'email_address', 'bank_manager', 'bank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'office_name' => 'Office Name',
            'province_name' => 'Province Name',
            'municipality_name' => 'Municipality Name',
            'barangay_name' => 'Barangay Name',
            'organization_name' => 'Organization Name',
            'purok' => 'Purok',
            'authorized_personnel' => 'Authorized Personnel',
            'contact_number' => 'Contact Number',
            'saving_account_number' => 'Saving Account Number',
            'email_address' => 'Email Address',
            'investment_type' => 'Investment Type',
            'investment_description' => 'Investment Description',
            'project_consultant' => 'Project Consultant',
            'project_objective' => 'Project Objective',
            'project_beneficiary' => 'Project Beneficiary',
            'matching_grant_amount' => 'Matching Grant Amount',
            'equity_amount' => 'Equity Amount',
            'bank_manager' => 'Bank Manager',
            'address' => 'Address',
            'bank_name' => 'Bank Name',
            'total_deposit_equity' => 'Total Deposit Equity',
            'total_deposit_grant' => 'Total Deposit Grant',
            'total_deposit_other_amount' => 'Total Deposit Other Amount',
            'total_liquidation_grant' => 'Total Liquidation Grant',
            'total_liquidation_equity' => 'Total Liquidation Equity',
            'total_liquidation_other_amount' => 'Total Liquidation Other Amount',
            'balance_equity' => 'Balance Equity',
            'balance_grant' => 'Balance Grant',
            'balance_other_amount' => 'Balance Other Amount',
            'notification_to_pay_count' => 'Notification To Pay Count',
            'due_diligence_report_count' => 'Due Diligence Report Count',
        ];
    }
}
