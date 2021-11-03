<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transfer_type}}`.
 */
class m211027_061822_create_transfer_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transfer_type}}', [
            'id' => $this->primaryKey(),
            'type'=>$this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transfer_type}}');
    }
}
