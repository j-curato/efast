<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_deposits}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mgrfrs}}`
 */
class m231108_015009_create_rapid_mg_cash_deposits_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_deposits}}', [
            'id' => $this->primaryKey(),
            'fk_mgrfr_id' => $this->bigInteger(),
            'serial_number' => $this->string()->notNull()->unique(),
            'reporting_period' => $this->string()->notNull(),
            'date' => $this->date()->notNull(),
            'particular' => $this->text()->notNull(),
            'matching_grant_amount' => $this->decimal(15, 2),
            'equity_amount' => $this->decimal(15, 2),
            'other_amount' => $this->decimal(15, 2),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('cash_deposits', 'id', $this->bigInteger());
        // creates index for column `fk_mgrfr_id`
        $this->createIndex(
            '{{%idx-cash_deposits-fk_mgrfr_id}}',
            '{{%cash_deposits}}',
            'fk_mgrfr_id'
        );

        // add foreign key for table `{{%mgrfrs}}`
        $this->addForeignKey(
            '{{%fk-cash_deposits-fk_mgrfr_id}}',
            '{{%cash_deposits}}',
            'fk_mgrfr_id',
            '{{%mgrfrs}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mgrfrs}}`
        $this->dropForeignKey(
            '{{%fk-cash_deposits-fk_mgrfr_id}}',
            '{{%cash_deposits}}'
        );

        // drops index for column `fk_mgrfr_id`
        $this->dropIndex(
            '{{%idx-cash_deposits-fk_mgrfr_id}}',
            '{{%cash_deposits}}'
        );

        $this->dropTable('{{%cash_deposits}}');
    }
}
