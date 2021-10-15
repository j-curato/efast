<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "liquidation_links".
 *
 * @property int $id
 * @property int|null $liquidation_id
 * @property string|null $link
 */
class LiquidationLinks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'liquidation_links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['liquidation_id'], 'integer'],
            [['link'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'liquidation_id' => 'Liquidation ID',
            'link' => 'Link',
        ];
    }
}
