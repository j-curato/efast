<?php

use yii\db\Migration;

/**
 * Class m220419_083155_add_fk_property_id_in_par_table
 */
class m220419_083155_add_fk_property_id_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'fk_property_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropColumn('par', 'fk_property_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_083155_add_fk_property_id_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
