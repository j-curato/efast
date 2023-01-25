<?php

use yii\db\Migration;

/**
 * Class m230125_030332_add_constraints_in_liquidation_table
 */
class m230125_030332_add_constraints_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        // $this->createIndex('idx-check_range_id', 'liquidation', 'check_range_id');
        // $this->createIndex('idx-po_transaction_id', 'liquidation', 'po_transaction_id');
        $this->addForeignKey('fk-check_range_id', 'liquidation', 'check_range_id', 'check_range', 'id', 'RESTRICT');
        $this->addForeignKey('fk-po_transaction_id', 'liquidation', 'po_transaction_id', 'po_transaction', 'id', 'RESTRICT');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-check_range_id', 'liquidation');
        $this->dropForeignKey('fk-po_transaction_id', 'liquidation');
        $this->dropIndex('idx-check_range_id', 'liquidation');
        $this->dropIndex('idx-po_transaction_id', 'liquidation');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230125_030332_add_constraints_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
