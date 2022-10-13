<?php

use yii\db\Migration;

/**
 * Class m221013_013625_remove_property_number_in_par_table
 */
class m221013_013625_remove_property_number_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('par', 'property_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('par', 'property_number', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221013_013625_remove_property_number_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
