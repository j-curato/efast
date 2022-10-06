<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ppe_useful_life}}`.
 */
class m221006_023921_create_ppe_useful_life_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ppe_useful_life}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull(),
            'life_from' => $this->integer(),
            'life_to' => $this->integer(),
            'life_description' => $this->text(),
            'type' => $this->string()->defaultValue('with_life')->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ppe_useful_life}}');
    }
}
