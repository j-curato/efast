<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rlsddp".
 *
 * @property int $id
 * @property string $serial_number
 * @property string $date
 * @property int $fk_acctbl_offr
 * @property int $is_blottered
 * @property string|null $police_station
 * @property int $status
 * @property int $fk_supvr
 * @property string|null $circumstances
 * @property string $created_at
 *
 * @property Employee $fkAcctblOffr
 * @property PropertyStatus $fkPropertyStatus
 * @property Employee $fkSupvr
 */
class Rlsddp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rlsddp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'date', 'fk_acctbl_offr', 'status', 'fk_supvr', 'is_blottered', 'fk_office_id'], 'required'],
            [['id', 'fk_acctbl_offr', 'is_blottered', 'status', 'fk_supvr', 'fk_office_id'], 'integer'],
            [['date', 'created_at', 'blotter_date'], 'safe'],
            [['circumstances'], 'string'],
            [['serial_number', 'police_station'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_acctbl_offr'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_acctbl_offr' => 'employee_id']],
            [['fk_supvr'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_supvr' => 'employee_id']],
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
            'fk_acctbl_offr' => 'Accountable Officer',
            'is_blottered' => 'Is Blottered',
            'police_station' => 'Police Station',
            'status' => ' Property Status ',
            'fk_supvr' => 'Direct Suppervisor',
            'circumstances' => 'Circumstances',
            'fk_office_id' => 'Office',
            'blotter_date' => 'Blotter Date',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkAcctblOffr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkAcctblOffr()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_acctbl_offr']);
    }

    /**
     * Gets query for [[FkPropertyStatus]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[FkSupvr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkSupvr()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_supvr']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
}
