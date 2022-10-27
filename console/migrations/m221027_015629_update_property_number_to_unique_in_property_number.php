<?php

use yii\db\Migration;

/**
 * Class m221027_015629_update_property_number_to_unique_in_property_number
 */
class m221027_015629_update_property_number_to_unique_in_property_number extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('property', 'property_number', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('property', 'property_number', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221027_015629_update_property_number_to_unique_in_property_number cannot be reverted.\n";

        return false;
    }
    */
}
