<?php

use yii\db\Migration;

/**
 * Class m230303_092833_alter_property_table
 */
class m230303_092833_alter_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('property', 'employee_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230303_092833_alter_property_table cannot be reverted.\n";

        return false;
    }
    */
}
