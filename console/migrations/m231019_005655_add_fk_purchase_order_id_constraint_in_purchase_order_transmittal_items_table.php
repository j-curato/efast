<?php

use yii\db\Migration;

/**
 * Class m231019_005655_add_fk_purchase_order_id_constraint_in_purchase_order_transmittal_items_table
 */
class m231019_005655_add_fk_purchase_order_id_constraint_in_purchase_order_transmittal_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->query();
        $this->createIndex('idx-po-trasmittal-fk_purchase_order_item_id', 'purchase_order_transmittal_items', 'fk_purchase_order_item_id');
        $this->addForeignKey('fk-po-trasmittal-fk_purchase_order_item_id', 'purchase_order_transmittal_items', 'fk_purchase_order_item_id', 'pr_purchase_order_item', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-po-trasmittal-fk_purchase_order_item_id', 'purchase_order_transmittal_items');
        $this->dropIndex('idx-po-trasmittal-fk_purchase_order_item_id', 'purchase_order_transmittal_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231019_005655_add_fk_purchase_order_id_constraint_in_purchase_order_transmittal_items_table cannot be reverted.\n";

        return false;
    }
    */
}
