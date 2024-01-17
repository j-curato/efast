<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_rfq_observers}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pr_rfq}}`
 */
class m240117_022402_create_tbl_rfq_observers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_rfq_observers}}', [
            'id' => $this->primaryKey(),
            'fk_rfq_id' => $this->bigInteger()->notNull(),
            'observer_name' => $this->string()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false)
        ]);

        // creates index for column `fk_rfq_id`
        $this->createIndex(
            '{{%idx-tbl_rfq_observers-fk_rfq_id}}',
            '{{%tbl_rfq_observers}}',
            'fk_rfq_id'
        );

        // add foreign key for table `{{%pr_rfq}}`
        $this->addForeignKey(
            '{{%fk-tbl_rfq_observers-fk_rfq_id}}',
            '{{%tbl_rfq_observers}}',
            'fk_rfq_id',
            '{{%pr_rfq}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pr_rfq}}`
        $this->dropForeignKey(
            '{{%fk-tbl_rfq_observers-fk_rfq_id}}',
            '{{%tbl_rfq_observers}}'
        );

        // drops index for column `fk_rfq_id`
        $this->dropIndex(
            '{{%idx-tbl_rfq_observers-fk_rfq_id}}',
            '{{%tbl_rfq_observers}}'
        );

        $this->dropTable('{{%tbl_rfq_observers}}');
    }
}
