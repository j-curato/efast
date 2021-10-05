<?php

use yii\db\Migration;

/**
 * Class m211005_065545_add_division_in_mfo_pap_code_table
 */
class m211001_070303_add_division_in_mfo_pap_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('mfo_pap_code', 'division', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('mfo_pap_code', 'division');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211005_065545_add_division_in_mfo_pap_code_table cannot be reverted.\n";

        return false;
    }
    */
}
