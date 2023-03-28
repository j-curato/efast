<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iirup".
 *
 * @property int $id
 * @property string $serial_number
 * @property int $fk_acctbl_ofr
 * @property int $fk_approved_by
 * @property string $created_at
 *
 * @property Employee $fkAcctblOfr
 * @property Employee $fkApprovedBy
 * @property IirupItems[] $iirupItems
 */
class Iirup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'iirup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_number', 'fk_acctbl_ofr', 'fk_approved_by', 'reporting_period', 'fk_office_id'], 'required'],
            [['id', 'fk_acctbl_ofr', 'fk_approved_by', 'fk_office_id'], 'integer'],
            [['created_at'], 'safe'],
            [['serial_number', 'reporting_period'], 'string', 'max' => 255],
            [['serial_number'], 'unique'],
            [['id'], 'unique'],
            [['fk_acctbl_ofr'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_acctbl_ofr' => 'employee_id']],
            [['fk_approved_by'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::class, 'targetAttribute' => ['fk_approved_by' => 'employee_id']],
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
            'fk_acctbl_ofr' => ' Accountable Officer',
            'fk_approved_by' => ' Approved By',
            'created_at' => 'Created At',
            'reporting_period' => 'Reporting Period',
            'fk_office_id' => 'Office',
        ];
    }

    /**
     * Gets query for [[FkAcctblOfr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcctblOfr()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_acctbl_ofr']);
    }

    /**
     * Gets query for [[FkApprovedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }

    /**
     * Gets query for [[IirupItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIirupItems()
    {
        return $this->hasMany(IirupItems::class, ['fk_iirup_id' => 'id']);
    }
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
}
