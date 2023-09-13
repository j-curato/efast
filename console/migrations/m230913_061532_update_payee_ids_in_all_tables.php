<?php

use yii\db\Migration;

/**
 * Class m230913_061532_update_payee_ids_in_all_tables
 */
class m230913_061532_update_payee_ids_in_all_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropForeignKey('fk-payee_id', 'dv_aucs');
        $this->alterColumn('dv_aucs', 'payee_id', $this->bigInteger());


        $this->alterColumn('jev_preparation', 'payee_id', $this->bigInteger());

        $this->dropForeignKey('fk-liquidation-payee_id', 'liquidation');
        $this->alterColumn('liquidation', 'payee_id', $this->bigInteger());

        $this->dropForeignKey('fk-aoqItm-payee_id', 'pr_aoq_entries');
        $this->alterColumn('pr_aoq_entries', 'payee_id', $this->bigInteger());

        $this->alterColumn('pr_purchase_order_item', 'payee_id', $this->bigInteger());
        $this->alterColumn('remittance', 'payee_id', $this->bigInteger());

        $this->alterColumn('remittance_payee', 'payee_id', $this->bigInteger());
        $this->alterColumn('rfi_without_po_items', 'fk_payee_id', $this->bigInteger());


        $this->dropForeignKey('fk-tracking_sheet-payee_id', 'tracking_sheet');
        $this->alterColumn('tracking_sheet', 'payee_id', $this->bigInteger());

        $this->dropForeignKey('fk-transaction-payee_id', 'transaction');
        $this->alterColumn('transaction', 'payee_id', $this->bigInteger());


        $this->alterColumn('payee', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk-payee_id', 'dv_aucs', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-liquidation-payee_id', 'liquidation', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-aoqItm-payee_id', 'pr_aoq_entries', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-tracking_sheet-payee_id', 'tracking_sheet', 'payee_id', 'payee', 'id', 'RESTRICT');
        $this->addForeignKey('fk-transaction-payee_id', 'transaction', 'payee_id', 'payee', 'id', 'RESTRICT');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230913_061532_update_payee_ids_in_all_tables cannot be reverted.\n";

        return false;
    }
    */
}
