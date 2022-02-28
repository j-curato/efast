<?php

use yii\db\Migration;

/**
 * Class m220228_055101_add_entry_type_in_jev_preparation_table
 */
class m220228_055101_add_entry_type_in_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_preparation', 'entry_type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_preparation', 'entry_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220228_055101_add_entry_type_in_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
