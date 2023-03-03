<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ssf_sp_status}}`.
 */
class m230227_021017_create_ssf_sp_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ssf_sp_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ssf_sp_status}}');
    }
}
