<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%po_asignatory}}`.
 */
class m210709_022620_create_po_asignatory_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%po_asignatory}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'position' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%po_asignatory}}');
    }
}
