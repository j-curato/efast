<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%password}}`.
 */
class m210711_114958_create_password_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%password}}', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(),
            'password'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%password}}');
    }
}
