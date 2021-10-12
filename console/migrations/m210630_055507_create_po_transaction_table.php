<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transaction}}`.
 */
class m210630_055507_create_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transaction}}', [
            'id' => $this->primaryKey(),
            'payee' => $this->string(),
            'particular' => $this->text(),
            'amount' => $this->decimal(10, 2),
            'payroll_number' => $this->string(),
            'tracking_number' => $this->string(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%po_transaction}}');
    }
}
