<?php

use yii\db\Migration;

/**
 * Class m210812_010737_add_disabled_in_sub_accounts2_table
 */
class m210812_010737_add_disabled_in_sub_accounts2_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sub_accounts2','is_active',$this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sub_accounts2','is_active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210812_010737_add_disabled_in_sub_accounts2_table cannot be reverted.\n";

        return false;
    }
    */
}
