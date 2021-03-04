<?php

use yii\db\Migration;

/**
 * Class m210301_025238_add_net_asset_id_to_jev_accounting_entries_table
 */
class m210301_025238_add_net_asset_id_to_jev_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_accounting_entries', 'net_asset_equity_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_accounting_entries', 'net_asset_equity_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210301_025238_add_net_asset_id_to_jev_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
