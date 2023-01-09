<?php

use yii\db\Migration;

/**
 * Class m230109_063045_add_fk_mfo_pap_id_in_division_program_unit
 */
class m230109_063045_add_fk_mfo_pap_id_in_division_program_unit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('division_program_unit', 'fk_mfo_pap_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('division_program_unit', 'fk_mfo_pap_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230109_063045_add_fk_mfo_pap_id_in_division_program_unit cannot be reverted.\n";

        return false;
    }
    */
}
