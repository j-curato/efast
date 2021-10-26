<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agency}}`.
 */
class m211025_020758_create_agency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%agency}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(),
            'type'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%agency}}');
    }
}
