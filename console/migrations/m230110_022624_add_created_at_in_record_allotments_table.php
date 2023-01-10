<?php

use yii\db\Migration;

/**
 * Class m230110_022624_add_created_at_in_record_allotments_table
 */
class m230110_022624_add_created_at_in_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
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
        echo "m230110_022624_add_created_at_in_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
