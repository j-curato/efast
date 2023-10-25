<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transaction_iar_items}}`.
 */
class m231025_003117_create_po_transaction_iar_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transaction_iar_items}}', [
            'id' => $this->primaryKey(),
            'fk_po_transaction_id' => $this->integer()->notNull(),
            'fk_iar_id' => $this->bigInteger()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->createIndex('idx-po_txn_iar_items-fk_po_txn_id', 'po_transaction_iar_items', 'fk_po_transaction_id');
        $this->addForeignKey('fk-po_txn_iar_items-fk_po_txn_id', 'po_transaction_iar_items', 'fk_po_transaction_id', 'po_transaction', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx-po_txn_iar_items-fk_iar_id', 'po_transaction_iar_items', 'fk_iar_id');
        $this->addForeignKey('fk-po_txn_iar_items-fk_iar_id', 'po_transaction_iar_items', 'fk_iar_id', 'iar', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-po_txn_iar_items-fk_po_txn_id', 'po_transaction_iar_items');
        $this->dropIndex('idx-po_txn_iar_items-fk_po_txn_id', 'po_transaction_iar_items');

        $this->dropForeignKey('fk-po_txn_iar_items-fk_iar_id', 'po_transaction_iar_items');
        $this->dropIndex('idx-po_txn_iar_items-fk_iar_id', 'po_transaction_iar_items');

        $this->dropTable('{{%po_transaction_iar_items}}');
    }
}
