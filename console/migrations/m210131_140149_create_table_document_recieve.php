<?php

use yii\db\Migration;

/**
 * Class m210131_140149_create_table_document_recieve
 */
class m210131_140149_create_table_document_recieve extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->createTable('{{%document_recieve}}', [
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
        $this->dropTable('{{%document_recieve}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_140149_create_table_document_recieve cannot be reverted.\n";

        return false;
    }
    */
}
