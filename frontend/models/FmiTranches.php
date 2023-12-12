<?php

namespace app\models;

use app\behaviors\GenerateIdBehavior;
use app\behaviors\HistoryLogsBehavior;
use Yii;

/**
 * This is the model class for table "tbl_fmi_tranches".
 *
 * @property int $id
 * @property string|null $tranche_number
 */
class FmiTranches extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            'generateId' => [
                'class' => GenerateIdBehavior::class,
            ],
            'history' => [
                'class' => HistoryLogsBehavior::class
            ]
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_fmi_tranches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tranche_number'], 'required'],
            [['id'], 'integer'],
            [['tranche_number'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tranche_number' => 'Tranche Number',
            'created_at' => 'Created At',
        ];
    }
    public static function getAllTranches()
    {

        return self::find()
            ->addSelect([
                'id',
                'tranche_number'
            ])
            ->asArray()
            ->all();
    }
}
