<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%advances}}".
 *
 * @property int $id
 * @property string|null $province
 * @property string|null $report_type
 * @property string|null $particular
 * @property string|null $nft_number
 * @property string $created_at
 * @property string|null $reporting_period
 * @property int|null $book_id
 * @property string|null $advances_type
 *
 * @property AdvancesEntries[] $advancesEntries
 */
class Advances extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%advances}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at','bank_account_id'], 'safe'],
            [['book_id','dv_aucs_id'], 'integer'],
            [['province', 'report_type'], 'string', 'max' => 50],
            [['particular'], 'string', 'max' => 500],
            [['nft_number', 'advances_type'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 20],
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
            'report_type' => 'Report Type',
            'particular' => 'Particular',
            'nft_number' => 'Nft Number',
            'created_at' => 'Created At',
            'reporting_period' => 'Reporting Period',
            'book_id' => 'Book ID',
            'advances_type' => 'Advances Type',
            'bank_account_id' => 'Bank Account',
            'dv_aucs_id' => 'DV Aucs',
        ];
    }

    /**
     * Gets query for [[AdvancesEntries]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\AdvancesEntriesQuery
     */
    public function getAdvancesEntries()
    {
        // return $this->hasMany(AdvancesEntries::className(), ['advances_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\AdvancesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AdvancesQuery(get_called_class());
    }
}
