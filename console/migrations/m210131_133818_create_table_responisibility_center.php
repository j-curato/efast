<?php

use yii\db\Migration;

/**
 * Class m210131_133818_create_table_responisibility_center
 */
class m210131_133818_create_table_responisibility_center extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%responsibility_center}}', [
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
        $this->dropTable('{{%responsibility_center}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210131_133818_create_table_responisibility_center cannot be reverted.\n";

        return false;
    }
    */
}
