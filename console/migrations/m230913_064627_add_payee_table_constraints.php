<?php

use yii\db\Migration;

/**
 * Class m230913_064627_add_payee_table_constraints
 */
class m230913_064627_add_payee_table_constraints extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-payee_id', 'dv_aucs', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-liquidation-payee_id', 'liquidation', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-aoqItm-payee_id', 'pr_aoq_entries', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-tracking_sheet-payee_id', 'tracking_sheet', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-transaction-payee_id', 'transaction', 'payee_id', 'payee', 'id', 'RESTRICT');



        $this->createIndex('idx-jev-payee_id', 'jev_preparation', 'payee_id');
        $this->addForeignKey('fk-jev-payee_id', 'jev_preparation', 'payee_id', 'payee', 'id', 'RESTRICT');


        $this->createIndex('idx-pur-ord-payee_id', 'pr_purchase_order_item', 'payee_id');
        $this->addForeignKey('fk-pur-ord-payee_id', 'pr_purchase_order_item', 'payee_id', 'payee', 'id', 'RESTRICT');


        $this->createIndex('idx-remittance-payee_id', 'remittance', 'payee_id');
        $this->addForeignKey('fk-remittance-payee_id', 'remittance', 'payee_id', 'payee', 'id', 'RESTRICT');

        $this->createIndex('idx-remit-payee-payee_id', 'remittance_payee', 'payee_id');
        $this->addForeignKey('fk-remit-payee-payee_id', 'remittance_payee', 'payee_id', 'payee', 'id', 'RESTRICT');

        $this->createIndex('idx-rfiNoPo-payee_id', 'rfi_without_po_items', 'fk_payee_id');
        $this->addForeignKey('fk-rfiNoPo-payee_id', 'rfi_without_po_items', 'fk_payee_id', 'payee', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payee_id', 'dv_aucs', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->dropForeignKey('fk-liquidation-payee_id', 'liquidation', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->dropForeignKey('fk-aoqItm-payee_id', 'pr_aoq_entries', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->dropForeignKey('fk-tracking_sheet-payee_id', 'tracking_sheet', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->dropForeignKey('fk-transaction-payee_id', 'transaction', 'payee_id', 'payee', 'id', 'RESTRICT');



        $this->dropForeignKey('fk-jev-payee_id', 'jev_preparation');
        $this->dropIndex('idx-jev-payee_id', 'jev_preparation');


        $this->dropForeignKey('fk-pur-ord-payee_id', 'pr_purchase_order_item');
        $this->dropIndex('idx-pur-ord-payee_id', 'pr_purchase_order_item');


        $this->dropForeignKey('fk-remittance-payee_id', 'remittance');
        $this->dropIndex('idx-remittance-payee_id', 'remittance');

        $this->dropForeignKey('fk-remit-payee-payee_id', 'remittance_payee');
        $this->dropIndex('idx-remit-payee-payee_id', 'remittance_payee');

        $this->dropForeignKey('fk-rfiNoPo-payee_id', 'rfi_without_po_items');
        $this->dropIndex('idx-rfiNoPo-payee_id', 'rfi_without_po_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230913_064627_add_payee_table_constraints cannot be reverted.\n";

        return false;
    }
    */
}
