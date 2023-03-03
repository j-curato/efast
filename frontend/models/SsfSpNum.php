<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ssf_sp_num".
 *
 * @property int $id
 * @property int $budget_year
 * @property int $fk_office_id
 * @property int $fk_citymun_id
 * @property string $project_name
 * @property string $cooperator
 * @property string $equipment
 * @property float $amount
 * @property string $date
 * @property string $status
 * @property string $created_at
 *
 * @property Citymun $fkCitymun
 * @property Office $fkOffice
 */
class SsfSpNum extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ssf_sp_num';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'budget_year', 'fk_office_id', 'fk_citymun_id', 'project_name', 'cooperator', 'equipment', 'amount', 'date', 'fk_ssf_sp_status_id', 'serial_number'], 'required'],
            [['id', 'budget_year', 'fk_office_id', 'fk_citymun_id', 'fk_ssf_sp_status_id'], 'integer'],
            [['project_name', 'equipment'], 'string'],
            [['amount'], 'number'],
            [['date', 'created_at'], 'safe'],
            [['cooperator', 'serial_number'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['fk_citymun_id'], 'exist', 'skipOnError' => true, 'targetClass' => Citymun::class, 'targetAttribute' => ['fk_citymun_id' => 'id']],
            [['fk_office_id'], 'exist', 'skipOnError' => true, 'targetClass' => Office::class, 'targetAttribute' => ['fk_office_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'budget_year' => 'Budget Year',
            'fk_office_id' => 'Office ',
            'fk_citymun_id' => 'City/Municipal',
            'project_name' => 'Project Name',
            'cooperator' => 'Cooperator',
            'equipment' => 'Equipment',
            'amount' => 'Amount',
            'date' => 'Date',
            'fk_ssf_sp_status_id' => 'Status',
            'serial_number' => 'Serial Number',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkCitymun]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Citymun::class, ['id' => 'fk_citymun_id']);
    }

    /**
     * Gets query for [[FkOffice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
}
