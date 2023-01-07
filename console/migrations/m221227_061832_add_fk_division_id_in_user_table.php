<?php

use yii\db\Migration;

/**
 * Class m221227_061832_add_fk_division_id_in_user_table
 */
class m221227_061832_add_fk_division_id_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'fk_division_id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'fk_division_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221227_061832_add_fk_division_id_in_user_table cannot be reverted.\n";

        return false;
    }
    */
}
