<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pre_repair_inspection".
 *
 * @property int $id
 * @property string $serial_number
 * @property string|null $date
 * @property string|null $findings
 * @property string|null $recommendation
 * @property int|null $fk_requested_by
 * @property int|null $fk_accountable_person
 * @property string $created_at
 */
class PreRepairInspection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pre_repair_inspection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'date', 'fk_requested_by','findings'], 'required'],
            [['id', 'fk_requested_by', 'fk_accountable_person'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['findings', 'recommendation'], 'string'],
            [['serial_number'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'findings',
                'recommendation',

            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
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
            'date' => 'Date',
            'findings' => 'Findings',
            'recommendation' => 'Recommendation',
            'fk_requested_by' => ' Requested By',
            'fk_accountable_person' => ' Accountable Person',
            'created_at' => 'Created At',
        ];
    }
    public function getRequestedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_requested_by']);
    }
    public function getAccountablePerson()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_accountable_person']);
    }
}
