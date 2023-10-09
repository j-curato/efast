<?php

namespace app\models;

use common\models\User;
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
            [['date', 'fk_requested_by', 'transaction_type', 'fk_office_id', 'fk_division_id', 'fk_property_unit', 'fk_chairperson'], 'required'],
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
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(rfi_number,'-',-1)  AS UNSIGNED) as last_number
         FROM request_for_inspection
         WHERE fk_office_id = :office_id
         ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->queryScalar();
        $nxtNum = !empty($query) ? intval($query) + 1 : 1;
        return strtoupper($this->office->office_name) . '-' . date('Y') . '-' .  str_pad($nxtNum, 4, '0', STR_PAD_LEFT);
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
            AND rfi_without_po_items.is_deleted !=1")
            ->bindValue(':id', $this->id)

            ->queryAll();
    }
    public function getWithPoItems()
    {
        return Yii::$app->db->createCommand("SELECT
                    request_for_inspection_items.id,
                    pr_purchase_order_items_aoq_items.id as po_aoq_item_id,
                    pr_purchase_order_item.serial_number as po_number,
                    payee.account_name as payee,
                    pr_stock.stock_title,
                    REPLACE(pr_purchase_request_item.specification,'[n]','\n') as specification,
                    pr_purchase_request.purpose,
                    request_for_inspection_items.quantity,
                    request_for_inspection_items.from as date_from,
                    request_for_inspection_items.to as date_to,
                    pr_project_procurement.title as project_title,
                    pr_office.division,
                    pr_office.unit,
                    pr_purchase_request_item.quantity - IFNULL(aoq_items_quantity.quantity,0) as balance_quantity,
                    unit_of_measure.unit_of_measure,
                    pr_aoq_entries.amount as unit_cost,
                    pr_purchase_order_item.fk_pr_purchase_order_id as po_id
                   
            FROM 
            request_for_inspection_items						
            INNER JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
            INNER JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
            INNER JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
            INNER JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
            INNER JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
            LEFT  JOIN  payee ON pr_aoq_entries.payee_id = payee.id
            LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
            LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
            LEFT JOIN pr_office ON pr_project_procurement.pr_office_id = pr_office.id
            LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            LEFT JOIN (SELECT 
                request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id,
                SUM(request_for_inspection_items.quantity) as quantity
                FROM request_for_inspection_items GROUP BY request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id) as aoq_items_quantity
                 ON pr_purchase_order_items_aoq_items.id = aoq_items_quantity.fk_pr_purchase_order_items_aoq_item_id
            WHERE request_for_inspection_items.fk_request_for_inspection_id = :id
            AND request_for_inspection_items.is_deleted !=1
            ")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getInspectionReportLinks()
    {
        return  Yii::$app->db->createCommand("SELECT 
            inspection_report.id,
            inspection_report.ir_number,
            iar.iar_number,
            iar.id as iar_id
            FROM request_for_inspection
            INNER JOIN request_for_inspection_items ON request_for_inspection.id = request_for_inspection_items.fk_request_for_inspection_id
            INNER JOIN inspection_report_items ON request_for_inspection_items.id = inspection_report_items.fk_request_for_inspection_item_id
            INNER JOIN inspection_report ON inspection_report_items.fk_inspection_report_id = inspection_report.id
            LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
            WHERE 
            request_for_inspection.id = :id
            GROUP BY 
            inspection_report.id,
            inspection_report.ir_number,
            iar.iar_number,
            iar.id 
            UNION
            SELECT 
            inspection_report.id,
            inspection_report.ir_number,
            iar.iar_number,
            iar.id as iar_id
            FROM request_for_inspection
            INNER JOIN rfi_without_po_items ON request_for_inspection.id = rfi_without_po_items.fk_request_for_inspection_id
            INNER JOIN inspection_report_no_po_items ON rfi_without_po_items.id = inspection_report_no_po_items.fk_rfi_without_po_item_id
            INNER JOIN inspection_report ON inspection_report_no_po_items.fk_inspection_report_id = inspection_report.id
            LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
            WHERE 
            request_for_inspection.id = :id
            GROUP BY 
            inspection_report.id,
            inspection_report.ir_number,
            iar.iar_number,
            iar.id ")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $userData = User::getUserDetails();
        $empOffice  = $userData->employee->office->office_name ?? '';
        $userOffice = Yii::$app->user->identity->office->office_name ?? '';
        $chairPersonLbl  = 'Inpection Officer';

        if (strtolower($empOffice)  === 'ro' || strtolower($userOffice) === 'ro') {
            $chairPersonLbl  = 'Chairperon';
        }
        return [
            'id' => 'ID',
            'rfi_number' => 'RFI No.',
            'date' => 'Date',
            'fk_chairperson' => $chairPersonLbl,
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
    public function getRequestedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_requested_by']);
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
