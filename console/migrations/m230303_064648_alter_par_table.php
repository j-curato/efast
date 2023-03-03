<?php

use yii\db\Migration;

/**
 * Class m230303_064648_alter_par_table
 */
class m230303_064648_alter_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('par', 'id', $this->bigInteger()->after('par_number'));
        $this->alterColumn('par', 'par_number', $this->string()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230303_064648_alter_par_table cannot be reverted.\n";

        return false;
    }
    */
}
