<?php

use yii\db\Migration;

/**
 * Class m230906_073400_add_isMaf_in_record_allotments_table
 */
class m230906_073400_add_isMaf_in_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments', 'isMaf', $this->boolean()->defaultValue(false));
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
        echo "m230906_073400_add_isMaf_in_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
