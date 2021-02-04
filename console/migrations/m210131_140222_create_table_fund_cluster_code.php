<?php

use yii\db\Migration;

/**
 * Class m210131_140222_create_table_fund_cluster_code
 */
class m210131_140222_create_table_fund_cluster_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
                
        $this->createTable('{{%fund_cluster_code}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
           
        ]);
    
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fund_cluster_code}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_140222_create_table_fund_cluster_code cannot be reverted.\n";

        return false;
    }
    */
}
