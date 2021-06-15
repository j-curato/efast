<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_transaction}}`.
 */
class m210614_053024_create_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_transaction}}', [
            'id' => $this->primaryKey(),
            ''
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
