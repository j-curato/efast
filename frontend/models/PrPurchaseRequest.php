<?php

namespace app\models;

use DateTime;
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
            [[
                'pr_number',
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


    private function generatePrNumber($is_backdate = false)
    {
        $date = DateTime::createFromFormat('Y-m-d', $this->date);
        $last_num_qry = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(pr_number,'-',-1) AS UNSIGNED) as last_number
            FROM pr_purchase_request
            WHERE pr_purchase_request.fk_office_id = :office_id
            AND pr_purchase_request.fk_division_id = :division_id
            AND pr_purchase_request.is_final = 0
            AND pr_purchase_request.date <= :dte
            AND pr_purchase_request.date LIKE :yr
            ORDER BY last_number DESC LIMIT 1")
            ->bindValue(':office_id', $this->fk_office_id)
            ->bindValue(':division_id', $this->fk_division_id)
            ->bindValue(':dte', $this->date)
            ->bindValue(':yr', $date->format('Y') . '%')
            ->queryScalar();
        if ($is_backdate) {
            $last_ltr = YIi::$app->db->createCommand("SELECT 
                 CASE
                    WHEN SUBSTRING(pr_number, -1) REGEXP '[0-9]' THEN NULL
                    ELSE CHAR(ASCII(SUBSTRING(pr_number, -1)) + 1)
                  END as lst_ltr
               FROM pr_purchase_request
               WHERE pr_purchase_request.fk_office_id = :office_id
               AND pr_purchase_request.fk_division_id = :division_id
               AND pr_purchase_request.is_final = 0
               AND pr_purchase_request.date = :dte
               AND pr_purchase_request.date LIKE :yr
                 ORDER BY lst_ltr DESC")
                ->bindValue(':office_id', $this->fk_office_id)
                ->bindValue(':division_id', $this->fk_division_id)
                ->bindValue(':dte',  $this->date)
                ->bindValue(':yr', $date->format('Y') . '%')
                ->queryScalar();
        }

        $num  = 1;
        if (!empty($last_num_qry)) {
            $num = intval($last_num_qry);
            !$is_backdate ? $num++ : '';
        }
        $zro = '';
        for ($i =  strlen($num); $i < 4; $i++) {
            $zro .= 0;
        }
        $lst_ltr = '';
        if ($is_backdate) {
            $lst_ltr = empty($last_ltr) ? 'A' : '';
        }

        return  strtoupper($this->office->office_name) . '-' . strtoupper($this->divisionDetails->division) . '-' . $date->format('Y-m-d') . '-' . $zro . $num . $lst_ltr;
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
        $qry =  Yii::$app->db->createCommand("SELECT EXISTS (SELECT id FROM pr_rfq WHERE pr_purchase_request_id = :id)")
            ->bindValue(':id', $this->id)
            ->queryScalar();

        return intval($qry) === 1 ? true : false;
    }
    public function getPrItems()
    {
        return Yii::$app->db->createCommand("CALL GetPrItems(:id)")
            ->bindValue(':id', $this->id)
            ->queryAll();
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
            'date' => 'Date Propose',
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
}
