<?php

namespace app\models;

use Yii;
use DateTime;
use ErrorException;
use yii\db\Expression;
use app\components\helpers\MyHelper;
use app\behaviors\HistoryLogsBehavior;

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

    public function behaviors()
    {
        return [
            'historyLogs' => [
                'class' => HistoryLogsBehavior::class,
            ],
        ];
    }
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
            [['date', 'created_at', 'cancelled_at'], 'safe'],
            [[
                'book_id',
                'requested_by_id',
                'approved_by_id',
                'is_cloud',
                'fk_supplemental_ppmp_noncse_id',
                'fk_supplemental_ppmp_cse_id',
                'budget_year',
                'fk_office_id',
                'fk_division_id',
                'fk_division_program_unit_id', 'is_fixed_expense',
                'is_cancelled',

            ], 'integer'],
            [['purpose'], 'string'],
            [['pr_number'], 'string', 'max' => 255],
            [['pr_number'], 'unique'],
            [['date'], 'validateDate'],
            [['budget_year'], 'validateBudgetYear'],
            [[
                'date',
                'book_id',
                'budget_year',
                'purpose',
                'requested_by_id',
                'approved_by_id',
                'fk_office_id',
                'fk_division_id',


            ], 'required'],
            [[
                'purpose',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],

        ];
    }

    public function validateDate($attribute, $params)
    {
        $targetDate = '2024-01-01';
        $selectedDate = $this->$attribute;

        if (
            $this->isNewRecord &&
            strtotime($selectedDate) < strtotime($targetDate)
            && strtotime(date('Y-m-d')) > strtotime(date('2023-11-28')) &&
            !Yii::$app->user->can('super-user')
            && !Yii::$app->user->can('create_2023_prs')

        ) {
            $this->addError($attribute, 'Please select a date on or after ' . $targetDate);
        }

        if (
            !$this->isNewRecord
            && !Yii::$app->user->can('super-user')
            && !Yii::$app->user->can('create_2023_prs')
        ) {
            $newDate = $this->getDirtyAttributes()['date'] ?? null;
            $oldDate = $this->getOldAttribute('date');

            if (
                !empty($newDate) &&
                strtotime(date($oldDate)) >= strtotime(date('2024-01-01')) &&
                strtotime(date($newDate)) < strtotime(date('2024-01-01'))

            ) {
                $this->addError($attribute, 'Please select a date on or after ' . $targetDate);
            }
        }
    }
    public function validateBudgetYear($attribute)
    {


        if (
            $this->isNewRecord
            && strtotime(date('Y-m-d')) > strtotime(date('2023-11-28'))
            && intval($this->$attribute) < 2024 &&
            !Yii::$app->user->can('super-user')
            && !Yii::$app->user->can('create_2023_prs')
        ) {
            $this->addError($attribute, 'Please select a Budget Year on or after 2024');
        }

        if (
            !$this->isNewRecord &&   !Yii::$app->user->can('super-user')
            && !Yii::$app->user->can('create_2023_prs')
        ) {
            $newBudgetYear = $this->getDirtyAttributes()['budget_year'] ?? null;
            $oldDate = $this->getOldAttribute('budget_year');
            if (
                !empty($newBudgetYear) &&
                intval($oldDate) >= 2024 &&
                intval($newBudgetYear) < 2024

            ) {
                $this->addError($attribute, 'Please select a Year on or after ' . 2024);
            }
        }
    }

    public function getTxnLinks()
    {
        return Yii::$app->db->createCommand("SELECT 
        transaction_pr_items.fk_pr_allotment_id,
        transaction_pr_items.fk_transaction_id as txn_id,
        `transaction`.tracking_number as txn_num
        FROM
        pr_purchase_request_allotments
        JOIN  transaction_pr_items ON pr_purchase_request_allotments.id = transaction_pr_items.fk_pr_allotment_id
        JOIN `transaction` ON transaction_pr_items.fk_transaction_id = `transaction`.id 
        WHERE 
        pr_purchase_request_allotments.is_deleted = 0
        AND transaction_pr_items.is_deleted = 0
        AND pr_purchase_request_allotments.fk_purchase_request_id = :id
        GROUP BY
        transaction_pr_items.fk_pr_allotment_id,
        transaction_pr_items.fk_transaction_id,
        `transaction`.tracking_number")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }

    public function getRfqLinks()
    {
        return Yii::$app->db->createCommand("SELECT id, rfq_number,pr_rfq.is_cancelled FROM pr_rfq WHERE pr_purchase_request_id = :id")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function hasRfq()
    {
        $qry =  Yii::$app->db->createCommand("SELECT EXISTS (SELECT id FROM pr_rfq WHERE pr_purchase_request_id = :id AND pr_rfq.is_cancelled = 0)")
            ->bindValue(':id', $this->id)
            ->queryScalar();

        return intval($qry) === 1 ? true : false;
    }
    // public function getPrItems()
    // {
    //     return Yii::$app->db->createCommand("CALL GetPrItems(:id)")
    //         ->bindValue(':id', $this->id)
    //         ->queryAll();
    // }
    public function getPrItems()
    {
        // return Yii::$app->db->createCommand("CALL GetPrItems(:id)")
        //     ->bindValue(':id', $this->id)
        //     ->queryAll();
        return PrPurchaseRequestItem::find()
            ->addSelect([
                new Expression("(CASE 
                        WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN 'cse_item_id'
                        WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN 'non_cse_item_id'
                        END
                        ) as cse_type"),
                new Expression("(CASE 
                        WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN pr_purchase_request_item.fk_ppmp_cse_item_id 
                        WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN  pr_purchase_request_item.fk_ppmp_non_cse_item_id 
                        END
                        ) as  ppmp_item_id"),

                new Expression("(CASE 
                        WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN pr_stock.stock_title
                        WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN  supplemental_ppmp_non_cse.activity_name 
                        END
                        ) as project_name"),
                new Expression("CAST(pr_purchase_request_item.id AS CHAR(50))  as item_id"),
                "pr_stock.id as stock_id",
                "pr_stock.stock_title",
                "unit_of_measure.id as unit_of_measure_id",
                "unit_of_measure.unit_of_measure",
                "pr_purchase_request_item.unit_cost",
                "pr_purchase_request_item.quantity",
                "pr_stock.bac_code",
                new Expression("pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity as total_cost"),
                "pr_purchase_request_item.specification",
                // "supplemental_ppmp.is_supplemental",
                // "supplemental_ppmp.id as ppmp_id",
                new Expression("(CASE 
                    WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN cse_ppmp.is_supplemental
                    WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN  non_cse_ppmp.is_supplemental 
                    END
                    ) as  is_supplemental"),

                new Expression("(CASE 
                    WHEN pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL THEN cse_ppmp.id
                    WHEN     pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL THEN  non_cse_ppmp.id 
                    END
                    ) as  ppmp_id")

            ])
            ->join("LEFT JOIN", "unit_of_measure", " pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id")
            ->join("LEFT JOIN", "supplemental_ppmp_non_cse_items", " pr_purchase_request_item.fk_ppmp_non_cse_item_id = supplemental_ppmp_non_cse_items.id")
            ->join("LEFT JOIN", "supplemental_ppmp_cse", " pr_purchase_request_item.fk_ppmp_cse_item_id = supplemental_ppmp_cse.id")
            ->join("LEFT JOIN", "supplemental_ppmp_non_cse", " supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id = supplemental_ppmp_non_cse.id")
            // ->join("LEFT JOIN", "supplemental_ppmp", " supplemental_ppmp_cse.fk_supplemental_ppmp_id = supplemental_ppmp_cse.id or supplemental_ppmp_non_cse . fk_supplemental_ppmp_id = supplemental_ppmp . id")
            ->join("LEFT JOIN", "supplemental_ppmp  as cse_ppmp", "supplemental_ppmp_cse.fk_supplemental_ppmp_id = cse_ppmp.id")
            ->join("LEFT JOIN", "supplemental_ppmp  as non_cse_ppmp", "supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = non_cse_ppmp.id")

            ->join("LEFT JOIN", "pr_stock", " pr_purchase_request_item.pr_stock_id = pr_stock.id")
            ->andWhere([
                "pr_purchase_request_item.pr_purchase_request_id" => $this->id
            ])
            ->andWhere(["pr_purchase_request_item.is_deleted" => false])
            ->asArray()
            ->all();
        // ->createCommand()->getRawSql();
    }
    public function getPrAllotments()
    {
        return Yii::$app->db->createCommand("CALL GetPrAllotments(:id)")
            ->bindValue(':id', $this->id)
            ->queryAll();
    }
    public function getPrDetails()
    {
        return Yii::$app->db->createCommand("SELECT 
        pr_purchase_request.pr_number,
        pr_purchase_request.date as date_propose,
        books.`name` as book_name,
        pr_purchase_request.purpose,
        requested_by.employee_name as requested_by,
        approved_by.employee_name as approved_by,
        pr_project_procurement.title as project_title,
        pr_project_procurement.amount as project_amount,
        office.office_name,
        divisions.division,
        division_program_unit.name as unit,
        prepared_by.employee_name as prepared_by
        FROM `pr_purchase_request`
        LEFT JOIN employee_search_view  as requested_by ON  pr_purchase_request.requested_by_id = requested_by.employee_id
        LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
        LEFT JOIN books ON pr_purchase_request.book_id = books.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        LEFT JOIN employee_search_view as prepared_by ON pr_project_procurement.employee_id = prepared_by.employee_id
        LEFT JOIN office ON pr_purchase_request.fk_office_id = office.id
        LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
        LEFT JOIN division_program_unit ON pr_purchase_request.fk_division_program_unit_id = division_program_unit.id
        WHERE 
        pr_purchase_request.id = :id")
            ->bindValue(':id', $this->id)
            ->queryOne();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_number' => 'Pr Number',
            'date' => 'Date ',
            'book_id' => 'Book ',
            'pr_project_procurement_id' => ' Project Procurement ',
            'purpose' => 'Purpose',
            'requested_by_id' => 'Requested By ',
            'approved_by_id' => 'Approved By ',
            'created_at' => 'Created At',
            'is_cloud' => 'is_cloud',
            'fk_supplemental_ppmp_noncse_id' => 'fk_supplemental_ppmp_noncse_id',
            'fk_supplemental_ppmp_cse_id' => 'fk_supplemental_ppmp_cse_id',
            'budget_year' => 'Budget Year',
            'fk_office_id' => 'Office',
            'fk_division_id' => 'Division',
            'fk_division_program_unit_id' => 'Division/Program/Unit',
            'is_fixed_expense' => 'Fixed Expense',
            'cancelled_at' => 'Cancelled At',
            'is_cancelled' => 'is Cancelled',
            'fk_created_by' => 'Created BY'
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
    public function getOffice()
    {
        return $this->hasOne(Office::class, ['id' => 'fk_office_id']);
    }
    public function getDivisionDetails()
    {
        return $this->hasOne(Divisions::class, ['id' => 'fk_division_id']);
    }
    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = MyHelper::getUuid();
                }
                if (empty($this->pr_number)) {
                    $this->pr_number = $this->generatePrNumber();
                }
                if (empty($this->fk_created_by)) {
                    $this->fk_created_by = Yii::$app->user->identity->id;
                }
            }
            if (!$this->isNewRecord) {
                $newDate = $this->getDirtyAttributes()['date'] ?? null;
                $oldDate = $this->getOldAttribute('date');
                if (!empty($newDate) && $newDate !== $oldDate) {
                    $this->pr_number = $this->updatePrNumber();
                }
            }
            return true;
        }
        return false;
    }
    private function updatePrNumber()
    {
        $date = DateTime::createFromFormat('Y-m-d', $this->date);
        $pr_number =  explode('-', $this->pr_number);
        // $pr_number[0] = strtoupper($this->office->office_name);
        // $pr_number[1] = strtoupper($this->divisionDetails->division);
        $pr_number[2] = $date->format('Y');
        $pr_number[3] = $date->format('m');
        $pr_number[4] = $date->format('d');
        return implode('-', $pr_number);
    }
    private function generatePrNumber()
    {

        $date = DateTime::createFromFormat('Y-m-d', $this->date);
        $last_num_qry = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_number,'-',-1) AS UNSIGNED) as last_number
            FROM pr_purchase_request
            WHERE pr_purchase_request.fk_office_id = :office_id
            AND pr_purchase_request.fk_division_id = :division_id
            AND pr_purchase_request.is_final = 0
            AND pr_purchase_request.date LIKE :yr
            ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->bindValue(':division_id', $this->fk_division_id)
            ->bindValue(':yr', $date->format('Y') . '%')
            ->queryScalar();
        $num  = !empty($last_num_qry) ? intval($last_num_qry) + 1  : 1;
        return  strtoupper($this->office->office_name) . '-' . strtoupper($this->divisionDetails->division) . '-' . $date->format('Y-m-d') . '-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    private function checkHasRfq()
    {
        return Yii::$app->db->createCommand("SELECT 
                GROUP_CONCAT(pr_rfq.rfq_number) as rfq_nums
                FROM pr_rfq
                WHERE  pr_rfq.is_cancelled = 0
                AND  pr_rfq.pr_purchase_request_id = :id
                GROUP BY 
                pr_rfq.pr_purchase_request_id")
            ->bindValue(':id', $this->id)
            ->queryScalar();
    }
    public function cancel()
    {
        try {
            $this->is_cancelled =  $this->is_cancelled ? 0 : 1;
            $this->cancelled_at = date('Y-m-d H:i:s');
            if ($this->is_cancelled === 1) {

                $hasRfq = $this->checkHasRfq();
                if (!empty($hasRfq)) {
                    throw new ErrorException("Unable to cancel PR,RFQ No./s $hasRfq is/are not Cancelled.");
                }
            }
            if (!$this->save(false)) {
                throw new ErrorException('Cancel Save Failed');
            }
            return true;
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
    }
}
