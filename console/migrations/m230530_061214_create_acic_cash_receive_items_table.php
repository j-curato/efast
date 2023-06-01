<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acic_cash_receive_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%acics}}`
 * - `{{%cash_received}}`
 */
class m230530_061214_create_acic_cash_receive_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acic_cash_receive_items}}', [
            'id' => $this->primaryKey(),
            'fk_acic_id' => $this->bigInteger()->notNull(),
            'fk_cash_receive_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'deleted_at' => $this->timestamp()->defaultValue(NULL),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_acic_id`
        $this->createIndex(
            '{{%idx-acic_cash_receive_items-fk_acic_id}}',
            '{{%acic_cash_receive_items}}',
            'fk_acic_id'
        );

        // add foreign key for table `{{%acics}}`
        $this->addForeignKey(
            '{{%fk-acic_cash_receive_items-fk_acic_id}}',
            '{{%acic_cash_receive_items}}',
            'fk_acic_id',
            '{{%acics}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_cash_receive_id`
        $this->createIndex(
            '{{%idx-acic_cash_receive_items-fk_cash_receive_id}}',
            '{{%acic_cash_receive_items}}',
            'fk_cash_receive_id'
        );

        // add foreign key for table `{{%cash_received}}`
        $this->addForeignKey(
            '{{%fk-acic_cash_receive_items-fk_cash_receive_id}}',
            '{{%acic_cash_receive_items}}',
            'fk_cash_receive_id',
            '{{%cash_received}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%acics}}`
        $this->dropForeignKey(
            '{{%fk-acic_cash_receive_items-fk_acic_id}}',
            '{{%acic_cash_receive_items}}'
        );

        // drops index for column `fk_acic_id`
        $this->dropIndex(
            '{{%idx-acic_cash_receive_items-fk_acic_id}}',
            '{{%acic_cash_receive_items}}'
        );

        // drops foreign key for table `{{%cash_received}}`
        $this->dropForeignKey(
            '{{%fk-acic_cash_receive_items-fk_cash_receive_id}}',
            '{{%acic_cash_receive_items}}'
        );

        // drops index for column `fk_cash_receive_id`
        $this->dropIndex(
            '{{%idx-acic_cash_receive_items-fk_cash_receive_id}}',
            '{{%acic_cash_receive_items}}'
        );

        $this->dropTable('{{%acic_cash_receive_items}}');
    }
}
