<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%payee}}".
 *
 * @property int $id
 * @property string $account_name
 * @property string|null $registered_name
 * @property string|null $contact_person
 * @property string|null $registered_address
 * @property string|null $contact
 * @property string|null $remark
 * @property string|null $tin_number
 * @property int|null $isEnable
 *
 * @property Liquidation[] $liquidations
 * @property TrackingSheet[] $trackingSheets
 * @property Transaction[] $transactions
 */
class Payee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isEnable'], 'integer'],
            [['account_name', 'registered_name', 'contact_person', 'registered_address', 'remark'], 'string', 'max' => 255],
            [['contact'], 'string', 'max' => 20],
            [['tin_number'], 'string', 'max' => 30],
            [[
                'id',
                'account_name',
                'registered_name',
                'contact_person',
                'registered_address',
                'contact',
                'remark',
                'tin_number',
                'isEnable',
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
            'account_name' => 'Account Name',
            'registered_name' => 'Registered Name',
            'contact_person' => 'Contact Person',
            'registered_address' => 'Registered Address',
            'contact' => 'Contact',
            'remark' => 'Remark',
            'tin_number' => 'Tin Number',
            'isEnable' => 'Is Enable',
        ];
    }

    /**
     * Gets query for [[Liquidations]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LiquidationQuery
     */
    public function getLiquidations()
    {
        return $this->hasMany(Liquidation::class, ['payee_id' => 'id']);
    }

    /**
     * Gets query for [[TrackingSheets]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TrackingSheetQuery
     */
    public function getTrackingSheets()
    {
        return $this->hasMany(TrackingSheet::class, ['payee_id' => 'id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TransactionQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['payee_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\PayeeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\PayeeQuery(get_called_class());
    }
}
