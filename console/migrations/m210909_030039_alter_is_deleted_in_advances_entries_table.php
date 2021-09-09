<?php

use yii\db\Migration;

/**
 * Class m210909_030039_alter_is_deleted_in_advances_entries_table
 */
class m210909_030039_alter_is_deleted_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('advances_entries','is_deleted',$this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('advances_entries','is_deleted',$this->boolean());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210909_030039_alter_is_deleted_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
