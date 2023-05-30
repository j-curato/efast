<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ppmp_non_cse_items}}".
 *
 * @property int $id
 * @property string $project_name
 * @property string $target_month
 * @property int $fk_fund_source_id
 * @property int $fk_pap_code_id
 * @property int|null $fk_ppmp_non_cse_id
 * @property string $description
 * @property int $fk_responsibility_center_id
 * @property string $created_at
 *
 * @property PpmpNonCseItemCategories[] $ppmpNonCseItemCategories
 * @property PpmpNonCse $fkPpmpNonCse
 */
class PpmpNonCseItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ppmp_non_cse_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_name', 'target_month', 'fk_pap_code_id', 'fk_responsibility_center_id'], 'required'],
            [['id', 'fk_pap_code_id', 'fk_ppmp_non_cse_id', 'fk_responsibility_center_id', 'is_deleted'], 'integer'],
            [['project_name', 'description'], 'string'],
            [['target_month', 'created_at'], 'safe'],
            [['id'], 'unique'],
            [['fk_ppmp_non_cse_id'], 'exist', 'skipOnError' => true, 'targetClass' => PpmpNonCse::class, 'targetAttribute' => ['fk_ppmp_non_cse_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}fk_fund_source_id
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_name' => 'Project Name',
            'target_month' => 'Target Month',
            'fk_pap_code_id' => ' Pap Code ',
            'fk_ppmp_non_cse_id' => ' Ppmp Non Cse ',
            'description' => 'Description',
            'fk_responsibility_center_id' => ' End User',
            'created_at' => 'Created At',
            'is_deleted' => 'is Deleted',
        ];
    }

    /**
     * Gets query for [[PpmpNonCseItemCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpNonCseItemCategories()
    {
        return $this->hasMany(PpmpNonCseItemCategories::class, ['ppmp_non_cse_item_id' => 'id']);
    }

    /**
     * Gets query for [[FkPpmpNonCse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPpmpNonCse()
    {
        return $this->hasOne(PpmpNonCse::class, ['id' => 'fk_ppmp_non_cse_id']);
    }
}
