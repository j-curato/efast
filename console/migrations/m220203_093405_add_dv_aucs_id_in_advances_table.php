<?php

use yii\db\Migration;

/**
 * Class m220203_093405_add_dv_aucs_id_in_advances_table
 */
class m220203_093405_add_dv_aucs_id_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('advances', 'dv_aucs_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances', 'dv_aucs_id');
    }
}
