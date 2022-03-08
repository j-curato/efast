<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pr_mode_of_procurement}}`.
 */
class m220303_064957_create_pr_mode_of_procurement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pr_mode_of_procurement}}', [
            'id' => $this->primaryKey(),
            'mode_name'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pr_mode_of_procurement}}');
    }
}
