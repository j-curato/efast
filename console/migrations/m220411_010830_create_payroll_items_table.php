<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payroll_items}}`.
 */
class m220411_010830_create_payroll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payroll_items}}', [
            'id' => $this->primaryKey(),
            'payroll_id' => $this->bigInteger()->notNull(),
            'object_code' => $this->string()->notNull(),
            'amount' => $this->decimal(15, 2)->defaultValue(0),
            'remittance_payee_id' => $this->integer(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payroll_items}}');
    }
}
