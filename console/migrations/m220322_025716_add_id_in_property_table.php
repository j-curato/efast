<?php

use yii\db\Migration;

/**
 * Class m220322_025716_add_id_in_property_table
 */
class m220322_025716_add_id_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->addPrimaryKey('pk-property_number', 'property', 'property_number');
        $this->addColumn('{{%property}}', 'id', $this->bigInteger()->after('property_number'));
        $this->dropPrimaryKey('PRIMARY', '{{%property}}');
        $this->addPrimaryKey('pk-id', 'property', 'id');
        $this->alterColumn('{{%property}}', 'id', $this->bigInteger(8) . ' NOT NULL AUTO_INCREMENT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('PRIMARY', '{{%property}}');
        $this->addPrimaryKey('pk-id', 'property', 'property_number');
        $this->dropColumn('property', 'id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220322_025716_add_id_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
