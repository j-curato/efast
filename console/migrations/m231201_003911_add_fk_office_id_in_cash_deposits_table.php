<?php

use yii\db\Migration;

/**
 * Class m231201_003911_add_fk_office_id_in_cash_deposits_table
 */
class m231201_003911_add_fk_office_id_in_cash_deposits_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_deposits', 'fk_office_id', $this->integer());
        $this->createIndex('idx-cash_deposits-fk_office_id', 'cash_deposits', 'fk_office_id');
        $this->addForeignKey(
            'fk-cash_deposits-fk_office_id',
            'cash_deposits',
            'fk_office_id',
            'office',
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
            'fk-cash_deposits-fk_office_id',
            'cash_deposits'
        );
        $this->dropIndex('idx-cash_deposits-fk_office_id', 'cash_deposits');
        $this->dropColumn('cash_deposits', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231201_003911_add_fk_office_id_in_cash_deposits_table cannot be reverted.\n";

        return false;
    }
    */
}
