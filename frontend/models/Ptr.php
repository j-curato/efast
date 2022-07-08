<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ptr".
 *
 * @property string|null $ptr_number
 * @property string|null $par_number
 * @property int|null $transfer_type_id
 * @property string|null $date
 * @property string|null $reason
 * @property string|null $employee_from
 * @property string|null $employee_to
 */
class Ptr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ptr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'transfer_type_id',
                'agency_from_id',
                'agency_to_id'
            ], 'integer'],
            [['date'], 'safe'],
            [[
                'reason',
                'employee_from',
                'employee_to'
            ], 'string'],
            [['ptr_number', 'par_number', 'employee_from', 'employee_to'], 'string', 'max' => 255],
            [[
                'ptr_number',
                'id',
                'fk_par_id',
                'par_number',
                'transfer_type_id',
                'date',
                'reason',
                'employee_from',
                'employee_to',
                'agency_from_id',
                'agency_to_id',
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
            'ptr_number' => 'Ptr Number',
            'par_number' => 'Par Number',
            'transfer_type_id' => 'Transfer Type',
            'date' => 'Date',
            'reason' => 'Reason',
            'employee_from' => 'Employee From',
            'employee_to' => 'Employee To',
            'agency_from_id' => 'Agency From',
            'agency_to_id' => 'Agency To'
        ];
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_to']);
    }
    public function getPar()
    {
        return $this->hasOne(Par::class, ['par_number' => 'par_number']);
    }
    public function getTransferType()
    {
        return $this->hasOne(TransferType::class, ['id' => 'transfer_type_id']);
    }
}
