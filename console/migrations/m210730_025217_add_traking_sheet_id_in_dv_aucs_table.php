<?php

use yii\db\Migration;

/**
 * Class m210730_025217_add_traking_sheet_id_in_dv_aucs_table
 */
class m210730_025217_add_traking_sheet_id_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','tracking_sheet_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','tracking_sheet');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210730_025217_add_traking_sheet_id_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
