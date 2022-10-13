<?php

use yii\db\Migration;

/**
 * Class m221013_011515_alter_date_column_in_par_table
 */
class m221013_011515_alter_date_column_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('par', 'date', $this->string());
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
        echo "m221013_011515_alter_date_column_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
