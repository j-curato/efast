<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%barangays}}`.
 */
class m231103_063956_create_barangays_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%barangays}}', [
            'id' => $this->primaryKey(),
            'fk_municipality_id' => $this->integer()->notNull(),
            'barangay_name' => $this->string()->notNull()
        ]);
        $this->createIndex('idx-barangays-fk_municipality_id', 'barangays', 'fk_municipality_id');
        $this->addForeignKey('fk-barangays-fk_municipality_id', 'barangays', 'fk_municipality_id', 'municipalities', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-barangays-fk_municipality_id', 'barangays');
        $this->dropIndex('idx-barangays-fk_municipality_id', 'barangays');
        $this->dropTable('{{%barangays}}');
    }
}
