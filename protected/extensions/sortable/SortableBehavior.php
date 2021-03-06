<?
class SortableBehavior extends ActiveRecordBehavior
{
    //заполняем айдишниками
    public function fillOrderColumn($column)
    {
        $model = $this->getOwner();
        $c     = Yii::app()->db->commandBuilder->createSqlCommand(
            "UPDATE ".$model->tableName()." AS t SET t.{$column} = t.id");

        $c->execute();
    }


    public function setPositions($ids, $column, $start)
    {
        $model = $this->getOwner();
        $table = $model->tableName();

        $priorities = array();
        foreach ($ids as $id)
        {
            $priorities[$id] = $start--;
        }

        $case = SqlHelper::arrToCase('id', $priorities, $model->getTableAlias());
        $in = SqlHelper::in('id', $ids, $model->getTableAlias());
        $c = Yii::app()->db->commandBuilder->createSqlCommand("UPDATE {$table} AS t SET t.{$column} = {$case} WHERE {$in}");
        $c->execute();
    }


    public function beforeSave($event)
    {
        $model = $this->getOwner();

        if ($model->isNewRecord)
        {
            $column         = 'order';
            $i              = $model->max($column);
            $model->$column = ++$i;
        }
    }


    public function beforeFind($event)
    {
        $criteria = $this->owner->getDbCriteria();
        if (!$criteria->order)
        {
            $criteria->order = '`order`';
        }
        
        parent::beforeFind($event);
    }


    public function beforeGridInitColumns($event)
    {
        if ($event->sender->sortable)
        {
            $event->sender->addColumn(array(
                'class' => 'ext.sortable.SortableColumn',
                'header'=> t('Сортировка')
            ), -1);
        }
    }

}