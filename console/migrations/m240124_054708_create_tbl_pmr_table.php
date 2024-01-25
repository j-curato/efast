<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_pmr}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%office}}`
 */
class m240124_054708_create_tbl_pmr_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_pmr}}', [
            'id' => $this->primaryKey(),
            'fk_office_id' => $this->integer()->notNull(),
            'reporting_period' => $this->string()->notNull(),
        ]);

        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_pmr-fk_office_id}}',
            '{{%tbl_pmr}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_pmr-fk_office_id}}',
            '{{%tbl_pmr}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_pmr-fk_office_id}}',
            '{{%tbl_pmr}}'
        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_pmr-fk_office_id}}',
            '{{%tbl_pmr}}'
        );

        $this->dropTable('{{%tbl_pmr}}');
    }
}
