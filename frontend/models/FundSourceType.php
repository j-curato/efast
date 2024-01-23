<?php

namespace app\models;

use Yii;
use app\behaviors\HistoryLogsBehavior;

/**
 * This is the model class for table "fund_source_type".
 *
 * @property int $id
 * @property string|null $name
 */
class FundSourceType extends \yii\db\ActiveRecord
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
        return 'fund_source_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['division'], 'string', 'max' => 50],
            [['division', 'name'], 'required',],
            [[
                'id',
                'name',
                'division',
            ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'division' => 'Division',
        ];
    }
    public function getAdvancesEntries()
    {
        return $this->hasMany(AdvancesEntries::class, ['fund_source_type' => 'name']);
    }
}
