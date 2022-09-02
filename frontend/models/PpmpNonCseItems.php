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
            [['id', 'project_name', 'target_month', 'fk_fund_source_id', 'fk_pap_code_id', 'description', 'fk_responsibility_center_id'], 'required'],
            [['id', 'fk_fund_source_id', 'fk_pap_code_id', 'fk_ppmp_non_cse_id', 'fk_responsibility_center_id'], 'integer'],
            [['project_name', 'description'], 'string'],
            [['target_month', 'created_at'], 'safe'],
            [['id'], 'unique'],
            [['fk_ppmp_non_cse_id'], 'exist', 'skipOnError' => true, 'targetClass' => PpmpNonCse::className(), 'targetAttribute' => ['fk_ppmp_non_cse_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_name' => 'Project Name',
            'target_month' => 'Target Month',
            'fk_fund_source_id' => 'Fk Fund Of Source ID',
            'fk_pap_code_id' => 'Fk Pap Code ID',
            'fk_ppmp_non_cse_id' => 'Fk Ppmp Non Cse ID',
            'description' => 'Description',
            'fk_responsibility_center_id' => 'Fk End User',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PpmpNonCseItemCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPpmpNonCseItemCategories()
    {
        return $this->hasMany(PpmpNonCseItemCategories::className(), ['ppmp_non_cse_item_id' => 'id']);
    }

    /**
     * Gets query for [[FkPpmpNonCse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFkPpmpNonCse()
    {
        return $this->hasOne(PpmpNonCse::className(), ['id' => 'fk_ppmp_non_cse_id']);
    }
}
