<?php

use yii\db\Migration;

/**
 * Class m220310_054311_add_description_on_property_table
 */
class m220310_054311_add_description_on_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('property', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220310_054311_add_description_on_property_table cannot be reverted.\n";

        return false;
    }
    */
}
