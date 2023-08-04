<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%supplemental_ppmp}}".
 *
 * @property int $id
 * @property string|null $date
 * @property string $serial_number
 * @property int $budget_year
 * @property string $cse_type
 * @property int $fk_office_id
 * @property int $fk_division_id
 * @property int $fk_division_program_unit_id
 * @property int|null $fk_prepared_by
 * @property int|null $fk_reviewed_by
 * @property int|null $fk_approved_by
 * @property int|null $fk_certified_funds_available_by
 * @property string $created_at
 *
 * @property SupplementalPpmpCse[] $supplementalPpmpCses
 * @property SupplementalPpmpNonCse[] $supplementalPpmpNonCses
 */
class SupplementalPpmp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supplemental_ppmp}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id', 'serial_number',
                'budget_year',
                'cse_type',
                'fk_office_id',
                'fk_division_id',
                'fk_division_program_unit_id',
                'fk_prepared_by',
                'fk_reviewed_by',
                'fk_approved_by',
                'fk_certified_funds_available_by',
                'is_supplemental',

            ], 'required'],
            [[
                'id', 'budget_year', 'fk_office_id', 'fk_division_id', 'fk_division_program_unit_id', 'fk_prepared_by',
                'fk_reviewed_by', 'fk_approved_by', 'fk_certified_funds_available_by',
                'is_final', 'is_supplemental',
                'fk_created_by'
            ], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['serial_number', 'cse_type'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['is_supplemental'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'serial_number' => 'Serial Number',
            'budget_year' => 'Budget Year',
            'cse_type' => 'Cse Type',
            'fk_office_id' => ' Office ',
            'fk_division_id' => ' Division ',
            'fk_division_program_unit_id' => ' Division Program Unit ',
            'fk_prepared_by' => ' Prepared ',
            'fk_reviewed_by' => ' Reviewed ',
            'fk_approved_by' => ' Approved ',
            'fk_certified_funds_available_by' => ' Certified Funds Available ',
            'created_at' => 'Created At',
            'is_final' => 'Final',
            'is_supplemental' => 'Supplemental',
            'fk_created_by' => 'Created By'
        ];
    }

    /**
     * Gets query for [[SupplementalPpmpCses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplementalPpmpCses()
    {
        return $this->hasMany(SupplementalPpmpCse::class, ['fk_supplemental_ppmp_id' => 'id']);
    }

    /**
     * Gets query for [[SupplementalPpmpNonCses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplementalPpmpNonCses()
    {
        return $this->hasMany(SupplementalPpmpNonCse::class, ['fk_supplemental_ppmp_id' => 'id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getDivisionName()
    {
        return $this->hasOne(Divisions::class, ['id' => 'fk_division_id']);
    }
    public function getDivisionProgramUnit()
    {
        return $this->hasOne(DivisionProgramUnit::class, ['id' => 'fk_division_program_unit_id']);
    }
    public function getPreparedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_prepared_by']);
    }
    public function getReviewedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_reviewed_by']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getCertifiedFundsAvailableBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_certified_funds_available_by']);
    }
}
