<?php

use yii\db\Migration;

/**
 * Class m230515_015521_rename_table_ro_check_range
 */
class m230515_015521_rename_table_ro_check_range extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('ro_check_range', 'ro_check_ranges');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('ro_check_ranges', 'ro_check_range');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230515_015521_rename_table_ro_check_range cannot be reverted.\n";

        return false;
    }
    */
}
