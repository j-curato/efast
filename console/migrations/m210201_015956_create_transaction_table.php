<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%responsibility_center}}`
 * - `{{%payee}}`
 */
class m210201_015956_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'responsibility_center_id' => $this->integer()->notNull(),
            'payee_id' => $this->integer()->notNull(),
            'particular' => $this->string(255)->notNull(),
            'gross_amount' => $this->decimal(10, 2),
            'tracking_number' => $this->string(255),
            'earmark_no' => $this->string(255),
            'payroll_number' => $this->string(255),
            'transaction_date' => $this->string(50),
            'transaction_time' => $this->string(20),
        ]);

        // creates index for column `responsibility_center_id`
        $this->createIndex(
            '{{%idx-transaction-responsibility_center_id}}',
            '{{%transaction}}',
            'responsibility_center_id'
        );

        // add foreign key for table `{{%responsibility_center}}`
        $this->addForeignKey(
            '{{%fk-transaction-responsibility_center_id}}',
            '{{%transaction}}',
            'responsibility_center_id',
            '{{%responsibility_center}}',
            'id',
            'CASCADE'
        );

        // creates index for column `payee_id`
        $this->createIndex(
            '{{%idx-transaction-payee_id}}',
            '{{%transaction}}',
            'payee_id'
        );

        // add foreign key for table `{{%payee}}`
        $this->addForeignKey(
            '{{%fk-transaction-payee_id}}',
            '{{%transaction}}',
            'payee_id',
            '{{%payee}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%responsibility_center}}`
        $this->dropForeignKey(
            '{{%fk-transaction-responsibility_center_id}}',
            '{{%transaction}}'
        );

        // drops index for column `responsibility_center_id`
        $this->dropIndex(
            '{{%idx-transaction-responsibility_center_id}}',
            '{{%transaction}}'
        );

        // drops foreign key for table `{{%payee}}`
        $this->dropForeignKey(
            '{{%fk-transaction-payee_id}}',
            '{{%transaction}}'
        );

        // drops index for column `payee_id`
        $this->dropIndex(
            '{{%idx-transaction-payee_id}}',
            '{{%transaction}}'
        );

        $this->dropTable('{{%transaction}}');
    }
}
