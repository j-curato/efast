<?php

use yii\db\Migration;

/**
 * Class m210428_070503_add_is_cancelled_to_process_ors_table
 */
class m210428_070503_add_is_cancelled_to_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_ors', 'is_cancelled', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_ors','is_cancelled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210428_070503_add_is_cancelled_to_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
