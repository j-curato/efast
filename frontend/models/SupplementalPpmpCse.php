<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "{{%supplemental_ppmp_cse}}".
 *
 * @property int $id
 * @property int|null $fk_supplemental_ppmp_id
 * @property int|null $fk_pr_stock_id
 * @property float|null $amount
 * @property int|null $jan_qty
 * @property int|null $feb_qty
 * @property int|null $mar_qty
 * @property int|null $apr_qty
 * @property int|null $may_qty
 * @property int|null $jun_qty
 * @property int|null $jul_qty
 * @property int|null $aug_qty
 * @property int|null $sep_qty
 * @property int|null $oct_qty
 * @property int|null $nov_qty
 * @property int|null $dec_qty
 * @property int|null $is_deleted
 * @property string $deleted_at
 * @property string $created_at
 *
 * @property SupplementalPpmp $fkSupplementalPpmp
 */
class SupplementalPpmpCse extends \yii\db\ActiveRecord
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
        return '{{%supplemental_ppmp_cse}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fk_supplemental_ppmp_id', 'fk_unit_of_measure_id', 'fk_pr_stock_id', 'jan_qty', 'feb_qty', 'mar_qty', 'apr_qty', 'may_qty', 'jun_qty', 'jul_qty', 'aug_qty', 'sep_qty', 'oct_qty', 'nov_qty', 'dec_qty', 'is_deleted'], 'integer'],
            [['amount'], 'number'],
            [['deleted_at', 'created_at'], 'safe'],
            [['fk_supplemental_ppmp_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupplementalPpmp::class, 'targetAttribute' => ['fk_supplemental_ppmp_id' => 'id']],
        ];
    }
    public function calculateBalance($prItemId = '', $amount = 0, $qty = 0)
    {


        return Yii::$app->db->createCommand("CALL CalculatePpmpCseBalance(:prItemId,:id,:amount,:qty)")
            ->bindValue(':prItemId', !empty($prItemId) ? $prItemId : null)
            ->bindValue(':id', $this->id)
            ->bindValue(':amount', $amount)
            ->bindValue(':qty', $qty)
            ->queryOne();
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_supplemental_ppmp_id' => ' Supplemental Ppmp ID',
            'fk_pr_stock_id' => ' Pr Stock ID',
            'fk_unit_of_measure_id' => 'Unit of Measure',
            'amount' => 'Amount',
            'jan_qty' => 'Jan Qty',
            'feb_qty' => 'Feb Qty',
            'mar_qty' => 'Mar Qty',
            'apr_qty' => 'Apr Qty',
            'may_qty' => 'May Qty',
            'jun_qty' => 'Jun Qty',
            'jul_qty' => 'Jul Qty',
            'aug_qty' => 'Aug Qty',
            'sep_qty' => 'Sep Qty',
            'oct_qty' => 'Oct Qty',
            'nov_qty' => 'Nov Qty',
            'dec_qty' => 'Dec Qty',
            'is_deleted' => 'Is Deleted',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[FkSupplementalPpmp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkSupplementalPpmp()
    {
        return $this->hasOne(SupplementalPpmp::class, ['id' => 'fk_supplemental_ppmp_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord) {
                if (empty($this->id)) {
                    $this->id = Yii::$app->db->createCommand("SELECT UUID_SHORT() % 9223372036854775807")->queryScalar();
                }
            }
            return true;
        }
        return false;
    }
    public function getPurchaseRequestsDataA()
    {
        return self::find()
            ->addSelect([
                "pr_purchase_request.id",
                "pr_purchase_request.pr_number",
                "pr_purchase_request_item.quantity",
                "pr_purchase_request_item.specification",
                "pr_purchase_request_item.unit_cost"
            ])

            ->join("JOIN", "pr_purchase_request_item",   "supplemental_ppmp_cse.id = pr_purchase_request_item.fk_ppmp_cse_item_id")
            ->join("JOIN", "pr_purchase_request",  "pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id")
            ->andWhere([
                "pr_purchase_request_item.is_deleted" => false
            ])
            ->andWhere([
                "supplemental_ppmp_cse.id" => $this->id
            ])
            ->andWhere([
                "pr_purchase_request.is_cancelled" => false
            ])
            ->asArray()
            ->all();
    }
}
