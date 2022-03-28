<?php

use yii\db\Migration;

/**
 * Class m220322_034429_add_id_and_fk_par_id_in_ptr_table
 */
class m220322_034429_add_id_and_fk_par_id_in_ptr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ptr}}', 'id', $this->bigInteger()->after('ptr_number'));
        $this->addColumn('{{%ptr}}', 'fk_par_id', $this->bigInteger()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ptr}}', 'id');
        $this->dropColumn('{{%ptr}}', 'fk_par_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220322_034429_add_id_and_fk_par_id_in_ptr_table cannot be reverted.\n";

        return false;
    }
    */
}
