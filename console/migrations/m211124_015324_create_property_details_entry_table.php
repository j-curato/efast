<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%property_details_entry}}`.
 */
class m211124_015324_create_property_details_entry_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%property_details_entry}}', [
            'id' => $this->primaryKey(),
            'property_details_id' => $this->integer(),
            'object_code' => $this->string(50),
            'first_month' => $this->string(20),
            'last_month' => $this->string(20),
            'salvage_value' => $this->decimal(15, 2),
            'monthly_depreciation' => $this->decimal(15, 2),
            'estimated_useful_life' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%property_details_entry}}');
    }
}
