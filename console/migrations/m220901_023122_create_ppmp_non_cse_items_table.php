<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ppmp_non_cse_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%ppmp_non_cse}}`
 */
class m220901_023122_create_ppmp_non_cse_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ppmp_non_cse_items}}', [
            'id' => $this->primaryKey(),
            'project_name' => $this->text()->notNull(),
            'target_month' => $this->date()->notNull(),
            'fk_fund_of_source_id' => $this->bigInteger()->notNull(),
            'fk_pap_code_id' => $this->bigInteger()->notNull(),
            'fk_ppmp_non_cse_id' => $this->bigInteger(),
            'description' => $this->text()->notNull(),
            'fk_end_user' => $this->bigInteger()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('ppmp_non_cse_items', 'id', $this->bigInteger());

        // creates index for column `fk_ppmp_non_cse_id`
        $this->createIndex(
            '{{%idx-ppmp_non_cse_items-fk_ppmp_non_cse_id}}',
            '{{%ppmp_non_cse_items}}',
            'fk_ppmp_non_cse_id'
        );

        // add foreign key for table `{{%ppmp_non_cse}}`
        $this->addForeignKey(
            '{{%fk-ppmp_non_cse_items-fk_ppmp_non_cse_id}}',
            '{{%ppmp_non_cse_items}}',
            'fk_ppmp_non_cse_id',
            '{{%ppmp_non_cse}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%ppmp_non_cse}}`
        $this->dropForeignKey(
            '{{%fk-ppmp_non_cse_items-fk_ppmp_non_cse_id}}',
            '{{%ppmp_non_cse_items}}'
        );

        // drops index for column `fk_ppmp_non_cse_id`
        $this->dropIndex(
            '{{%idx-ppmp_non_cse_items-fk_ppmp_non_cse_id}}',
            '{{%ppmp_non_cse_items}}'
        );

        $this->dropTable('{{%ppmp_non_cse_items}}');
    }
}
