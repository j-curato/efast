<?php

use yii\db\Migration;

/**
 * Class m230412_004231_alter_rpccpe_table
 */
class m230412_004231_alter_rpccpe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('rpcppe', 'id', $this->bigInteger()->after('reporting_period'));
        $this->alterColumn('rpcppe', 'reporting_period', $this->string()->after('id'));
        $this->dropPrimaryKey('PRIMARY', 'rpcppe');
        $this->addPrimaryKey('pk_id', 'rpcppe', 'id');
        $this->dropColumn('rpcppe', 'rpcppe_number');
        $this->dropColumn('rpcppe', 'ppe_condition_id');
        $this->renameColumn('rpcppe', 'book_id', 'fk_book_id');
        $this->addColumn('rpcppe', 'fk_chart_of_account_id', $this->integer());
        $this->addColumn('rpcppe', 'fk_actbl_ofr', $this->bigInteger());

        $this->addColumn('rpcppe','fk_office_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('rpcppe', 'rpcppe_number', $this->string());
        $this->addColumn('rpcppe', 'ppe_condition_id', $this->string());
        $this->dropPrimaryKey('pk_id', 'rpcppe');
        $this->addPrimaryKey('pk_rpcppe_number', 'rpcppe', 'rpcppe_number');
        $this->dropColumn('rpcppe', 'id');
        $this->dropColumn('rpcppe', 'fk_chart_of_account_id');
        $this->dropColumn('rpcppe', 'fk_actbl_ofr');
        $this->renameColumn('rpcppe', 'fk_book_id', 'book_id');
        $this->dropColumn('rpcppe','fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230412_004231_alter_rpccpe_table cannot be reverted.\n";

        return false;
    }
    */
}
