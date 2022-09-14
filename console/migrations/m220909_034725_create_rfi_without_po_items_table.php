<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rfi_without_po_items}}`.
 */
class m220909_034725_create_rfi_without_po_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rfi_without_po_items}}', [
            'id' => $this->primaryKey(),
            'fk_request_for_inspection_id' => $this->bigInteger()->notNull(),
            'project_name' => $this->text()->notNull(),
            'fk_stock_id' => $this->bigInteger(),
            'specification' => $this->text(),
            'fk_unit_of_measure_id' => $this->integer()->notNull(),
            'fk_payee_id' => $this->bigInteger()->notNull(),
            'unit_cost' => $this->decimal(10, 2),
            'quantity' => $this->integer()->notNull(),
            'from_date' => $this->date()->notNull(),
            'to_date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rfi_without_po_items}}');
    }
}
