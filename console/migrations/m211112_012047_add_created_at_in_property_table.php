<?php

use yii\db\Migration;

/**
 * Class m211112_012047_add_created_at_in_property_table
 */
class m211112_012047_add_created_at_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('property','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('property','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211112_012047_add_created_at_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}
