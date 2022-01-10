<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_stock}}`.
 */
class m220107_050025_create_pr_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_stock}}', [
            'id' => $this->primaryKey(),
            'stock' => $this->string(),
            'stock_number' => $this->string(),
            'bac_code' => $this->string(),
            'unit_of_measure_id' => $this->integer(),
            'amount' => $this->decimal(10, 2),
            'chart_of_account_id' => $this->integer(),
            'description' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_stock}}');
    }
}
