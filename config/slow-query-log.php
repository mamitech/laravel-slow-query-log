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
    # 3. channel : custom log handler channel.
    #      You can specify custom channels in 'channels' value in this config
    'storage' => env('SLOW_QUERY_LOG_STORAGE', 'file'),

    'log-channel' => 'logstash'
];