<?php

/*
Thx to Yenz
Execute tasks in the future
*/
class Tasker {
    /* job/task list */
    private static $jobs = [];

    /* add a job/task */
    public static function add($offset, callable $callable, array $parameters = []) {
        $job['time'] = time() + (int)$offset;
        $job['callable'] = $callable;
        $job['parameters'] = $parameters;
        self::$jobs[] = $job;
    }

    /* call periodically. Execute tasks */
    public static function doWork() {
        $time = time();
        foreach (self::$jobs as $key => $job) {
            if ($job['time'] <= $time) {
                $job['callable']($job['parameters']);
                unset(self::$jobs[$key]);
            }
        }
    }
}
?>