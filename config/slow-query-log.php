<?php

return [
    # set to true to enable slow query log
    'enabled' => (bool) env('SLOW_QUERY_LOG_ENABLED', false),

    # minimum millisecond a query execution is
    # considered as slow to be recorded
    'min-threshold' => (int) env('SLOW_QUERY_MIN_THRESHOLD', 50), # in millisecond

    # sometimes tracelog could be too long due to
    # it includes all the third party library.
    # to keep it small, you can specify substring here
    # to check if a stack trace need to be included in
    # log. For example, when you set trace-only to 'app'
    # stack trace in log will only include all files
    # with 'app' in it's path
    'trace-only' => env('SLOW_QUERY_TRACE_ONLY', ''),

    # the opposite of trace-only, it will exclude all
    # file path that has the substring of its value
    'trace-exclude' => env('SLOW_QUERY_TRACE_EXCLUDE', ''),

    # there are three option to store the slow query:
    # 1. file : all slow query log will be recorded in local file
    # 2. database : all slow query log will be recorded to a table in db
    # 3. log-channel : custom log handler channel.
    #      You can specify custom channels in 'log-channel' value in this config
    #      the log channel you use must be registered in config/logging.php -> channels
    'storage' => env('SLOW_QUERY_LOG_STORAGE', 'file'),

    'log-channel' => 'logstash',

    # Specify maximum character length of SQL to be stored in log.
    # Sometimes when the query gets too long, you might want to truncate
    #   the string to save up space.
    # zero (0) means that there is no limitation on sql length.
    'max-sql-length' => 0
];