<?php

use yii\db\Migration;

/**
 * Class m240126_032611_add_attributes_in_dv_aucs_entries_table
 */
class m240126_032611_add_attributes_in_dv_aucs_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs_entries', 'liquidation_damage', $this->decimal(10, 2)->defaultValue(0));
        $this->addColumn('dv_aucs_entries', 'tax_portion_of_post', $this->decimal(10, 2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs_entries', 'liquidation_damage');
        $this->dropColumn('dv_aucs_entries', 'tax_portion_of_post');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240126_032611_add_attributes_in_dv_aucs_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
