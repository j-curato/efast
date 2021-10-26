<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_card}}`.
 */
class m211025_061419_create_property_card_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_card}}', [
            'pc_number' => $this->string(),
            'balance' => $this->decimal(10, 2),
            'par_number' => $this->string(),
            'ptr_number' => $this->string()
        ]);
        $this->addPrimaryKey('pk-pc-number', 'property_card', 'pc_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_card}}');
    }
}
