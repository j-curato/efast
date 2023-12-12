<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_rapid_mg_sord}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%office}}`
 * - `{{%mgrfrs}}`
 */
class m231212_011538_create_tbl_rapid_mg_sord_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_rapid_mg_sord}}', [
            'id' => $this->primaryKey(),
            'fk_office_id' => $this->integer(),
            'fk_mgrfr_id' => $this->bigInteger(),
            'reporting_period' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_rapid_mg_sord', 'id', $this->bigInteger());
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_rapid_mg_sord-fk_office_id}}',
            '{{%tbl_rapid_mg_sord}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_rapid_mg_sord-fk_office_id}}',
            '{{%tbl_rapid_mg_sord}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_mgrfr_id`
        $this->createIndex(
            '{{%idx-tbl_rapid_mg_sord-fk_mgrfr_id}}',
            '{{%tbl_rapid_mg_sord}}',
            'fk_mgrfr_id'
        );

        // add foreign key for table `{{%mgrfrs}}`
        $this->addForeignKey(
            '{{%fk-tbl_rapid_mg_sord-fk_mgrfr_id}}',
            '{{%tbl_rapid_mg_sord}}',
            'fk_mgrfr_id',
            '{{%mgrfrs}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_rapid_mg_sord-fk_office_id}}',
            '{{%tbl_rapid_mg_sord}}'
        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_rapid_mg_sord-fk_office_id}}',
            '{{%tbl_rapid_mg_sord}}'
        );

        // drops foreign key for table `{{%mgrfrs}}`
        $this->dropForeignKey(
            '{{%fk-tbl_rapid_mg_sord-fk_mgrfr_id}}',
            '{{%tbl_rapid_mg_sord}}'
        );

        // drops index for column `fk_mgrfr_id`
        $this->dropIndex(
            '{{%idx-tbl_rapid_mg_sord-fk_mgrfr_id}}',
            '{{%tbl_rapid_mg_sord}}'
        );

        $this->dropTable('{{%tbl_rapid_mg_sord}}');
    }
}
