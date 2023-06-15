<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_dvs_in_bank".
 *
 * @property int $id
 * @property string|null $check_or_ada_no
 * @property string|null $ada_number
 * @property string|null $issuance_date
 * @property string|null $dv_number
 * @property float|null $grossAmt
 * @property string|null $payee
 * @property string|null $orsNums
 * @property string|null $particular
 * @property string $acic_num
 */
class VwDvsInBank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_dvs_in_bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['grossAmt'], 'number'],
            [['orsNums', 'particular'], 'string'],
            [['acic_num'], 'required'],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['ada_number'], 'string', 'max' => 40],
            [['issuance_date'], 'string', 'max' => 50],
            [['dv_number', 'payee', 'acic_num'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'check_or_ada_no' => 'Check No.',
            'ada_number' => 'ADA No.',
            'issuance_date' => 'Issuance Date',
            'dv_number' => 'DV No.',
            'grossAmt' => 'Gross Amount',
            'payee' => 'Payee',
            'orsNums' => 'ORS No.',
            'particular' => 'Particular',
            'acic_num' => 'ACIC No.',
        ];
    }
}
