<?php

namespace app\models;


use Yii;
use yii\db\Expression;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "cash_disbursement".
 *
 * @property int $id
 * @property int|null $book_id
 * @property int|null $dv_aucs_entries_id
 * @property string|null $reporting_period
 * @property string|null $mode_of_payment
 * @property string|null $check_or_ada_no
 * @property string|null $is_cancelled
 * @property string|null $issuance_date
 *
 * @property Books $book
 * @property DvAucs $dvAucs
 */
class CashDisbursement extends \yii\db\ActiveRecord
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
        return 'cash_disbursement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [


            [[
                'book_id',
                'reporting_period',
                'issuance_date',
                'check_or_ada_no',
                'begin_time',
                'out_time',
                'fk_ro_check_range_id',
                'fk_mode_of_payment_id'
            ], 'required'],
            [[
                'book_id',
                'dv_aucs_id',
                'is_cancelled',
                'fk_ro_check_range_id',
                'fk_mode_of_payment_id',
                'is_deleted'
            ], 'integer'],
            [[
                'reporting_period',
                'mode_of_payment',
                'issuance_date',
                'begin_time',
                'out_time',
            ], 'string', 'max' => 50],
            [['ada_number'], 'string', 'max' => 40],
            [['check_or_ada_no'], 'safe',],
            [['is_cancelled'], 'default', 'value' => 0],
            [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Books::class, 'targetAttribute' => ['book_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'book_id' => 'Book ',
            'dv_aucs_id' => 'Dv Aucs ',
            'reporting_period' => 'Reporting Period',
            'mode_of_payment' => 'Mode Of Payment',
            'check_or_ada_no' => 'Check Number',
            'is_cancelled' => 'Is Cancelled',
            'issuance_date' => 'Issuance Date',
            'ada_number' => 'Ada Number',
            'begin_time' => 'Begin Time',
            'out_time' => 'Out Time',
            'fk_ro_check_range_id' => 'Check Range',
            'fk_mode_of_payment_id' => 'Mode of Payment',

            'is_deleted' => 'is Deleted'
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }

    /**
     * Gets query for [[DvAucs]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getDvAucs()
    {
        return $this->hasOne(DvAucs::class, ['id' => 'dv_aucs_id']);
    }
    public function getJevPreparation()
    {
        return $this->hasOne(JevPreparation::class, ['cash_disbursement_id' => 'id']);
    }
    public function getTransmittal()
    {
        return $this->hasOne(TransmittalEntries::class, ['cash_disbursement_id' => 'id']);
    }
    public function getModeOfPayment()
    {
        return $this->hasOne(ModeOfPayments::class, ['id' => 'fk_mode_of_payment_id']);
    }
    public function getSliie()
    {
        return $this->hasOne(Sliies::class, ['fk_cash_disbursement_id' => 'id']);
    }
    public function getLddapAda()
    {
        return $this->hasOne(LddapAdas::class, ['fk_cash_disbursement_id' => 'id']);
    }
    public function getAcicItem()
    {
        return $this->hasOne(AcicsCashItems::class, ['fk_cash_disbursement_id' => 'id'])
            ->andWhere(['is_deleted' => false]);
    }
    public function getCashDisbursementItems()
    {
        return $this->hasMany(CashDisbursementItems::class, ['fk_cash_disbursement_id' => 'id'])
            ->andWhere(['is_deleted' => false]);
    }

    public static function searchCheckNumber($text = null, $page = 1,  $id = null)
    {
        $limit = 5;
        $offset = ($page - 1) * $limit;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => self::findOne($id)->serial_number];
        } else if (!is_null($text)) {
            $query = self::find()
                ->addSelect([
                    new Expression("CAST(id AS CHAR(50)) as id"),
                    new Expression("check_or_ada_no as text"),
                ])
                ->where(['like', 'cash_disbursement.check_or_ada_no', $text])
                ->offset($offset)
                ->limit($limit);
            $data =  $query->asArray()->all();
            $out['results'] = array_values($data);
            $out['pagination'] = ['more' => !empty($data) ? true : false];
        }
        return $out;
    }
    public function getDetails()
    {

        return self::find()
            ->addSelect([
                new Expression(" cash_disbursement.check_or_ada_no as check_number"),
                "cash_disbursement.issuance_date",
                new Expression("books.`name` as book_name")
            ])
            ->joinWith('book')
            ->andWhere(['cash_disbursement.id' => $this->id])
            ->asArray()
            ->one();
    }
    public function getItems()
    {
        $subQryDvEntries = DvAucsEntries::find()
            ->addSelect([
                "dv_aucs_entries.dv_aucs_id",
                new Expression("SUM(dv_aucs_entries.amount_disbursed)  as total_disbursed")
            ])
            ->andWhere(['is_deleted' => false])
            ->groupBy(" dv_aucs_entries.dv_aucs_id");

        return CashDisbursementItems::find()
            ->addSelect([
                "dv_aucs.dv_number",
                new Expression("payee.registered_name as payee_name"),
                "dv_aucs.particular",
                "dv_entries.total_disbursed"
            ])
            ->andWhere(['fk_cash_disbursement_id' => $this->id])
            ->join("LEFT JOIN", "dv_aucs", "cash_disbursement_items.fk_dv_aucs_id = dv_aucs.id")
            ->join("LEFT JOIN", "payee", "dv_aucs.payee_id = payee.id")
            ->leftJoin(
                ['dv_entries' => $subQryDvEntries],
                'dv_aucs.id = dv_entries.dv_aucs_id'
            )
            ->asArray()
            ->all();
    }
    public function getAcicNum()
    {
        return self::find()
            ->addSelect(["acics.serial_number"])
            ->join("JOIN", "acics_cash_items", "cash_disbursement.id = acics_cash_items.fk_cash_disbursement_id")
            ->join("JOIN", "acics", "acics_cash_items.fk_acic_id = acics.id")
            ->andWhere(["acics_cash_items.is_deleted" => false])
            ->andWhere(["cash_disbursement.id" => $this->id])
            ->scalar();
    }
}
