<?php

namespace app\models;

use Yii;
use ErrorException;
use app\models\Employee;
use yii\helpers\ArrayHelper;
use app\components\helpers\MyHelper;

/**
 * This is the model class for table "po_transaction".
 *
 * @property int $id
 * @property int|null $po_responsibility_center_id
 * @property string|null $payee
 * @property string|null $particular
 * @property float|null $amount
 * @property string|null $payroll_number
 *
 * @property ResponsibilityCenter $responsibilityCenter
 */
class PoTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'po_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['po_responsibility_center_id', 'fk_requested_by'], 'integer'],
            [['po_responsibility_center_id', 'amount', 'particular', 'reporting_period', 'fk_requested_by'], 'required'],
            [['payee', 'particular'], 'string'],
            [['amount'], 'number'],
            [['payroll_number', 'tracking_number', 'reporting_period'], 'string', 'max' => 100],
            [[
                'payee',
                'particular',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            [['po_responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoResponsibilityCenter::class, 'targetAttribute' => ['po_responsibility_center_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_responsibility_center_id' => 'Responsibility Center ',
            'payee' => 'Payee',
            'particular' => 'Particular',
            'amount' => 'Gross Amount',
            'payroll_number' => 'Payroll Number',
            'tracking_number' => 'Tracking Number',
            'reporting_period' => 'Reporting Period',
            'fk_requested_by' => 'Requested By',
        ];
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoResponsibilityCenter()
    {
        return $this->hasOne(PoResponsibilityCenter::class, ['id' => 'po_responsibility_center_id']);
    }
    public function getRequestedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_requested_by']);
    }
    public function insertIars($iars)
    {
        try {
            $oldIars = ArrayHelper::getColumn($this->queryIars(), 'fk_iar_id');
            $iarsToRemove = array_diff($oldIars, $iars);
            if (!empty($iarsToRemove)) {
                $delete = $this->deleteIarItems($iarsToRemove);
                if ($delete !== true) {
                    throw new ErrorException($delete);
                }
            }
            $iarsToInsert  = array_diff($iars, $oldIars);
            foreach ($iarsToInsert as $val) {
                $item = new PoTransactionIarItems();
                $item->fk_po_transaction_id = $this->id;
                $item->fk_iar_id = $val;
                if (!$item->validate()) {
                    throw new ErrorException(json_encode($item->errors));
                }
                if (!$item->save(false)) {
                    throw new ErrorException('Transaction IARS Save Error');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function deleteIarItems($iarsToRemove)
    {
        try {
            $generatedParams = MyHelper::generateParams($iarsToRemove);
            $generatedParamName = $generatedParams['paramNames'];
            $iarItemsIdsToRemove =  Yii::$app->db->createCommand("SELECT 
                    po_transaction_iar_items.id    
                    FROM  po_transaction_iar_items
                    WHERE po_transaction_iar_items.fk_po_transaction_id = :id
                    AND po_transaction_iar_items.is_deleted= 0
                    AND po_transaction_iar_items.fk_iar_id IN ($generatedParamName)", $generatedParams['params'])
                ->bindValue(':id', $this->id)
                ->queryAll();
            foreach ($iarItemsIdsToRemove as $item) {
                $item = PoTransactionIarItems::findOne($item);
                $item->is_deleted = 1;
                if (!$item->save(false)) {
                    throw new ErrorException('Delete IAR Failed');
                }
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
    private function queryIars()
    {
        return Yii::$app->db->createCommand("SELECT 
                    po_transaction_iar_items.id as item_id,
                    po_transaction_iar_items.fk_iar_id,
                    iar.iar_number
                FROM po_transaction_iar_items 
                JOIN iar ON po_transaction_iar_items.fk_iar_id = iar.id
                WHERE po_transaction_iar_items.is_deleted = 0 
                AND po_transaction_iar_items.fk_po_transaction_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getIarItemsA()
    {
        return $this->queryIars();
    }
}
