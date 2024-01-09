<?php

use yii\db\Migration;

/**
 * Class m240108_064110_add_constraints_in_liquidation_entries_table
 */
class m240108_064110_add_constraints_in_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->createIndex('idx-liquidation_entries-new_chart_of_account_id', 'liquidation_entries', 'new_chart_of_account_id');
        $this->addForeignKey(
            'fk-liquidation_entries-new_chart_of_account_id',
            'liquidation_entries',
            'new_chart_of_account_id',
            'chart_of_accounts',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-liquidation_entries-new_chart_of_account_id',
            'liquidation_entries',

        );
        $this->dropIndex('idx-liquidation_entries-new_chart_of_account_id', 'liquidation_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240108_064110_add_constraints_in_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
