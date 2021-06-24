<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "conso_detailed_dv".
 *
 * @property string $mfo_code
 * @property string $mfo_name
 * @property string $mfo_description
 * @property float|null $total_allotment_recieve
 * @property float|null $conso_total_obligation
 * @property float|null $conso_total_ewt
 * @property float|null $conso_total_dv
 * @property float|null $conso_total_vat
 */
class ConsoDetailedDv extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conso_detailed_dv';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mfo_code', 'mfo_name', 'mfo_description'], 'required'],
            [['total_allotment_recieve', 'conso_total_obligation', 'conso_total_ewt', 'conso_total_dv', 'conso_total_vat'], 'number'],
            [['mfo_code', 'mfo_name', 'mfo_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mfo_code' => 'Mfo Code',
            'mfo_name' => 'Mfo Name',
            'mfo_description' => 'Mfo Description',
            'total_allotment_recieve' => 'Total Allotment Recieve',
            'conso_total_obligation' => 'Conso Total Obligation',
            'conso_total_ewt' => 'Conso Total Ewt',
            'conso_total_dv' => 'Conso Total Dv',
            'conso_total_vat' => 'Conso Total Vat',
        ];
    }
}
