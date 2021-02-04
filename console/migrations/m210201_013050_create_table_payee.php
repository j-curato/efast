<?php

use yii\db\Migration;

/**
 * Class m210201_013050_create_table_payee
 */
class m210201_013050_create_table_payee extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payee}}', [
            'id' => $this->primaryKey(),
            'account_name' => $this->string(255)->notNull(),
            'registered_name' => $this->string(255)->notNull(),
            'contact_person' => $this->string(255)->notNull(),
            'registered_address' => $this->string(255)->notNull(),
            'contact' => $this->string(20)->notNull(),
            'remark' => $this->string(255)->notNull(),
            'tin_number' => $this->string(30)->notNull(),
           
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payee}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210201_013050_create_table_payee cannot be reverted.\n";

        return false;
    }
    */
}

