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
                'fk_property_id',
                'fk_issued_by',
                'fk_actual_user',
                'fk_received_by',
                'fk_approved_by',
                'fk_transfer_type_id',
                'fk_to_agency_id',
            ], 'integer'],
            [['date'], 'safe'],

            [[
                'fk_property_id',
                'fk_issued_by',
                'fk_actual_user',
                'fk_received_by',
                'fk_approved_by',
                'fk_transfer_type_id',
                'fk_to_agency_id',
                'date',
            ], 'required'],
            [['ptr_number'], 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ptr_number' => 'PTR Number',
            'fk_property_id' => 'Property',
            'fk_issued_by' => 'Issued By',
            'fk_actual_user' => 'Actual User',
            'fk_received_by' => 'Received By',
            'fk_approved_by' => 'Approved By',
            'fk_transfer_type_id' => 'Transfer Type',
            'date' => 'Date',
            'agency_from_id' => 'Agency From',
            'fk_to_agency_id' => 'Agency To',
            'created_at' => 'Created At',

        ];
    }
}
