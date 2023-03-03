<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m230227_083847_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%location}}', [
            'id' => $this->primaryKey(),
            'location' => $this->string()->notNull(),
            'is_nc' => $this->boolean()->defaultValue(0)->notNull(),
            'fk_division_id' => $this->bigInteger()->notNull(),
            'fk_office_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->addForeignKey('fk-loc-fk_division_id', 'location', 'fk_division_id', 'divisions', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk-loc-fk_office_id', 'location', 'fk_office_id', 'office', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-loc-fk_division_id', 'location');
        $this->dropForeignKey('fk-loc-fk_office_id', 'location');
        $this->dropTable('{{%location}}');
    }
}
