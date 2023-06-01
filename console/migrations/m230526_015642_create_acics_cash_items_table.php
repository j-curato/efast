<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acics_cash_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%acics}}`
 */
class m230526_015642_create_acics_cash_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acics_cash_items}}', [
            'id' => $this->primaryKey(),
            'fk_acic_id' => $this->bigInteger()->notNull(),
            'fk_cash_disbursement_id' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_acic_id`
        $this->createIndex(
            '{{%idx-acics_cash_items-fk_acic_id}}',
            '{{%acics_cash_items}}',
            'fk_acic_id'
        );

        // add foreign key for table `{{%acics}}`
        $this->addForeignKey(
            '{{%fk-acics_cash_items-fk_acic_id}}',
            '{{%acics_cash_items}}',
            'fk_acic_id',
            '{{%acics}}',
            'id',
            'CASCADE'
        );
        // creates index for column `fk_acic_id`
        $this->createIndex(
            '{{%idx-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}',
            'fk_cash_disbursement_id'
        );

        // add foreign key for table `{{%acics}}`
        $this->addForeignKey(
            '{{%fk-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}',
            'fk_cash_disbursement_id',
            '{{%cash_disbursement}}',
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
            '{{%fk-acics_cash_items-fk_acic_id}}',
            '{{%acics_cash_items}}'
        );

        // drops index for column `fk_acic_id`
        $this->dropIndex(
            '{{%idx-acics_cash_items-fk_acic_id}}',
            '{{%acics_cash_items}}'
        );


        // add foreign key for table `{{%acics}}`
        $this->dropForeignKey(
            '{{%fk-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}'

        );

        $this->dropIndex(
            '{{%idx-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}'
        );
        $this->dropTable('{{%acics_cash_items}}');
    }
}
