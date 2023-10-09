<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notice_of_postponement_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pr_rfq}}`
 * - `{{%notice_of_postponement}}`
 */
class m231005_022905_create_notice_of_postponement_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notice_of_postponement_items}}', [
            'id' => $this->primaryKey(),
            'fk_rfq_id' => $this->bigInteger()->notNull(),
            'fk_notice_of_postponement_id' => $this->bigInteger()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'from_date' => $this->date()->notNull(),
            'to_date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('notice_of_postponement_items', 'id', $this->bigInteger());

        // creates index for column `fk_rfq_id`
        $this->createIndex(
            '{{%idx-notice_of_postponement_items-fk_rfq_id}}',
            '{{%notice_of_postponement_items}}',
            'fk_rfq_id'
        );

        // add foreign key for table `{{%pr_rfq}}`
        $this->addForeignKey(
            '{{%fk-notice_of_postponement_items-fk_rfq_id}}',
            '{{%notice_of_postponement_items}}',
            'fk_rfq_id',
            '{{%pr_rfq}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_notice_of_postponement_id`
        $this->createIndex(
            '{{%idx-notice_of_postponement_items-fk_notice_of_postponement_id}}',
            '{{%notice_of_postponement_items}}',
            'fk_notice_of_postponement_id'
        );

        // add foreign key for table `{{%notice_of_postponement}}`
        $this->addForeignKey(
            '{{%fk-notice_of_postponement_items-fk_notice_of_postponement_id}}',
            '{{%notice_of_postponement_items}}',
            'fk_notice_of_postponement_id',
            '{{%notice_of_postponement}}',
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
            '{{%fk-notice_of_postponement_items-fk_rfq_id}}',
            '{{%notice_of_postponement_items}}'
        );

        // drops index for column `fk_rfq_id`
        $this->dropIndex(
            '{{%idx-notice_of_postponement_items-fk_rfq_id}}',
            '{{%notice_of_postponement_items}}'
        );

        // drops foreign key for table `{{%notice_of_postponement}}`
        $this->dropForeignKey(
            '{{%fk-notice_of_postponement_items-fk_notice_of_postponement_id}}',
            '{{%notice_of_postponement_items}}'
        );

        // drops index for column `fk_notice_of_postponement_id`
        $this->dropIndex(
            '{{%idx-notice_of_postponement_items-fk_notice_of_postponement_id}}',
            '{{%notice_of_postponement_items}}'
        );

        $this->dropTable('{{%notice_of_postponement_items}}');
    }
}
