<?php

use yii\db\Migration;

/**
 * Class m210131_140332_create_table_authorization_code
 */
class m210131_140332_create_table_authorization_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%authorization_code}}', [
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
         
        $this->dropTable('{{%authorization_code}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_140332_create_table_authorization_code cannot be reverted.\n";

        return false;
    }
    */
}
