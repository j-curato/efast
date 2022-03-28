<?php

use yii\db\Migration;

/**
 * Class m220322_052448_add_object_code_salvage_value_estimated_life_in_property_table
 */
class m220322_052448_add_object_code_salvage_value_estimated_life_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%property}}', 'object_code', $this->string());
        $this->addColumn('{{%property}}', 'salvage_value', $this->decimal(10, 2));
        $this->addColumn('{{%property}}', 'estimated_life', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%property}}', 'object_code' );
        $this->dropColumn('{{%property}}', 'salvage_value');
        $this->dropColumn('{{%property}}', 'estimated_life' );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220322_052448_add_object_code_salvage_value_estimated_life_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
