<?php

namespace app\models;

use Yii;

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


        return Yii::$app->db->createCommand("CALL CalculatePpmpNonCseBalance(:prItemId,:id,:amount,:qty)")
            ->bindValue(':prItemId', $prItemId)
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
}
