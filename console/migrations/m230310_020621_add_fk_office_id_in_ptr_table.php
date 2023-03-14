<?php

use yii\db\Migration;

/**
 * Class m230310_020621_add_fk_office_id_in_ptr_table
 */
class m230310_020621_add_fk_office_id_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ptr', 'fk_office_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('ptr', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230310_020621_add_fk_office_id_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
