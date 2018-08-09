<?php

function($date, $type) {
    $userId = Yii::$app->user->id;
    $key = [__FUNCTION__, 'user_id' => $userId, 'type' => $type, 'date' => $date];
    $sql = SomeDataModel::find()
        ->select('MAX({{id}})')
        ->where(array_diff($key, [__FUNCTION__]))
        ->createCommand()
        ->getRawSql();

    return Yii::$app->cache->getOrSet($key, function () use ($key) {
        $result = [];
        $dataList = SomeDataModel::find()
            ->select(['id', 'a', 'b'])
            ->where(array_diff($key, [__FUNCTION__]))
            ->all();

        foreach ($dataList as $dataItem) {
            $result[$dataItem->id] = ['a' => $dataItem->a, 'b' => $dataItem->b];
        }

        return $result;
    }, 3600, new \yii\caching\DbDependency(['sql' => $sql]));
}
