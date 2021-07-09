<?php

use yii\db\Migration;

/**
 * Class m210709_024827_add_dv_link_in_dv_aucs_table
 */
class m210709_024827_add_dv_link_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','dv_link',$this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','dv_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210709_024827_add_dv_link_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
