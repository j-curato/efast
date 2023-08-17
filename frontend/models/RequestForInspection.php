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
            [['date', 'fk_requested_by', 'transaction_type', 'fk_office_id', 'fk_division_id'], 'required'],
            [[
                'id', 'fk_chairperson', 'fk_inspector', 'fk_property_unit', 'fk_pr_office_id', 'is_final', 'fk_requested_by', 'fk_office_id',
                'fk_division_id',
                'fk_created_by'
            ], 'integer'],
            [['date', 'created_at'], 'safe'],
            [['rfi_number', 'transaction_type'], 'string', 'max' => 255],
            [['rfi_number', 'id'], 'unique'],

        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
                if (empty($this->rfi_number)) {
                    $this->rfi_number = $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function generateSerialNumber()
    {
        $ofc = Office::findOne($this->fk_office_id);
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(rfi_number,'-',-1)  AS UNSIGNED) as last_number
         FROM request_for_inspection
         WHERE fk_office_id = :office_id
         ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':office_id', $ofc->id)
            ->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num  = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $zero .= 0;
        }
        return strtoupper($ofc->office_name) . '-' . date('Y') . '-' . $zero . $num;
    }
    public function getNoPoItems()
    {

        return Yii::$app->db->createCommand("SELECT  
        rfi_without_po_items.id,
        rfi_without_po_items.project_name,
       IFNULL(pr_stock.stock_title,'') as stock_title,
       pr_stock.id as stock_id,
       rfi_without_po_items.specification,
       REPLACE(rfi_without_po_items.specification,'[n]','\n') as specification_view,
       unit_of_measure.unit_of_measure,
       unit_of_measure.id as unit_of_measure_id,
       payee.account_name as payee_name,
       payee.id as payee_id,
       rfi_without_po_items.unit_cost,
       rfi_without_po_items.quantity,
       rfi_without_po_items.from_date,
       rfi_without_po_items.to_date
       
       FROM request_for_inspection
       LEFT JOIN rfi_without_po_items ON request_for_inspection.id = rfi_without_po_items.fk_request_for_inspection_id
       LEFT JOIN pr_stock ON rfi_without_po_items.fk_stock_id = pr_stock.id
       LEFT JOIN payee ON rfi_without_po_items.fk_payee_id = payee.id
       LEFT JOIN unit_of_measure ON rfi_without_po_items.fk_unit_of_measure_id = unit_of_measure.id
       WHERE 
       request_for_inspection.id = :id
       AND rfi_without_po_items.is_deleted !=1
       ")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rfi_number' => 'RFI No.',
            'date' => 'Date',
            'fk_chairperson' => 'Chairperson',
            'fk_inspector' => 'Inspector',
            'fk_property_unit' => 'Property Unit',
            'fk_pr_office_id' => 'Requested By Division',
            'created_at' => 'Created At',
            'is_final' => 'Final',
            'fk_requested_by' => 'Requested By',
            'fk_responsibility_center_id' => 'Division',
            'transaction_type' => 'Transaction Type',
            'fk_office_id' => 'Office',
            'fk_division_id' => 'Division',
            'fk_created_by' => 'Created By'


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
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'fk_responsibility_center_id']);
    }
}
