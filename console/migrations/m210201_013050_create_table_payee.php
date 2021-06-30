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
            'registered_name' => $this->string(255)->null(),
            'contact_person' => $this->string(255)->null(),
            'registered_address' => $this->string(255)->null(),
            'contact' => $this->string(20)->null(),
            'remark' => $this->string(255)->null(),
            'tin_number' => $this->string(30)->null(),
           
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

