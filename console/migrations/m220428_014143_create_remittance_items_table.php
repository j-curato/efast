<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%remittance_items}}`.
 */
class m220428_014143_create_remittance_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%remittance_items}}', [
            'id' => $this->primaryKey(),
            'fk_remittance_id' => $this->bigInteger(),
            'fk_dv_acounting_entries_id' => $this->integer(),
            'amount' => $this->decimal(10, 2),
            'is_removed' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('remittance_items', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%remittance_items}}');
    }
}
