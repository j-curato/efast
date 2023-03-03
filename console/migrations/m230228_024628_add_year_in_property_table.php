<?php

use yii\db\Migration;

/**
 * Class m230228_024628_add_year_in_property_table
 */
class m230228_024628_add_year_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property', 'ppe_year', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('property', 'ppe_year');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230228_024628_add_year_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
