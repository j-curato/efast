<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_disbursement_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%cash_disbursement}}`
 * - `{{%chart_of_accounts}}`
 * - `{{%dv_aucs}}`
 */
class m230515_023035_create_cash_disbursement_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_disbursement_items}}', [
            'id' => $this->primaryKey(),
            'fk_cash_disbursement_id' => $this->integer(),
            'fk_chart_of_account_id' => $this->integer(),
            'fk_dv_aucs_id' => $this->integer(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_cash_disbursement_id`
        $this->createIndex(
            '{{%idx-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}',
            'fk_cash_disbursement_id'
        );

        // add foreign key for table `{{%cash_disbursement}}`
        $this->addForeignKey(
            '{{%fk-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}',
            'fk_cash_disbursement_id',
            '{{%cash_disbursement}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_chart_of_account_id`
        $this->createIndex(
            '{{%idx-cash_disbursement_items-fk_chart_of_account_id}}',
            '{{%cash_disbursement_items}}',
            'fk_chart_of_account_id'
        );

        // add foreign key for table `{{%chart_of_accounts}}`
        $this->addForeignKey(
            '{{%fk-cash_disbursement_items-fk_chart_of_account_id}}',
            '{{%cash_disbursement_items}}',
            'fk_chart_of_account_id',
            '{{%chart_of_accounts}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_dv_aucs_id`
        $this->createIndex(
            '{{%idx-cash_disbursement_items-fk_dv_aucs_id}}',
            '{{%cash_disbursement_items}}',
            'fk_dv_aucs_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        $this->addForeignKey(
            '{{%fk-cash_disbursement_items-fk_dv_aucs_id}}',
            '{{%cash_disbursement_items}}',
            'fk_dv_aucs_id',
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
        // drops foreign key for table `{{%cash_disbursement}}`
        $this->dropForeignKey(
            '{{%fk-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}'
        );

        // drops index for column `fk_cash_disbursement_id`
        $this->dropIndex(
            '{{%idx-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}'
        );

        // drops foreign key for table `{{%chart_of_accounts}}`
        $this->dropForeignKey(
            '{{%fk-cash_disbursement_items-fk_chart_of_account_id}}',
            '{{%cash_disbursement_items}}'
        );

        // drops index for column `fk_chart_of_account_id`
        $this->dropIndex(
            '{{%idx-cash_disbursement_items-fk_chart_of_account_id}}',
            '{{%cash_disbursement_items}}'
        );

        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-cash_disbursement_items-fk_dv_aucs_id}}',
            '{{%cash_disbursement_items}}'
        );

        // drops index for column `fk_dv_aucs_id`
        $this->dropIndex(
            '{{%idx-cash_disbursement_items-fk_dv_aucs_id}}',
            '{{%cash_disbursement_items}}'
        );

        $this->dropTable('{{%cash_disbursement_items}}');
    }
}
