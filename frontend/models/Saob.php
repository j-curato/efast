<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "saob".
 *
 * @property int $id
 * @property string|null $from_reporting_period
 * @property string|null $to_reporting_period
 * @property int|null $mfo_pap_code_id
 * @property int|null $document_recieve_id
 * @property int|null $book_id
 * @property string $created_at
 */
class Saob extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'saob';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mfo_pap_code_id', 'document_recieve_id', 'book_id', 'created_at'], 'safe'],
            [['from_reporting_period', 'to_reporting_period'], 'string', 'max' => 255],
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
            'to_reporting_period' => 'To Reporting Period',
            'mfo_pap_code_id' => 'Mfo Pap Code ID',
            'document_recieve_id' => 'Document Recieve ID',
            'book_id' => 'Book ID',
            'created_at' => 'Created At',
        ];
    }
    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }
    public function getMfo()
    {
        return $this->hasOne(MfoPapCode::class, ['id' => 'mfo_pap_code_id']);
    }
    public function getDocumentRecieve()
    {
        return $this->hasOne(DocumentRecieve::class, ['id' => 'document_recieve_id']);
    }
}
