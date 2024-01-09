<?php

use yii\db\Migration;

/**
 * Class m240103_013944_add_is_deleted_in_dv_accounting_entries_table
 */
class m240103_013944_add_is_deleted_in_dv_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_accounting_entries', 'is_deleted', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_accounting_entries', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240103_013944_add_is_deleted_in_dv_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
