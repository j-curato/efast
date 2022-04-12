<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll}}`.
 */
class m220411_004705_create_payroll_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll}}', [
            'id' => $this->primaryKey(),
            'payroll_number' => $this->string()->notNull()->unique(),
            'reporting_period' => $this->string()->notNull(),
            'process_ors_id' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'amount' => $this->decimal(15, 2)->defaultValue(0),
            'due_to_bir_amount'=>$this->decimal(15,2)->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);
        $this->alterColumn('payroll', 'id', $this->bigInteger()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payroll}}');
    }
}
