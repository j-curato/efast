<?php

use yii\db\Migration;

/**
 * Class m230227_081228_add_index_in_property_table
 */
class m230227_081228_add_index_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-property_number', 'property', 'property_number', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-property_number', 'property');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230227_081228_add_index_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
