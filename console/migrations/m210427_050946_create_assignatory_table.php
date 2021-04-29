<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%assignatory}}`.
 */
class m210427_050946_create_assignatory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%assignatory}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'position'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%assignatory}}');
    }
}
