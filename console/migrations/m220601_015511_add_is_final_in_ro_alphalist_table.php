<?php

use yii\db\Migration;

/**
 * Class m220601_015511_add_is_final_in_ro_alphalist_table
 */
class m220601_015511_add_is_final_in_ro_alphalist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_alphalist','is_final',$this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ro_alphalist','is_final');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220601_015511_add_is_final_in_ro_alphalist_table cannot be reverted.\n";

        return false;
    }
    */
}
