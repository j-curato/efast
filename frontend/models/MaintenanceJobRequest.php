<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "maintenance_job_request".
 *
 * @property int $id
 * @property int $fk_responsibility_center_id
 * @property int $fk_employee_id
 * @property string $date_requested
 * @property string $problem_description
 * @property string|null $recommendation
 * @property string|null $action_taken
 * @property string $created_at
 */
class MaintenanceJobRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'maintenance_job_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'fk_responsibility_center_id', 'fk_employee_id', 'date_requested', 'problem_description', 'mjr_number','fk_approved_by'], 'required'],
            [['id', 'fk_responsibility_center_id', 'fk_employee_id','fk_approved_by'], 'integer'],
            [['date_requested', 'created_at'], 'safe'],
            [['mjr_number'], 'unique'],
            [['problem_description', 'recommendation', 'action_taken', 'mjr_number'], 'string'],
            [['id'], 'unique'],
            [[
                'id',
                'mjr_number',
                'fk_responsibility_center_id',
                'fk_employee_id',
                'date_requested',
                'problem_description',
                'recommendation',
                'action_taken',
                'created_at',

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
            'fk_responsibility_center_id' => ' Responsibility Center ',
            'fk_employee_id' => 'Requested By',
            'date_requested' => 'Date Requested',
            'problem_description' => 'Problem Description',
            'recommendation' => 'Recommendation',
            'created_at' => 'Created At',
            'action_taken' => 'Action Taken',
            'mjr_number' => 'MJR Number',
            'fk_approved_by' => 'Approved By',
        ];
    }
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'fk_responsibility_center_id']);
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_employee_id']);
    }
}
