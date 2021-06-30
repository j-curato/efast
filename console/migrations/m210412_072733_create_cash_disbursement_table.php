<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_disbursement}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%books}}`
 * - `{{%dv_aucs}}`
 */
class m210412_072733_create_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_disbursement}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer(),
            'dv_aucs_id' => $this->integer(),
            'dv_aucs_entries_id' => $this->integer(),
            'reporting_period' => $this->string(50),
            'mode_of_payment' => $this->string(50),
            'check_or_ada_no' => $this->string(100),
            'is_cancelled' => $this->boolean()->defaultValue(false),
            'issuance_date' => $this->string(50),
        ]);

        // creates index for column `book_id`
        $this->createIndex(
            '{{%idx-cash_disbursement-book_id}}',
            '{{%cash_disbursement}}',
            'book_id'
        );

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-cash_disbursement-book_id}}',
            '{{%cash_disbursement}}',
            'book_id',
            '{{%books}}',
            'id',
            'CASCADE'
        );

        // creates index for column `dv_aucs_id`
        $this->createIndex(
            '{{%idx-cash_disbursement-dv_aucs_id}}',
            '{{%cash_disbursement}}',
            'dv_aucs_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        $this->addForeignKey(
            '{{%fk-cash_disbursement-dv_aucs_id}}',
            '{{%cash_disbursement}}',
            'dv_aucs_id',
            '{{%dv_aucs}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-cash_disbursement-book_id}}',
            '{{%cash_disbursement}}'
        );

        // drops index for column `book_id`
        $this->dropIndex(
            '{{%idx-cash_disbursement-book_id}}',
            '{{%cash_disbursement}}'
        );

        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-cash_disbursement-dv_aucs_id}}',
            '{{%cash_disbursement}}'
        );

        // drops index for column `dv_aucs_id`
        $this->dropIndex(
            '{{%idx-cash_disbursement-dv_aucs_id}}',
            '{{%cash_disbursement}}'
        );

        $this->dropTable('{{%cash_disbursement}}');
    }
}
