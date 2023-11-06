<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%municipalities}}`.
 */
class m231103_063937_create_municipalities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%municipalities}}', [
            'id' => $this->primaryKey(),
            'fk_province_id' => $this->integer()->notNull(),
            'municipality_name' => $this->string()->notNull()
        ]);
        $this->createIndex('idx-municipalities-fk_province_id', 'municipalities', 'fk_province_id');
        $this->addForeignKey('fk-municipalities-fk_province_id', 'municipalities', 'fk_province_id', 'provinces', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-municipalities-fk_province_id', 'municipalities');
        $this->dropIndex('idx-municipalities-fk_province_id', 'municipalities');
        $this->dropTable('{{%municipalities}}');
    }
}
