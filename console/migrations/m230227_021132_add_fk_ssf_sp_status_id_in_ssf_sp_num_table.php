<?php

use yii\db\Migration;

/**
 * Class m230227_021132_add_fk_ssf_sp_status_id_in_ssf_sp_num_table
 */
class m230227_021132_add_fk_ssf_sp_status_id_in_ssf_sp_num_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ssf_sp_num', 'fk_ssf_sp_status_id', $this->integer()->notNull());
        $this->createIndex('idx-fk_ssf_sp_status_id', 'ssf_sp_num', 'fk_ssf_sp_status_id');
        $this->addForeignKey('fk-ssfSpNum-fk_ssf_sp_status_id', 'ssf_sp_num', 'fk_ssf_sp_status_id', 'ssf_sp_status', 'id', 'RESTRICT');
        $this->dropColumn('ssf_sp_num', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-ssfSpNum-fk_ssf_sp_status_id', 'ssf_sp_num');
        $this->dropIndex('idx-fk_ssf_sp_status_id', 'ssf_sp_num');
        $this->dropColumn('ssf_sp_num', 'fk_ssf_sp_status_id');
        $this->addColumn('ssf_sp_num', 'status', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230227_021132_add_fk_ssf_sp_status_id_in_ssf_sp_num_table cannot be reverted.\n";

        return false;
    }
    */
}
