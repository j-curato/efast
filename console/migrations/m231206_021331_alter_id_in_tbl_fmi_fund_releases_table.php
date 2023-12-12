<?php

use yii\db\Migration;

/**
 * Class m231206_021331_alter_id_in_tbl_fmi_fund_releases_table
 */
class m231206_021331_alter_id_in_tbl_fmi_fund_releases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tbl_fmi_fund_releases', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231206_021331_alter_id_in_tbl_fmi_fund_releases_table cannot be reverted.\n";

        return false;
    }
    */
}
