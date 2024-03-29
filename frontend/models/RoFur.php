<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ro_fur".
 *
 * @property int $id
 * @property string|null $from_reporting_period
 * @property string|null $to_reporting_period
 * @property string|null $division
 * @property int|null $document_recieve_id
 * @property string $created_at
 */
class RoFur extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ro_fur';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'document_recieve_id'], 'safe'],
            [['from_reporting_period', 'to_reporting_period', 'division'], 'string', 'max' => 20],
            [[
                'id',
                'from_reporting_period',
                'to_reporting_period',
                'division',
                'document_recieve_id',
                'created_at',

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
            'from_reporting_period' => 'From Reporting Period',
            'to_reporting_period' => ' Reporting Period',
            'division' => 'Division',
            'document_recieve_id' => 'Document Recieve ',
            'created_at' => 'Created At',
        ];
    }
    public function getDocumentReceive()
    {
        return $this->hasOne(DocumentRecieve::class, ['id' => 'document_recieve_id']);
    }
}
