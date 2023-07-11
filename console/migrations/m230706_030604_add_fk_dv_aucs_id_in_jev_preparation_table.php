<?php

use yii\db\Migration;

/**
 * Class m230706_030604_add_fk_dv_aucs_id_in_jev_preparation_table
 */
class m230706_030604_add_fk_dv_aucs_id_in_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation', 'fk_dv_aucs_id', $this->integer());
        $this->createIndex('idx-jev-fk_dv_aucs_id', 'jev_preparation', 'fk_dv_aucs_id');
        $this->addForeignKey('fk-jev-fk_dv_aucs_id', 'jev_preparation', 'fk_dv_aucs_id', 'dv_aucs', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jev-fk_dv_aucs_id', 'jev_preparation');
        $this->dropIndex('idx-jev-fk_dv_aucs_id', 'jev_preparation');
        $this->dropColumn('jev_preparation', 'fk_dv_aucs_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230706_030604_add_fk_dv_aucs_id_in_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
