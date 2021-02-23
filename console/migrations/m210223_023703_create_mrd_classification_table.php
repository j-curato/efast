<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mrd_classification}}`.
 */
class m210223_023703_create_mrd_classification_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mrd_classification}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(255),
     
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mrd_classification}}');
    }
}
