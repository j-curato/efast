<?php

use yii\db\Migration;

/**
 * Class m210510_031255_add_type_in_process_ors_table
 */
class m210510_031255_add_type_in_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_ors', 'type', $this->string(10)->defaultValue('ors'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_ors', 'type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210510_031255_add_type_in_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
