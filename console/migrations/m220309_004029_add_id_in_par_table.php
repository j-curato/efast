<?php

use yii\db\Migration;

/**
 * Class m220309_004029_add_id_in_par_table
 */
class m220309_004029_add_id_in_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('par', 'id', $this->bigInteger()->notNull());
        $this->dropPrimaryKey('PRIMARY', 'par');
        $this->addPrimaryKey('pd-id', 'par', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('PRIMARY', 'par');
        $this->dropColumn('par', 'id',);
        $this->addPrimaryKey('pd-id', 'par', 'par_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220309_004029_add_id_in_par_table cannot be reverted.\n";

        return false;
    }
    */
}
