<?php

namespace app\models;

use DateTime;
use DateTimeImmutable;
use Yii;

/**
 * This is the model class for table "{{%travel_order}}".
 *
 * @property int $id
 * @property string $date
 * @property string $destination
 * @property string $created_at
 */
class TravelOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%travel_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'id',
                'destination',
                'purpose',
                'fk_approved_by',
                'fk_budget_officer',
                'date',
                'type'
            ], 'required'],
            [[
                'id',
                'fk_approved_by',
                'fk_budget_officer',
                'fk_recommending_approval'
            ], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['destination', 'purpose', 'expected_outputs'], 'string'],
            [['type', 'to_number'], 'string', 'max' => 255],
            [['id'], 'unique'],
            // [['date'], 'validateDate'],


        ];
    }
    // public function validateDate($attribute, $params, $validator)
    // {
    //     $date_now = new DateTimeImmutable();
    //     $date2    =  DateTime::createFromFormat('Y-m-d', $this->$attribute)->format('Y-m-d');

    //     if (strtotime($date2) < strtotime($date_now->format('Y-m-d'))) {
    //         $this->addError($attribute, 'Date Cannot be Less Than ' . $date_now->format('F d, Y'));
    //     }
    // }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'destination' => 'Destination',
            'created_at' => 'Created At',
            'destination' => 'Destination',
            'purpose' => 'Purpose',
            'expected_outputs' => 'Expected Outputs',
            'fk_recommending_approval' => 'Recommending Approval',
            'fk_approved_by' => 'Approved By',
            'fk_budget_officer' => 'Budget Officer',
            'type' => 'Type',
            'to_number' => 'Travel Order Number',

        ];
    }
    public function getRecommendingApproval()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_recommending_approval']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function getBudgetOfficer()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_budget_officer']);
    }
}
