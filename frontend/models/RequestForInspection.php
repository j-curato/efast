<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%request_for_inspection}}".
 *
 * @property int $id
 * @property string $rfi_number
 * @property string|null $date
 * @property int|null $fk_chairperson
 * @property int|null $fk_inspector
 * @property int|null $fk_property_unit
 * @property string $created_at
 *
 * @property RequestForInspectionItems[] $requestForInspectionItems
 */
class RequestForInspection extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     * 
     */
    public static function tableName()
    {
        return '{{%request_for_inspection}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rfi_number', 'date', 'fk_requested_by'], 'required'],
            [['id', 'fk_chairperson', 'fk_inspector', 'fk_property_unit', 'fk_pr_office_id', 'is_final', 'fk_requested_by'], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['rfi_number'], 'string', 'max' => 255],
            [['rfi_number'], 'unique'],
            [['id'], 'unique'],
            [[
                'rfi_number',
                'date',
                'fk_pr_office_id',
                'fk_chairperson',
                'fk_inspector',
                'fk_property_unit',
                'fk_requested_by',


            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rfi_number' => 'Rfi Number',
            'date' => 'Date',
            'fk_chairperson' => 'Chairperson',
            'fk_inspector' => 'Inspector',
            'fk_property_unit' => 'Property Unit',
            'fk_pr_office_id' => 'Requested By Division',
            'created_at' => 'Created At',
            'is_final' => 'Final',
            'fk_requested_by' => 'Requested By',


        ];
    }

    /**
     * Gets query for [[RequestForInspectionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestForInspectionItems()
    {
        return $this->hasMany(RequestForInspectionItems::class, ['fk_request_for_inspection_id' => 'id']);
    }

    public function getChairperson()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_chairperson']);
    }
    public function getInspector()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_inspector']);
    }
    public function getPropertyUnit()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_property_unit']);
    }
    public function getOffice()
    {
        return $this->hasOne(PrOffice::class, ['id' => 'fk_pr_office_id']);
    }
}
