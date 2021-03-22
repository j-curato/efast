<?php

use yii\db\Migration;

/**
 * Class m210322_065608_add_timestamp_to_jev_preparation_table
 */
class m210322_065608_add_timestamp_to_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
     $this->dropColumn('jev_preparation','created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210322_065608_add_timestamp_to_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
