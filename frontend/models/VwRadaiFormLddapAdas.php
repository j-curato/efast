<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vw_radai_form_lddap_adas".
 *
 * @property int $id
 * @property string|null $check_or_ada_no
 * @property string|null $issuance_date
 * @property string $lddap_no
 * @property string $mode_of_payment_name
 * @property string $acic_no
 */
class VwRadaiFormLddapAdas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vw_radai_form_lddap_adas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['lddap_no', 'mode_of_payment_name', 'acic_no'], 'required'],
            [['check_or_ada_no'], 'string', 'max' => 100],
            [['issuance_date'], 'string', 'max' => 50],
            [['lddap_no', 'mode_of_payment_name', 'acic_no'], 'string', 'max' => 255],
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
            'issuance_date' => 'Check Date',
            'lddap_no' => 'LDDAP No.',
            'mode_of_payment_name' => 'Mode Of Payment ',
            'acic_no' => 'ACIC No.',
        ];
    }
}
