<?php

use yii\db\Migration;

/**
 * Class m230619_085211_add_fk_dv_aucs_id_in_transmittal_entries_table
 */
class m230619_085211_add_fk_dv_aucs_id_in_transmittal_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transmittal_entries', 'fk_dv_aucs_id', $this->integer());
        $this->createIndex('idx-transmittal-fk_dv_aucs_id', 'transmittal_entries', 'fk_dv_aucs_id');
        $this->addForeignKey('fk-transmittal-fk_dv_aucs_id', 'transmittal_entries', 'fk_dv_aucs_id', 'dv_aucs', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-transmittal-fk_dv_aucs_id', 'transmittal_entries');
        $this->dropIndex('idx-transmittal-fk_dv_aucs_id', 'transmittal_entries');
        $this->dropColumn('transmittal_entries', 'fk_dv_aucs_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230619_085211_add_fk_dv_aucs_id_in_transmittal_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
