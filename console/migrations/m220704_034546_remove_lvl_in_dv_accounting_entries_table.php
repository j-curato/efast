<?php

use yii\db\Migration;

/**
 * Class m220704_034546_remove_lvl_in_dv_accounting_entries_table
 */
class m220704_034546_remove_lvl_in_dv_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('dv_accounting_entries', 'lvl');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('dv_accounting_entries', 'lvl', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220704_034546_remove_lvl_in_dv_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
