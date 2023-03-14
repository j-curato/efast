<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rlsddp_index".
 *
 * @property string|null $accountable_officer
 * @property string|null $supervisor
 * @property int $id
 * @property string $serial_number
 * @property string $date
 * @property string $blotter_date
 * @property string|null $circumstances
 * @property string|null $police_station
 * @property string $blottered
 * @property string|null $office_name
 * @property string|null $status
 */
class RlsddpIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rlsddp_index';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accountable_officer', 'supervisor', 'circumstances'], 'string'],
            [['id', 'serial_number', 'date', 'blotter_date'], 'required'],
            [['id'], 'integer'],
            [['date', 'blotter_date'], 'safe'],
            [['serial_number', 'police_station', 'office_name', 'status'], 'string', 'max' => 255],
            [['blottered'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'accountable_officer' => 'Accountable Officer',
            'supervisor' => 'Supervisor',
            'id' => 'ID',
            'serial_number' => 'Serial Number',
            'date' => 'Date',
            'blotter_date' => 'Blotter Date',
            'circumstances' => 'Circumstances',
            'police_station' => 'Police Station',
            'blottered' => 'Blottered',
            'office_name' => 'Office Name',
            'status' => 'Status',
        ];
    }
}
