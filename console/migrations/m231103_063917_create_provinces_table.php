<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%provinces}}`.
 */
class m231103_063917_create_provinces_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%provinces}}', [
            'id' => $this->primaryKey(),
            'fk_region_id' => $this->integer()->notNull(),
            'province_name' => $this->string()->notNull()
        ]);
        $this->createIndex('idx-provinces-fk_region_id', 'provinces', 'fk_region_id');
        $this->addForeignKey('fk-provinces-fk_region_id', 'provinces', 'fk_region_id', 'regions', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-provinces-fk_region_id', 'provinces');
        $this->dropIndex('idx-provinces-fk_region_id', 'provinces');
        $this->dropTable('{{%provinces}}');
    }
}
