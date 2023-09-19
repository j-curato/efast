<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "it_maintenance_request".
 *
 * @property int $id
 * @property int|null $fk_requested_by
 * @property int|null $fk_worked_by
 * @property int|null $fk_division_id
 * @property string $serial_number
 * @property string $date_requested
 * @property string|null $date_accomplished
 * @property string $description
 * @property string $type
 * @property string $created_at
 *
 * @property Divisions $fkDivision
 * @property Employee $fkRequestedBy
 * @property Employee $fkWorkedBy
 */
class ItMaintenanceRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'it_maintenance_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'date_requested', 'description', 'type', 'fk_division_id', 'fk_requested_by'], 'required'],
            [['id', 'fk_requested_by', 'fk_worked_by', 'fk_division_id'], 'integer'],
            [['date_requested', 'date_accomplished', 'created_at'], 'safe'],
            [['description', 'action_taken'], 'string'],
            [['serial_number', 'type'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_division_id'], 'exist', 'skipOnError' => true, 'targetClass' => Divisions::class, 'targetAttribute' => ['fk_division_id' => 'id']],
            [['fk_requested_by'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_requested_by' => 'employee_id']],
            [['fk_worked_by'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_worked_by' => 'employee_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_requested_by' => ' Requested By',
            'fk_worked_by' => ' Worked By',
            'fk_division_id' => ' Division ',
            'serial_number' => 'Serial Number',
            'date_requested' => 'Date Requested',
            'date_accomplished' => 'Date Accomplished',
            'description' => 'Description',
            'action_taken' => 'Action Taken',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkDivision]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDivisions()
    {
        return $this->hasOne(Divisions::class, ['id' => 'fk_division_id']);
    }

    /**
     * Gets query for [[FkRequestedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_requested_by']);
    }

    /**
     * Gets query for [[FkWorkedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_worked_by']);
    }
    public function getHelpdeskCsf()
    {
        return $this->hasOne(ItHelpdeskCsf::class, ['fk_it_maintenance_request' => 'id']);
    }
}
