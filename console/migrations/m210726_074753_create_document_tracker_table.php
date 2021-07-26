<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_tracker}}`.
 */
class m210726_074753_create_document_tracker_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_tracker}}', [
            'id' => $this->primaryKey(),
            'date_recieved' => $this->date(),
            'document_type' => $this->string(),
            'status' => $this->string(),
            'document_number' => $this->string(),
            'document_date' => $this->date(),
            'details' => $this->text(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
 
        $this->dropTable('{{%document_tracker}}');
    }
}
