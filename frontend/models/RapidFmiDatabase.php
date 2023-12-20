<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_rapid_fmi_database".
 *
 * @property string|null $province_name
 * @property string|null $municipality_name
 * @property string|null $barangay_name
 * @property string|null $purok
 * @property string|null $batch_name
 * @property int|null $project_duration
 * @property int|null $project_road_length
 * @property string|null $project_start_date
 * @property string|null $bank_account_name
 * @property string|null $bank_account_number
 * @property string|null $project_name
 * @property string|null $bank_manager
 * @property string|null $address
 * @property string|null $branch_name
 * @property string|null $bank_name
 * @property float|null $total_grant_deposit
 * @property float|null $total_deposit_equity
 * @property float|null $total_deposit_other
 * @property float|null $total_liquidated_equity
 * @property float|null $total_liquidated_grant
 * @property float|null $total_liquidated_other
 * @property float|null $grant_beginning_balance
 * @property float|null $equity_beginning_balance
 * @property float|null $other_beginning_balance
 * @property string|null $bank_certification_link
 * @property string|null $certificate_of_project_link
 * @property string|null $certificate_of_turnover_link
 * @property string|null $spcr_link
 */
class RapidFmiDatabase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_rapid_fmi_database';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purok', 'batch_name', 'project_name', 'address', 'branch_name', 'bank_certification_link', 'certificate_of_project_link', 'certificate_of_turnover_link', 'spcr_link'], 'string'],
            [['project_duration', 'project_road_length'], 'integer'],
            [['project_start_date'], 'safe'],
            [['total_grant_deposit', 'total_deposit_equity', 'total_deposit_other', 'total_liquidated_equity', 'total_liquidated_grant', 'total_liquidated_other', 'grant_beginning_balance', 'equity_beginning_balance', 'other_beginning_balance'], 'number'],
            [['province_name', 'municipality_name', 'barangay_name', 'bank_account_name', 'bank_account_number', 'bank_manager', 'bank_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'province_name' => 'Province Name',
            'municipality_name' => 'Municipality Name',
            'barangay_name' => 'Barangay Name',
            'purok' => 'Purok',
            'batch_name' => 'Batch Name',
            'project_duration' => 'Project Duration',
            'project_road_length' => 'Project Road Length',
            'project_start_date' => 'Project Start Date',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_number' => 'Bank Account Number',
            'project_name' => 'Project Name',
            'bank_manager' => 'Bank Manager',
            'address' => 'Address',
            'branch_name' => 'Branch Name',
            'bank_name' => 'Bank Name',
            'total_grant_deposit' => 'Total Grant Deposit',
            'total_deposit_equity' => 'Total Deposit Equity',
            'total_deposit_other' => 'Total Deposit Other',
            'total_liquidated_equity' => 'Total Liquidated Equity',
            'total_liquidated_grant' => 'Total Liquidated Grant',
            'total_liquidated_other' => 'Total Liquidated Other',
            'grant_beginning_balance' => 'Grant Beginning Balance',
            'equity_beginning_balance' => 'Equity Beginning Balance',
            'other_beginning_balance' => 'Other Beginning Balance',
            'bank_certification_link' => 'Bank Certification Link',
            'certificate_of_project_link' => 'Certificate Of Project Link',
            'certificate_of_turnover_link' => 'Certificate Of Turnover Link',
            'spcr_link' => 'Spcr Link',
        ];
    }
}
