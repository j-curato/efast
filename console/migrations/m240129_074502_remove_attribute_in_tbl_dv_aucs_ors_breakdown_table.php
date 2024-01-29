<?php

use yii\db\Migration;

/**
 * Class m240129_074502_remove_attribute_in_tbl_dv_aucs_ors_breakdown_table
 */
class m240129_074502_remove_attribute_in_tbl_dv_aucs_ors_breakdown_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('tbl_dv_aucs_ors_breakdown', 'total_withheld');
        $this->dropColumn('tbl_dv_aucs_ors_breakdown', 'process_ors_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('tbl_dv_aucs_ors_breakdown', 'total_withheld', $this->integer());
        $this->addColumn('tbl_dv_aucs_ors_breakdown', 'process_ors_id', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240129_074502_remove_attribute_in_tbl_dv_aucs_ors_breakdown_table cannot be reverted.\n";

        return false;
    }
    */
}
