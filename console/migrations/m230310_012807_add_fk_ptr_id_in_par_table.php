<?php

use yii\db\Migration;

/**
 * Class m230310_012807_add_fk_ptr_id_in_par_table
 */
class m230310_012807_add_fk_ptr_id_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'fk_ptr_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('par', 'fk_ptr_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230310_012807_add_fk_ptr_id_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
