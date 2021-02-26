<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sub_accounts2}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%sub_accounts1}}`
 */
class m210224_021229_create_sub_accounts2_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sub_accounts2}}', [
            'id' => $this->primaryKey(),
            'sub_accounts1_id' => $this->integer()->notNull(),
            'object_code' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
        ]);

        
        // creates index for column `sub_accounts1_id`
        $this->createIndex(
            '{{%idx-sub_accounts2-sub_accounts1_id}}',
            '{{%sub_accounts2}}',
            'sub_accounts1_id'
        );

        // add foreign key for table `{{%sub_accounts1}}`
        $this->addForeignKey(
            '{{%fk-sub_accounts2-sub_accounts1_id}}',
            '{{%sub_accounts2}}',
            'sub_accounts1_id',
            '{{%sub_accounts1}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%sub_accounts1}}`
        $this->dropForeignKey(
            '{{%fk-sub_accounts2-sub_accounts1_id}}',
            '{{%sub_accounts2}}'
        );

        // drops index for column `sub_accounts1_id`
        $this->dropIndex(
            '{{%idx-sub_accounts2-sub_accounts1_id}}',
            '{{%sub_accounts2}}'
        );

        $this->dropTable('{{%sub_accounts2}}');
    }
}
