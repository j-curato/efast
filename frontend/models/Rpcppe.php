<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rpcppe".
 *
 * @property string $rpcppe_number
 * @property string|null $reporting_period
 * @property int|null $fk_book_id
 * @property string|null $certified_by
 * @property string|null $approved_by
 * @property string|null $verified_by
 * @property string|null $verified_pos
 */
class Rpcppe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rpcppe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_book_id', 'fk_chart_of_account_id', 'fk_office_id', 'reporting_period'], 'required'],
            [['fk_book_id', 'fk_chart_of_account_id', 'fk_actbl_ofr', 'fk_office_id'], 'integer'],
            [['certified_by', 'approved_by', 'verified_by', 'verified_pos'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reporting_period' => 'Reporting Period',
            'fk_book_id' => 'Book ',
            'certified_by' => 'Certified By',
            'approved_by' => 'Approved By',
            'verified_by' => 'Verified By',
            'verified_pos' => 'Verified Pos',
            'fk_chart_of_account_id' => 'Chart of Account',
            'fk_actbl_ofr' => 'Accountable Officer',
            'fk_office_id' => 'Office',
        ];
    }
    public function getChartOfAccount()
    {
        return $this->hasOne(ChartOfAccounts::class, ['id' => 'fk_chart_of_account_id']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getAccountableOfficer()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_actbl_ofr']);
    }
}
