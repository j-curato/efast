<?php

use yii\db\Migration;

/**
 * Class m210920_025312_add_division_in_usert_table
 */
class m210920_025312_add_division_in_usert_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'division', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'division');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210920_025312_add_division_in_usert_table cannot be reverted.\n";

        return false;
    }
    */
}
