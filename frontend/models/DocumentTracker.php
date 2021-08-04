<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_tracker".
 *
 * @property int $id
 * @property string|null $date_recieved
 * @property string|null $document_type
 * @property string|null $status
 * @property string|null $document_number
 * @property string|null $document_date
 * @property string|null $details
 *
 * @property ResponsibleOffice $responsibleOffice
 * @property DocumentTrackerComplinceLink[] $documentTrackerComplinceLinks
 * @property DocumentTrackerLinks[] $documentTrackerLinks
 */
class DocumentTracker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_tracker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_recieved', 'document_date'], 'safe'],
            [['details'], 'string'],
            [['document_type', 'status', 'document_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_recieved' => 'Date Recieved',
            'document_type' => 'Document Type',
            'status' => 'Status',
            'document_number' => 'Document Number',
            'document_date' => 'Document Date',
            'details' => 'Details',
        ];
    }

    /**
     * Gets query for [[ResponsibleOffice]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[DocumentTrackerComplinceLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentTrackerComplinceLinks()
    {
        return $this->hasMany(DocumentTrackerComplinceLink::class, ['document_tracker_id' => 'id']);
    }

    /**
     * Gets query for [[DocumentTrackerLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentTrackerLinks()
    {
        return $this->hasMany(DocumentTrackerLinks::class, ['document_tracker_id' => 'id']);
    }
    public function getDocumentTrackerOffice()
    {
        return $this->hasMany(DocumentTrackerResponsibleOffice::class, ['document_tracker_id' => 'id']);
    }
}
