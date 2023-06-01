<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acic_in_bank_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%acic_in_bank}}`
 * - `{{%acics}}`
 */
class m230530_081809_create_acic_in_bank_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acic_in_bank_items}}', [
            'id' => $this->primaryKey(),
            'fk_acic_in_bank_id' => $this->bigInteger()->notNull(),
            'fk_acic_id' => $this->bigInteger()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_acic_in_bank_id`
        $this->createIndex(
            '{{%idx-acic_in_bank_items-fk_acic_in_bank_id}}',
            '{{%acic_in_bank_items}}',
            'fk_acic_in_bank_id'
        );

        // add foreign key for table `{{%acic_in_bank}}`
        $this->addForeignKey(
            '{{%fk-acic_in_bank_items-fk_acic_in_bank_id}}',
            '{{%acic_in_bank_items}}',
            'fk_acic_in_bank_id',
            '{{%acic_in_bank}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_acic_id`
        $this->createIndex(
            '{{%idx-acic_in_bank_items-fk_acic_id}}',
            '{{%acic_in_bank_items}}',
            'fk_acic_id'
        );

        // add foreign key for table `{{%acics}}`
        $this->addForeignKey(
            '{{%fk-acic_in_bank_items-fk_acic_id}}',
            '{{%acic_in_bank_items}}',
            'fk_acic_id',
            '{{%acics}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%acic_in_bank}}`
        $this->dropForeignKey(
            '{{%fk-acic_in_bank_items-fk_acic_in_bank_id}}',
            '{{%acic_in_bank_items}}'
        );

        // drops index for column `fk_acic_in_bank_id`
        $this->dropIndex(
            '{{%idx-acic_in_bank_items-fk_acic_in_bank_id}}',
            '{{%acic_in_bank_items}}'
        );

        // drops foreign key for table `{{%acics}}`
        $this->dropForeignKey(
            '{{%fk-acic_in_bank_items-fk_acic_id}}',
            '{{%acic_in_bank_items}}'
        );

        // drops index for column `fk_acic_id`
        $this->dropIndex(
            '{{%idx-acic_in_bank_items-fk_acic_id}}',
            '{{%acic_in_bank_items}}'
        );

        $this->dropTable('{{%acic_in_bank_items}}');
    }
}
