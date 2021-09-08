<?php

use yii\db\Migration;

/**
 * Class m210908_015901_add_is_deleted_on_advances_entries_table
 */
class m210908_015901_add_is_deleted_on_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries','is_deleted',$this->boolean()->defaultValue(false)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries','is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210908_015901_add_is_deleted_on_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
