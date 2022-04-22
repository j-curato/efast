<?php

use yii\db\Migration;

/**
 * Class m220420_010422_add_fk_alphalist_id_on_liquidation_entries_table
 */
class m220420_010422_add_fk_alphalist_id_on_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries', 'fk_alphalist_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_entries', 'fk_alphalist_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220420_010422_add_fk_alphalist_id_on_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
