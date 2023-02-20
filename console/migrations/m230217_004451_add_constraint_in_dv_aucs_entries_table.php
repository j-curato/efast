<?php

use yii\db\Migration;

/**
 * Class m230217_004451_add_constraint_in_dv_aucs_entries_table
 */
class m230217_004451_add_constraint_in_dv_aucs_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->query();
        $this->createIndex('idx-dve-process_ors_id', 'dv_aucs_entries', 'process_ors_id');
        $this->addForeignKey('fk-dve-process_ors_id', 'dv_aucs_entries', 'process_ors_id', 'process_ors', 'id', 'RESTRICT');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-dve-process_ors_id', 'dv_aucs_entries');
        $this->dropIndex('idx-dve-process_ors_id', 'dv_aucs_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230217_004451_add_constraint_in_dv_aucs_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
