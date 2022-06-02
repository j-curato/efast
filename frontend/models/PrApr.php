<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pr_apr".
 *
 * @property int $id
 * @property int|null $pr_purchase_request_id
 * @property string|null $apr_number
 * @property string $created_at
 */
class PrApr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pr_apr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pr_purchase_request_id'], 'integer'],
            [['created_at'], 'safe'],
            [['apr_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pr_purchase_request_id' => 'Pr Purchase Request ID',
            'apr_number' => 'Apr Number',
            'created_at' => 'Created At',
        ];
    }
}
