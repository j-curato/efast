<?php

use yii\db\Migration;

/**
 * Class m210131_140242_create_table_financing_source_code
 */
class m210131_140242_create_table_financing_source_code extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%financing_source_code}}', [
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
        
        $this->dropTable('{{%financing_source_code}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_140242_create_table_financing_source_code cannot be reverted.\n";

        return false;
    }
    */
}
