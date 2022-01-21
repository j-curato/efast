<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_purchase_request".
 *
 * @property int $id
 * @property string|null $pr_number
 * @property string|null $date
 * @property int|null $book_id
 * @property int|null $pr_project_procurement_id
 * @property string|null $purpose
 * @property int|null $requested_by_id
 * @property int|null $approved_by_id
 * @property string $created_at
 */
class PrPurchaseRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_purchase_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'created_at'], 'safe'],
            [['book_id', 'pr_project_procurement_id', 'requested_by_id', 'approved_by_id'], 'integer'],
            [['purpose'], 'string'],
            [['pr_number'], 'string', 'max' => 255],
            [['pr_number'], 'unique'],
            [[
                'pr_number',
                'date',
                'book_id',
                'pr_project_procurement_id',
                'purpose',
                'requested_by_id',
                'approved_by_id',

            ], 'required'],

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
            'date' => 'Date Propose',
            'book_id' => 'Book ',
            'pr_project_procurement_id' => ' Project Procurement ',
            'purpose' => 'Purpose',
            'requested_by_id' => 'Requested By ',
            'approved_by_id' => 'Approved By ',
            'created_at' => 'Created At',
        ];
    }
    public function getRequestedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'requested_by_id']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'approved_by_id']);
    }
    public function getPrItem()
    {
        return $this->hasMany(PrPurchaseRequestItem::class, ['pr_purchase_request_id' => 'id']);
    }
    public function getProjectProcurement()
    {
        return $this->hasOne(PrProjectProcurement::class, ['id' => 'pr_project_procurement_id']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
}
