<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%citymun}}`.
 */
class m230227_005300_create_citymun_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%citymun}}', [
            'id' => $this->primaryKey(),
            'city_mun' => $this->string()->notNull(),
            'fk_office_id' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx-fk_office_id', 'citymun', 'fk_office_id');
        $this->addForeignKey('fk-citymun-fk_office_id', 'citymun', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-citymun-fk_office_id', 'citymun');
        $this->dropIndex('idx-fk_office_id', 'citymun');
        $this->dropTable('{{%citymun}}');
    }
}
