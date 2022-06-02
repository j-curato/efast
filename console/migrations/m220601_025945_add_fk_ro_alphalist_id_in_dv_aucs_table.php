<?php

use yii\db\Migration;

/**
 * Class m220601_025945_add_fk_ro_alphalist_id_in_dv_aucs_table
 */
class m220601_025945_add_fk_ro_alphalist_id_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs', 'fk_ro_alphalist_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs', 'fk_ro_alphalist_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220601_025945_add_fk_ro_alphalist_id_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
