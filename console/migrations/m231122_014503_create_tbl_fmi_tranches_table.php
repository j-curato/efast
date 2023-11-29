<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_tranches}}`.
 */
class m231122_014503_create_tbl_fmi_tranches_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_tranches}}', [
            'id' => $this->primaryKey(),
            'tranche_number' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('{{%tbl_fmi_tranches}}', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tbl_fmi_tranches}}');
    }
}
