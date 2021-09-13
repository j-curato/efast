<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rod_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%rod}}`
 */
class m210909_075508_create_rod_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rod_entries}}', [
            'id' => $this->primaryKey(),
            'rod_number' => $this->string(),
            'advances_entries_id'=>$this->integer()
        ]);

        // creates index for column `rod_number`
        $this->createIndex(
            '{{%idx-rod_entries-rod_number}}',
            '{{%rod_entries}}',
            'rod_number'
        );

        // add foreign key for table `{{%rod}}`
        $this->addForeignKey(
            '{{%fk-rod_entries-rod_number}}',
            '{{%rod_entries}}',
            'rod_number',
            '{{%rod}}',
            'rod_number',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%rod}}`
        $this->dropForeignKey(
            '{{%fk-rod_entries-rod_number}}',
            '{{%rod_entries}}'
        );

        // drops index for column `rod_number`
        $this->dropIndex(
            '{{%idx-rod_entries-rod_number}}',
            '{{%rod_entries}}'
        );

        $this->dropTable('{{%rod_entries}}');
    }
}
