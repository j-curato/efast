<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_status}}`.
 */
class m230313_064949_create_property_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_status}}', [
            'id' => $this->primaryKey(),
            'status' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_status}}');
    }
}
