<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%purchase_order}}`.
 */
class m220303_070608_create_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_purchase_order}}', [
            'id' => $this->primaryKey(),
            'po_number' => $this->string()->unique()->notNull(),
            'fk_contract_type_id' => $this->integer()->notNull(),
            'fk_mode_of_procurement_id' => $this->integer()->notNull(),
            'fk_pr_aoq_id' => $this->integer()->notNull(),
            'place_of_delivery' => $this->text(),
            'delivery_date' => $this->date(),
            'delivery_term' => $this->text(),
            'payment_term' => $this->string(),
            'fk_auth_official' => $this->bigInteger(),
            'fk_accounting_unit' => $this->bigInteger(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')


        ]);
        $this->alterColumn('pr_purchase_order', 'id', $this->bigInteger()->notNull());
        // $this->addPrimaryKey('pk_purchase_order', 'pr_purchase_order', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_purchase_order}}');
    }
}
