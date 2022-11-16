<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ssf_category}}".
 *
 * @property int $id
 * @property string|null $ssf_number
 * @property string|null $fund_source
 * @property string|null $province
 * @property string|null $district
 * @property string|null $city
 * @property string|null $project_title
 * @property string|null $cooperator
 * @property string|null $cooperator_type
 * @property string|null $industry_cluster
 * @property int|null $count_of_ssf_establish
 * @property string|null $equipment_provided
 * @property float|null $amount_disbursed
 * @property string|null $date
 * @property string $created_at
 */
class SsfCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ssf_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_title', 'cooperator', 'equipment_provided'], 'string'],
            [['count_of_ssf_establish'], 'integer'],
            [['amount_disbursed'], 'number'],
            [['date', 'created_at'], 'safe'],
            [['ssf_number', 'fund_source', 'province', 'district', 'city', 'cooperator_type', 'industry_cluster'], 'string', 'max' => 255],
            [['ssf_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ssf_number' => 'Ssf Number',
            'fund_source' => 'Fund Source',
            'province' => 'Province',
            'district' => 'District',
            'city' => 'City',
            'project_title' => 'Project Title',
            'cooperator' => 'Cooperator',
            'cooperator_type' => 'Cooperator Type',
            'industry_cluster' => 'Industry Cluster',
            'count_of_ssf_establish' => 'Count Of Ssf Establish',
            'equipment_provided' => 'Equipment Provided',
            'amount_disbursed' => 'Amount Disbursed',
            'date' => 'Date',
            'created_at' => 'Created At',
        ];
    }
}
