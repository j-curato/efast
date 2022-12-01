<?php

use yii\db\Migration;

/**
 * Class m221201_020439_add_is_deleted_in_dv_aucs_entries_table
 */
class m221201_020439_add_is_deleted_in_dv_aucs_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs_entries', 'is_deleted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs_entries', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221201_020439_add_is_deleted_in_dv_aucs_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
