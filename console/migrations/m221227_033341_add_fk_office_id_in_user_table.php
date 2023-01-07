<?php

use yii\db\Migration;

/**
 * Class m221227_033341_add_fk_office_id_in_user_table
 */
class m221227_033341_add_fk_office_id_in_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'fk_office_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221227_033341_add_fk_office_id_in_user_table cannot be reverted.\n";

        return false;
    }
    */
}
