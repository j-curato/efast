<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_purchase_order_items_aoq_items}}`.
 */
class m220720_082024_create_pr_purchase_order_items_aoq_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_order_items_aoq_items}}', [
            'id' => $this->primaryKey(),
            'fk_purchase_order_item_id' => $this->bigInteger()->notNull(),
            'fk_aoq_entries_id' => $this->bigInteger()->notNull(),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_purchase_order_items_aoq_items}}');
    }
}
