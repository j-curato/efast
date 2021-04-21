<?php

use yii\db\Migration;

/**
 * Class m210405_005233_add_tax_percents_to_dv_aucs_table
 */
class m210405_005233_add_tax_percents_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs', 'one_percent_ewt', $this->decimal(10,2));
        $this->addColumn('dv_aucs', 'two_percent_ewt', $this->decimal(10,2));
        $this->addColumn('dv_aucs', 'three_percent_ft', $this->decimal(10,2));
        $this->addColumn('dv_aucs', 'five_percent_ft', $this->decimal(10,2));
        $this->addColumn('dv_aucs', 'five_percent_ewt', $this->decimal(10,2));
        $this->addColumn('dv_aucs', 'total_withheld', $this->decimal(10,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs', 'one_percent_ewt');
        $this->dropColumn('dv_aucs', 'two_percent_ewt');
        $this->dropColumn('dv_aucs', 'three_percent_ft');
        $this->dropColumn('dv_aucs', 'five_percent_ft');
        $this->dropColumn('dv_aucs', 'five_percent_ewt');
        $this->dropColumn('dv_aucs', 'total_withheld');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210405_005233_add_tax_percents_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
