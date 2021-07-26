<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_tracker_links}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document_tracker}}`
 */
class m210726_075334_create_document_tracker_links_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_tracker_links}}', [
            'id' => $this->primaryKey(),
            'document_tracker_id' => $this->integer(),
            'link'=>$this->text()
        ]);

        // creates index for column `document_tracker_id`
        $this->createIndex(
            '{{%idx-document_tracker_links-document_tracker_id}}',
            '{{%document_tracker_links}}',
            'document_tracker_id'
        );

        // add foreign key for table `{{%document_tracker}}`
        $this->addForeignKey(
            '{{%fk-document_tracker_links-document_tracker_id}}',
            '{{%document_tracker_links}}',
            'document_tracker_id',
            '{{%document_tracker}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%document_tracker}}`
        $this->dropForeignKey(
            '{{%fk-document_tracker_links-document_tracker_id}}',
            '{{%document_tracker_links}}'
        );

        // drops index for column `document_tracker_id`
        $this->dropIndex(
            '{{%idx-document_tracker_links-document_tracker_id}}',
            '{{%document_tracker_links}}'
        );

        $this->dropTable('{{%document_tracker_links}}');
    }
}
