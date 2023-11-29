<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_creation_history}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m231129_073446_create_tbl_creation_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_creation_history}}', [
            'id' => $this->primaryKey(),
            'server_name' => $this->text()->notNull(),
            'table_name' => $this->text()->notNull(),
            'row_id' => $this->bigInteger()->notNull(),
            'fk_created_by' => $this->bigInteger(),
            'created_at' => $this->dateTime()->notNull()
        ]);
        $this->alterColumn('{{%tbl_creation_history}}', 'id', $this->bigInteger());
        // creates index for column `fk_created_by`
        $this->createIndex(
            '{{%idx-tbl_creation_history-fk_created_by}}',
            '{{%tbl_creation_history}}',
            'fk_created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-tbl_creation_history-fk_created_by}}',
            '{{%tbl_creation_history}}',
            'fk_created_by',
            '{{%user}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-tbl_creation_history-fk_created_by}}',
            '{{%tbl_creation_history}}'
        );

        // drops index for column `fk_created_by`
        $this->dropIndex(
            '{{%idx-tbl_creation_history-fk_created_by}}',
            '{{%tbl_creation_history}}'
        );

        $this->dropTable('{{%tbl_creation_history}}');
    }
}
