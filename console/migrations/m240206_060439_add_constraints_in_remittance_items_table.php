<?php

use yii\db\Migration;

/**
 * Class m240206_060439_add_constraints_in_remittance_items_table
 */
class m240206_060439_add_constraints_in_remittance_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex('idx-remittance_items-fk_dv_acounting_entries_id', 'remittance_items', 'fk_dv_acounting_entries_id');
        $this->addForeignKey('fk-remittance_items-fk_dv_acounting_entries_id', 'remittance_items', 'fk_dv_acounting_entries_id', 'dv_accounting_entries', 'id', 'RESTRICT');
  
   
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-remittance_items-fk_dv_acounting_entries_id', 'remittance_items');
        $this->dropIndex('idx-remittance_items-fk_dv_acounting_entries_id', 'remittance_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_060439_add_constraints_in_remittance_items_table cannot be reverted.\n";

        return false;
    }
    */
}
