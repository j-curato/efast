<?php

use yii\db\Migration;

/**
 * Class m210323_053335_add_lvl_to_raoud_entries_table
 */
class m210323_053335_add_lvl_to_raoud_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raoud_entries','lvl',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raoud_entries','lvl');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210323_053335_add_lvl_to_raoud_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
