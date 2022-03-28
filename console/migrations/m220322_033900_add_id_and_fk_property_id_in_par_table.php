<?php

use yii\db\Migration;

/**
 * Class m220322_033900_add_id_and_fk_property_id_in_par_table
 */
class m220322_033900_add_id_and_fk_property_id_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'id', $this->bigInteger()->after('par_number'));
        $this->addColumn('par', 'fk_property_id', $this->bigInteger()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('par', 'id');
        $this->dropColumn('par', 'fk_property_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220322_033900_add_id_and_fk_property_id_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
