# Todo: make it more proper

# Usage
## Publish config
The first time you use this package, publish the configuration by typing `php artisan vendor:publish --provider=Mamitech\SlowQueryLog\ServiceProvider`

Enable it by settings environment variable `SLOW_QUERY_LOG_ENABLED` to `true`.

Set your minimum threshold of query execution to be considered slow by setting environment variable `SLOW_QUERY_MIN_THRESHOLD` to a number (in millisecond unit).

If you only want to trace certain files, set your environment variable `SLOW_QUERY_TRACE_ONLY` to some string.
For example if you want to only keep the trace data of all files within `app/` folder, set `SLOW_QUERY_TRACE_ONLY` to `app/`

# Run Test

Before running test, make sure you have installed all the dependencies
by running:

```
composer install
```

Then, make sure you have `php-sqlite3` installed in your system
because use sqlite to run the test.

In debian based system:

```
sudo apt install php-sqlite3
```

Now run the test using:

```
./vendor/bin/phpunit
```