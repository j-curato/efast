<?php

use yii\db\Migration;

/**
 * Class m220310_024736_add_fk_actual_user_in_par_table
 */
class m220310_024736_add_fk_actual_user_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'actual_user', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('par', 'actual_user',);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220310_024736_add_fk_actual_user_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
