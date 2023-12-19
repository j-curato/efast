<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_rapid_fmi_sord}}`.
 */
class m231219_013919_create_tbl_rapid_fmi_sord_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_rapid_fmi_sord}}', [
            'id' => $this->primaryKey(),
            'fk_fmi_subproject_id' => $this->bigInteger()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex(
            "idx-tbl_rapid_fmi_sord-fk_fmi_subproject_id",
            "tbl_rapid_fmi_sord",
            "fk_fmi_subproject_id"
        );
        $this->addForeignKey(
            "fk-tbl_rapid_fmi_sord-fk_fmi_subproject_id",
            "tbl_rapid_fmi_sord",
            "fk_fmi_subproject_id",
            "tbl_fmi_subprojects",
            "id",
            "RESTRICT"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            "fk-tbl_rapid_fmi_sord-fk_fmi_subproject_id",
            "tbl_rapid_fmi_sord"
        );
        $this->dropIndex(
            "idx-tbl_rapid_fmi_sord-fk_fmi_subproject_id",
            "tbl_rapid_fmi_sord"
        );
        $this->dropTable('{{%tbl_rapid_fmi_sord}}');
    }
}
