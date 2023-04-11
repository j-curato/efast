<?php

use yii\db\Migration;

/**
 * Class m230403_013501_update_user_table
 */
class m230403_013501_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->alterColumn('user', 'id', $this->bigInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230403_013501_update_user_table cannot be reverted.\n";

        return false;
    }
    */
}
