<?php

use yii\db\Migration;

/**
 * Class m220419_085158_remove_par_number_constraint_in_property_card_table
 */
class m220419_085158_remove_par_number_constraint_in_property_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // echo "m220419_085158_remove_par_number_constraint_in_property_card_table cannot be reverted.\n";

        // return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_085158_remove_par_number_constraint_in_property_card_table cannot be reverted.\n";

        return false;
    }
    */
}
