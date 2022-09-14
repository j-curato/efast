<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase_order_transmittal}}`.
 */
class m220914_061942_create_purchase_order_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%purchase_order_transmittal}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('purchase_order_transmittal', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%purchase_order_transmittal}}');
    }
}
