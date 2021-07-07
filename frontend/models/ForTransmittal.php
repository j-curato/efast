<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "for_transmittal".
 *
 * @property int $id
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string $account_name
 * @property string|null $particular
 * @property float|null $total_dv
 */
class ForTransmittal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'for_transmittal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['particular'], 'string'],
            [['total_dv'], 'number'],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number','reporting_period'], 'string', 'max' => 40],
            [['account_name','dv_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check_or_ada_no' => 'Check Or Ada No',
            'ada_number' => 'Ada Number',
            'account_name' => 'Account Name',
            'particular' => 'Particular',
            'total_dv' => 'Total Dv',
            'reporting_period' => 'Reporting Period',
            'dv_number' => 'dv_number',
        ];
    }
}
