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
        $this->addColumn('dv_aucs', '1_percent_ewt', $this->float());
        $this->addColumn('dv_aucs', '2_percent_ewt', $this->float());
        $this->addColumn('dv_aucs', '3_percent_ft', $this->float());
        $this->addColumn('dv_aucs', '5_percent_ft', $this->float());
        $this->addColumn('dv_aucs', '5_percent_ewt', $this->float());
        $this->addColumn('dv_aucs', 'total_withheld', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs', '1_percent_ewt');
        $this->dropColumn('dv_aucs', '2_percent_ewt');
        $this->dropColumn('dv_aucs', '3_percent_ft');
        $this->dropColumn('dv_aucs', '5_percent_ft');
        $this->dropColumn('dv_aucs', '5_percent_ewt');
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
