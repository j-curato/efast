<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_project_procurement".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $pr_office_id
 * @property float|null $amount
 * @property int|null $employee_id
 */
class PrProjectProcurement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_project_procurement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [[
                'title',
                'pr_office_id',
                'employee_id',
                'amount'
            ], 'required'],
            [['pr_office_id', 'employee_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'pr_office_id' => ' Office ',
            'amount' => 'Amount',
            'employee_id' => 'Prepared By',
        ];
    }
    public function getOffice()
    {
        return $this->hasOne(PrOffice::class, ['id' => 'pr_office_id']);
    }
    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'employee_id']);
    }
}
