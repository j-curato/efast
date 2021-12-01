<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\DvAucsEntries]].
 *
 * @see \common\models\DvAucsEntries
 */
class DvAucsEntriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\DvAucsEntries[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\DvAucsEntries|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
