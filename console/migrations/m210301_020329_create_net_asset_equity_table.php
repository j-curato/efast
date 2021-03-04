<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%net_asset_equity}}`.
 */
class m210301_020329_create_net_asset_equity_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%net_asset_equity}}', [
            'id' => $this->primaryKey(),
            'group' => $this->string(255)->notNull(),
            'specific_change' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%net_asset_equity}}');
    }
}
