<?php

namespace app\models;

use app\components\helpers\MyHelper;
use Yii;
use yii\db\Expression;

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
                'mgrfrs.equity_amount'

            ])
            ->join('LEFT JOIN', 'barangays', ' mgrfrs.fk_barangay_id = barangays.id')
            ->join('LEFT JOIN', 'provinces', 'mgrfrs.fk_province_id = provinces.id')
            ->join('LEFT JOIN', 'municipalities', 'mgrfrs.fk_municipality_id = municipalities.id')
            ->join('LEFT JOIN', 'office', 'mgrfrs.fk_office_id = office.id')
            ->where('mgrfrs.id = :id', ['id' => $this->id])
            ->asArray()
            ->one();
    }
}
