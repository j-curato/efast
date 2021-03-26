<?php

use yii\db\Migration;

/**
 * Class m210326_020900_add_burs_amount_to_raouds_table
 */
class m210326_020900_add_burs_amount_to_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('raouds', 'burs_amount', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('raouds', 'burs_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210326_020900_add_burs_amount_to_raouds_table cannot be reverted.\n";

        return false;
    }
    */
}
