<?php

use yii\db\Migration;

/**
 * Class m220816_083540_add_fk_end_user_in_iar_table
 */
class m220816_083540_add_fk_end_user_in_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('iar', 'fk_end_user', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('iar', 'fk_end_user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220816_083540_add_fk_end_user_in_iar_table cannot be reverted.\n";

        return false;
    }
    */
}
