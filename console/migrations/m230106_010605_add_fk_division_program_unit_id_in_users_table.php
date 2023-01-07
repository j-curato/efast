<?php

use yii\db\Migration;

/**
 * Class m230106_010605_add_fk_division_program_unit_id_in_users_table
 */
class m230106_010605_add_fk_division_program_unit_id_in_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'fk_division_program_unit', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'fk_division_program_unit');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230106_010605_add_fk_division_program_unit_id_in_users_table cannot be reverted.\n";

        return false;
    }
    */
}
