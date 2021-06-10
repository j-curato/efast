<?php

use yii\db\Migration;

/**
 * Class m210512_031737_add_created_at_in_advances_table
 */
class m210512_031737_add_created_at_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210512_031737_add_created_at_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
