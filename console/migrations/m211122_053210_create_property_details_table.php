<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_details}}`.
 */
class m211122_053210_create_property_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_details}}', [
            'id' => $this->primaryKey(),
            'property_number'=>$this->string(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_details}}');
    }
}
