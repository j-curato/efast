<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acic_cancelled_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%acics}}`
 * - `{{%cash_disbursement}}`
 */
class m230601_081446_create_acic_cancelled_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acic_cancelled_items}}', [
            'id' => $this->primaryKey(),
            'fk_acic_id' => $this->bigInteger()->notNull(),
            'fk_cash_disbursement_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_acic_id`
        $this->createIndex(
            '{{%idx-acic_cancelled_items-fk_acic_id}}',
            '{{%acic_cancelled_items}}',
            'fk_acic_id'
        );

        // add foreign key for table `{{%acics}}`
        $this->addForeignKey(
            '{{%fk-acic_cancelled_items-fk_acic_id}}',
            '{{%acic_cancelled_items}}',
            'fk_acic_id',
            '{{%acics}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_cash_disbursement_id`
        $this->createIndex(
            '{{%idx-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}',
            'fk_cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}',
            'fk_cash_disbursement_id',
            '{{%cash_disbursement}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%acics}}`
        $this->dropForeignKey(
            '{{%fk-acic_cancelled_items-fk_acic_id}}',
            '{{%acic_cancelled_items}}'
        );

        // drops index for column `fk_acic_id`
        $this->dropIndex(
            '{{%idx-acic_cancelled_items-fk_acic_id}}',
            '{{%acic_cancelled_items}}'
        );

        // drops foreign key for table `{{%cash_disbursement}}`
        $this->dropForeignKey(
            '{{%fk-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}'
        );

        // drops index for column `fk_cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}'
        );

        $this->dropTable('{{%acic_cancelled_items}}');
    }
}
