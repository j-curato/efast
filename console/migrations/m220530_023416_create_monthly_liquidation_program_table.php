<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%monthly_liquidation_program}}`.
 */
class m220530_023416_create_monthly_liquidation_program_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%monthly_liquidation_program}}', [
            'id' => $this->primaryKey(),
            'reporting_period' => $this->string(20),
            'amount' => $this->decimal(10, 2),
            'book_id' => $this->integer(),
            'province' => $this->string(20),
            'fund_source_type' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%monthly_liquidation_program}}');
    }
}
