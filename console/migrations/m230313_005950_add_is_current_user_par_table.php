<?php

use yii\db\Migration;

/**
 * Class m230313_005950_add_is_current_user_par_table
 */
class m230313_005950_add_is_current_user_par_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('par', 'is_current_user', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('par', 'is_current_user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230313_005950_add_is_current_user_par_table cannot be reverted.\n";

        return false;
    }
    */
}
