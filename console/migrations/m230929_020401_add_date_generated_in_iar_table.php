<?php

use yii\db\Migration;

/**
 * Class m230929_020401_add_date_generated_in_iar_table
 */
class m230929_020401_add_date_generated_in_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('iar', 'date_generated', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('iar', 'date_generated');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230929_020401_add_date_generated_in_iar_table cannot be reverted.\n";

        return false;
    }
    */
}
