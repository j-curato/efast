<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%purchase_request_index}}".
 *
 * @property int $id
 * @property string $pr_number
 * @property string|null $office_name
 * @property string|null $division
 * @property string|null $division_program_unit
 * @property string|null $activity_name
 * @property string|null $requested_by
 * @property string|null $approved_by
 * @property string|null $book_name
 * @property string|null $purpose
 * @property string|null $date
 */
class PurchaseRequestIndex extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%purchase_request_index}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['activity_name', 'requested_by', 'approved_by', 'purpose'], 'string'],
            [['date'], 'safe'],
            [['pr_number', 'office_name', 'division', 'division_program_unit', 'book_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_number' => 'Pr Number',
            'office_name' => 'Office Name',
            'division' => 'Division',
            'division_program_unit' => 'Division Program Unit',
            'activity_name' => 'Activity Name',
            'requested_by' => 'Requested By',
            'approved_by' => 'Approved By',
            'book_name' => 'Book Name',
            'purpose' => 'Purpose',
            'date' => 'Date',
        ];
    }
}
