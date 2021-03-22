<?php

use yii\db\Migration;

/**
 * Class m210322_082348_add_obligated_amount_to_raouds_table
 */
class m210322_082348_add_obligated_amount_to_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds','obligated_amount',$this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raouds','obligated_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210322_082348_add_obligated_amount_to_raouds_table cannot be reverted.\n";

        return false;
    }
    */
}
