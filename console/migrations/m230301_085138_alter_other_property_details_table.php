<?php

use yii\db\Migration;

/**
 * Class m230301_085138_alter_other_property_details_table
 */
class m230301_085138_alter_other_property_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('other_property_details', 'first_month_depreciation');
        $this->dropColumn('other_property_details', 'start_month_depreciation');
        $this->dropColumn('other_property_details', 'depreciation_schedule');
        $this->addColumn('other_property_details', 'useful_life', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('other_property_details', 'first_month_depreciation', $this->string());
        $this->addColumn('other_property_details', 'start_month_depreciation', $this->string());
        $this->addColumn('other_property_details', 'depreciation_schedule', $this->string());
        $this->dropColumn('other_property_details', 'useful_life');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230301_085138_alter_other_property_details_table cannot be reverted.\n";

        return false;
    }
    */
}
