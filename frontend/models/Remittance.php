<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "remittance".
 *
 * @property int $id
 * @property string|null $reporting_period
 * @property string $created_at
 */
class Remittance extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'remittance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'book_id', 'remittance_number', 'reporting_period'], 'required'],
            [['id', 'payee_id', 'book_id'], 'integer'],
            [['created_at', 'type', 'payroll_id', 'remittance_number'], 'safe'],
            [['reporting_period'], 'string', 'max' => 20],
            [['id'], 'unique'],
            ['payee_id', 'required'],
            // ['payroll_id', 'required', 'when' => function ($model) {
            //     return $model->type == 'remittance_to_payee';
            // }, 'whenClient' => "function (attribute, value) {
            //     return $('#remittance-type').val() == 'remittance_to_payee';
            // }"],



        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reporting_period' => 'Reporting Period',
            'created_at' => 'Created At',
            'type' => 'Type',
            'book_id' => 'Book',
            'payee_id' => 'Payee',
            'payroll_id' => 'Payroll Number',
            'remittance_number' => 'Remittance Number',

        ];
    }
    public function getBook()
    {

        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getPayee()
    {

        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }
    public function getPayroll()
    {
        return $this->hasOne(Payroll::class, ['id' => 'payroll_id']);
    }
    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['fk_remittance_id' => 'id']);
    }
}
