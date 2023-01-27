<?php

use yii\db\Migration;

/**
 * Class m230127_054743_add_fk_fund_source_type_id_in_advances_entries_table
 */
class m230127_054743_add_fk_fund_source_type_id_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries', 'fk_fund_source_type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries', 'fk_fund_source_type_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230127_054743_add_fk_fund_source_type_id_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
