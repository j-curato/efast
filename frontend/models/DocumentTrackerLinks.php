<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_tracker_links".
 *
 * @property int $id
 * @property int|null $document_tracker_id
 * @property string|null $link
 *
 * @property DocumentTracker $documentTracker
 */
class DocumentTrackerLinks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_tracker_links';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['document_tracker_id'], 'integer'],
            [['link'], 'safe'],
            [['document_tracker_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentTracker::className(), 'targetAttribute' => ['document_tracker_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_tracker_id' => 'Document Tracker ID',
            'link' => 'Link',
        ];
    }

    /**
     * Gets query for [[DocumentTracker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentTracker()
    {
        return $this->hasOne(DocumentTracker::className(), ['id' => 'document_tracker_id']);
    }
}
