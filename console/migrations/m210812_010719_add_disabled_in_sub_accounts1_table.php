<?php

use yii\db\Migration;

/**
 * Class m210812_010719_add_disabled_in_sub_accounts1_table
 */
class m210812_010719_add_disabled_in_sub_accounts1_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sub_accounts1','is_active',$this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sub_accounts1','is_active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210812_010719_add_disabled_in_sub_accounts1_table cannot be reverted.\n";

        return false;
    }
    */
}
