<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use Yii;
use ErrorException;
use ParentIterator;
use yii\helpers\ArrayHelper;
use app\components\helpers\MyHelper;
use app\behaviors\HistoryLogsBehavior;
use DateTime;
use yii\db\Expression;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int $responsibility_center_id
 * @property int $payee_id
 * @property string $particular
 * @property float $gross_amount
 * @property string|null $tracking_number
 * @property string|null $earmark_no
 * @property string|null $payroll_number
 * @property string|null $transaction_date
 * @property string|null $transaction_time
 *
 * @property Payee $payee
 * @property ResponsibilityCenter $responsibilityCenter
 */
class Transaction extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            HistoryLogsBehavior::class,
            GenerateIdBehavior::class
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'responsibility_center_id',
                'payee_id',
                'particular',
                'transaction_date', 'type',
                'fk_book_id',
                'fk_certified_by',
                'fk_certified_budget_by',
                'fk_certified_cash_by',
                'fk_approved_by'
            ], 'required'],
            [[
                'responsibility_center_id',
                'payee_id',
                'is_local',
                'fk_book_id',
                'is_cancelled',
                'fk_certified_by',
                'fk_certified_budget_by',
                'fk_certified_cash_by',
                'fk_approved_by'
            ], 'integer'],
            [['gross_amount'], 'number'],
            [['tracking_number', 'earmark_no', 'payroll_number', 'type'], 'string', 'max' => 255],
            [['transaction_date'], 'string', 'max' => 50],
            [['transaction_time'], 'string', 'max' => 20],
            [['particular'], 'string',],
            [['payee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Payee::class, 'targetAttribute' => ['payee_id' => 'id']],
            [['responsibility_center_id'], 'exist', 'skipOnError' => true, 'targetClass' => ResponsibilityCenter::class, 'targetAttribute' => ['responsibility_center_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'responsibility_center_id' => 'Responsibility Center ',
            'payee_id' => 'Payee ',
            'particular' => 'Particular',
            'gross_amount' => 'Gross Amount',
            'tracking_number' => 'Tracking Number',
            'earmark_no' => 'Earmark No',
            'payroll_number' => 'Payroll Number',
            'transaction_date' => 'Transaction Date',
            'transaction_time' => 'Transaction Time',
            'created_at' => 'created_at',
            'type' => 'Type',
            'is_local' => 'is Local',
            'fk_book_id' => 'Book',
            'is_cancelled' => 'is Cancelled',

            'fk_certified_by' => 'Certified By',
            'fk_certified_budget_by' => 'Certified Budget By',
            'fk_certified_cash_by' => 'Certified Cash Available  By',
            'fk_approved_by' => 'Approved By',
        ];
    }

    /**
     * Gets query for [[Payee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayee()
    {
        return $this->hasOne(Payee::class, ['id' => 'payee_id']);
    }

    /**
     * Gets query for [[ResponsibilityCenter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsibilityCenter()
    {
        return $this->hasOne(ResponsibilityCenter::class, ['id' => 'responsibility_center_id']);
    }
    public function getProcessOrs()
    {
        return $this->hasMany(ProcessOrs::class, ['transaction_id' => 'id']);
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'fk_book_id']);
    }
    public function getCertifiedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_certified_by']);
    }
    public function getCertifiedBudgetBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_certified_budget_by']);
    }
    public function getCertifiedCashBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_certified_cash_by']);
    }
    public function getApprovedBy()
    {
        return $this->hasOne(Employee::class, ['employee_id' => 'fk_approved_by']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->tracking_number)) {
                    $this->tracking_number = $this->generateSerialNumber();
                }
            }
            return true;
        }
        return false;
    }

    private function generateSerialNumber()
    {

        $date  = DateTime::createFromFormat('m-d-Y', $this->transaction_date);
        $year = $date->format('Y');
        $lastNum =  self::find()
            ->addSelect([
                new Expression(" CAST(SUBSTRING_INDEX(`transaction`.tracking_number,'-',-1)AS UNSIGNED) as last_number ")
            ])
            ->join("LEFT JOIN", "responsibility_center", "`transaction`.responsibility_center_id = responsibility_center.id")
            ->andWhere(['responsibility_center.`id`' => $this->responsibility_center_id])
            ->andWhere("`transaction`.tracking_number LIKE :year", ['year' => "%$year%"])
            ->orderBy(" last_number DESC")
            ->limit(1)
            ->scalar();
        // echo $lastNum;
        // die();
        $num = !empty($lastNum) ? $lastNum + 1 : 1;

        return $this->responsibilityCenter->name . '-' . $year . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    public function getIarItemsA()
    {
        return $this->queryIars();
    }
    private function queryIars()
    {
        return Yii::$app->db->createCommand("SELECT 
                    transaction_iars.id as item_id,
                    transaction_iars.fk_iar_id,
                    iar.iar_number
                FROM transaction_iars 
                JOIN iar ON transaction_iars.fk_iar_id = iar.id
                WHERE transaction_iars.is_deleted = 0 
                AND transaction_iars.fk_transaction_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }

    private function deleteIarItems($iarsToRemove)
    {
        try {
            $generatedParams = MyHelper::generateParams($iarsToRemove);
            $generatedParamName = $generatedParams['paramNames'];
            $iarItemsIdsToRemove =  Yii::$app->db->createCommand("SELECT 
                    transaction_iars.id    
                    FROM  transaction_iars
                    WHERE transaction_iars.fk_transaction_id = :id
                    AND transaction_iars.is_deleted= 0
                    AND transaction_iars.fk_iar_id IN ($generatedParamName)", $generatedParams['params'])
                ->bindValue(':id', $this->id)
                ->queryAll();
            foreach ($iarItemsIdsToRemove as $item) {
                $item = TransactionIars::findOne($item);
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
    public function insertIarItems($iars = [])
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
                $item = new TransactionIars();
                $item->fk_transaction_id = $this->id;
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
    public function getPrItems()
    {
        return YIi::$app->db->createCommand("CALL GetTransactionPrItems(:id)")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getItems()
    {
        return YIi::$app->db->createCommand("CALL GetTransactionAllotmentItems(:id)")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function cancel()
    {
        try {
            $this->is_cancelled =  $this->is_cancelled ? 0 : 1;
            if ($this->is_cancelled === 1) {
                $qry = Yii::$app->db->createCommand("SELECT EXISTS (SELECT process_ors.transaction_id FROM process_ors
                WHERE 
                process_ors.is_cancelled = 0
                AND process_ors.transaction_id = 5000)")
                    ->bindValue(':id', $this->id)
                    ->queryScalar();
                if ($qry) {
                    throw new ErrorException("Cannot Cancel Transaction has ORS associated with");
                }
            }
            if (!$this->save(false)) {
                throw new ErrorException('Save Failed');
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
}
