<?php

use yii\db\Migration;

/**
 * Class m220211_050732_add_is_final_in_ro_rao_table
 */
class m220211_050732_add_is_final_in_ro_rao_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_rao', 'is_final', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ro_rao', 'is_final');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220211_050732_add_is_final_in_ro_rao_table cannot be reverted.\n";

        return false;
    }
    */
}
