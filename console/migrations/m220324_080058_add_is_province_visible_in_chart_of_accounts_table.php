<?php

use yii\db\Migration;

/**
 * Class m220324_080058_add_is_province_visible_in_chart_of_accounts_table
 */
class m220324_080058_add_is_province_visible_in_chart_of_accounts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('chart_of_accounts','is_province_visible',$this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('chart_of_accounts','is_province_visible');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220324_080058_add_is_province_visible_in_chart_of_accounts_table cannot be reverted.\n";

        return false;
    }
    */
}
