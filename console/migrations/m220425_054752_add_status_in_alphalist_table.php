<?php

use yii\db\Migration;

/**
 * Class m220425_054752_add_status_in_alphalist_table
 */
class m220425_054752_add_status_in_alphalist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('alphalist', 'status', $this->integer()->defaultValue(9));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('alphalist', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220425_054752_add_status_in_alphalist_table cannot be reverted.\n";

        return false;
    }
    */
}
