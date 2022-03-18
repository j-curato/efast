<?php

use yii\db\Migration;

/**
 * Class m220317_063002_add_created_at_in_pr_iar_table
 */
class m220317_063002_add_created_at_in_pr_iar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_iar','created_at',$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_iar','created_at');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220317_063002_add_created_at_in_pr_iar_table cannot be reverted.\n";

        return false;
    }
    */
}
