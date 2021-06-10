<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sub_accounts_view".
 *
 * @property int $row_number
 * @property string $object_code
 * @property string $account_title
 */
class SubAccountsView extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_accounts_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['row_number'], 'integer'],
            [['object_code'], 'string', 'max' => 255],
            [['account_title'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'row_number' => 'Row Number',
            'object_code' => 'Object Code',
            'account_title' => 'Account Title',
        ];
    }
}
