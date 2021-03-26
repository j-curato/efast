<?php

use yii\db\Migration;

/**
 * Class m210326_020138_add_process_burs_id_to_raouds_table
 */
class m210326_020138_add_process_burs_id_to_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds','process_burs_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raouds','process_burs_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210326_020138_add_process_burs_id_to_raouds_table cannot be reverted.\n";

        return false;
    }
    */
}
