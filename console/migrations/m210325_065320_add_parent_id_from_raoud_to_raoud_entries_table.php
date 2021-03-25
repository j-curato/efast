<?php

use yii\db\Migration;

/**
 * Class m210325_065320_add_parent_id_from_raoud_to_raoud_entries_table
 */
class m210325_065320_add_parent_id_from_raoud_to_raoud_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raoud_entries','parent_id_from_raoud',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raoud_entries','parent-id_from_raoud');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210325_065320_add_parent_id_from_raoud_to_raoud_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
