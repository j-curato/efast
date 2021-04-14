<?php

use yii\db\Migration;

/**
 * Class m210413_132542_add_process_ors_id_to_dv_aucs_entries_table
 */
class m210413_132542_add_process_ors_id_to_dv_aucs_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("dv_aucs_entries","process_ors_id",$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("dv_aucs_entries","process_ors_id");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210413_132542_add_process_ors_id_to_dv_aucs_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
