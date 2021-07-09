<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advances_entries_for_liquidation".
 *
 * @property int $id
 * @property string|null $province
 * @property string|null $fund_source
 * @property float|null $amount
 * @property float|null $total_liquidation
 * @property string|null $particular
 */
class AdvancesEntriesForLiquidation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advances_entries_for_liquidation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['fund_source', 'particular'], 'string'],
            [['amount', 'total_liquidation'], 'number'],
            [['province'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province' => 'Province',
            'fund_source' => 'Fund Source',
            'amount' => 'Amount',
            'total_liquidation' => 'Total Liquidation',
            'particular' => 'Particular',
        ];
    }
}
