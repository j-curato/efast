<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rpcppe".
 *
 * @property string $rpcppe_number
 * @property string|null $reporting_period
 * @property int|null $book_id
 * @property string|null $certified_by
 * @property string|null $approved_by
 * @property string|null $verified_by
 * @property string|null $verified_pos
 */
class Rpcppe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rpcppe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rpcppe_number'], 'required'],
            [['book_id'], 'integer'],
            [['rpcppe_number', 'certified_by', 'approved_by', 'verified_by', 'verified_pos'], 'string', 'max' => 255],
            [['reporting_period'], 'string', 'max' => 50],
            [['rpcppe_number'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rpcppe_number' => 'Rpcppe Number',
            'reporting_period' => 'Reporting Period',
            'book_id' => 'Book ID',
            'certified_by' => 'Certified By',
            'approved_by' => 'Approved By',
            'verified_by' => 'Verified By',
            'verified_pos' => 'Verified Pos',
        ];
    }
}
