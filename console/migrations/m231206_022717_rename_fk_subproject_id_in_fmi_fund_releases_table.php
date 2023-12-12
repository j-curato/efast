<?php

use yii\db\Migration;

/**
 * Class m231206_022717_rename_fk_subproject_id_in_fmi_fund_releases_table
 */
class m231206_022717_rename_fk_subproject_id_in_fmi_fund_releases_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('tbl_fmi_fund_releases', 'fk_subproject_id', 'fk_fmi_subproject_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('tbl_fmi_fund_releases', 'fk_fmi_subproject_id', 'fk_subproject_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231206_022717_rename_fk_subproject_id_in_fmi_fund_releases_table cannot be reverted.\n";

        return false;
    }
    */
}
