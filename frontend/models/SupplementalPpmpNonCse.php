<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "{{%supplemental_ppmp_non_cse}}".
 *
 * @property int $id
 * @property int|null $fk_supplemental_ppmp_id
 * @property int $fk_pr_stock_id
 * @property string $type
 * @property int|null $early_procurement
 * @property int $fk_mode_of_procurement_id
 * @property string $activity_name
 * @property int $fk_fund_source_id
 * @property string $proc_act_sched
 * @property int|null $is_deleted
 * @property string $deleted_at
 * @property string $created_at
 *
 * @property SupplementalPpmp $fkSupplementalPpmp
 */
class SupplementalPpmpNonCse extends \yii\db\ActiveRecord
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
        return '{{%supplemental_ppmp_non_cse}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fk_supplemental_ppmp_id',  'early_procurement',
                //  'fk_mode_of_procurement_id',
                'fk_fund_source_id', 'is_deleted'
            ], 'integer'],
            [[
                'type',
                //  'fk_mode_of_procurement_id',
                'activity_name', 'fk_fund_source_id',
                // 'proc_act_sched'
            ], 'required'],
            [['activity_name'], 'string'],
            [['deleted_at', 'created_at'], 'safe'],
            [[
                'type',
                // 'proc_act_sched'
            ], 'string', 'max' => 255],
            [['fk_supplemental_ppmp_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupplementalPpmp::class, 'targetAttribute' => ['fk_supplemental_ppmp_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_supplemental_ppmp_id' => 'Fk Supplemental Ppmp ID',
            'type' => 'Type',
            'early_procurement' => 'Early Procurement',
            // 'fk_mode_of_procurement_id' => 'Fk Mode Of Procurement ID',
            'activity_name' => 'Activity Name',
            'fk_fund_source_id' => 'Fk Fund Source ID',
            // 'proc_act_sched' => 'Proc Act Sched',
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
}
