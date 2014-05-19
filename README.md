Laravel Integer SQL Dates
=========================

How to use INTEGER UNIX Timestamps for your MySQL dates in Laravel, while preserving the auto-update magic and Carbon API.

I have been using ints to store my date/time info in MySQL and really like not worrying about MySQL transforming dates and what timezone it is running in vs. what timezone the machine (PHP) is running in. I searched around for a solution and came up with this one, which was best for the purpose I needed it for.

## Laravel Forums Thread

There is also a [discussion](http://laravel.io/forum/05-18-2014-integer-dates-with-sql-database-in-laravel) on the Laravel forums.

## Why use INTEGER (a.k.a ints) for Datetime?

  * You can keep 100% of your date manipulation, comparison and presentation in one place: PHP.
  * Because thinking of/considering/dealing with timezones complicate your application level (your code, third-party APIs, database servers, CDNs)
  * So you don't have to worry about what timezone MySQL is using vs. what your server is using or PHP is pushing
  * Because [Stripe's](http://www.stripe.com/) API sends everything to you in UTC epochs. You do use Stripe right?
  * Because it might just give you the warm fuzzies instead a red face like MySQL DATETIME will
  * Plus: http://forum.kohanaframework.org/discussion/1404/int-vs-datetime-for-dates-in-databases


Read these articles and see how complex it can be when you don't use INTs:

  * https://web.ivy.net/~carton/rant/MySQL-timezones.txt
  * http://stackoverflow.com/questions/19023978/should-mysql-have-its-timezone-set-to-utc
  * http://stackoverflow.com/questions/2532729/daylight-saving-time-and-time-zone-best-practices

## Overrides

Basically, I approached it by creating a `Base.php` model and in it overriding the necessary `\Eloquent\Model.php` methods and one `\Query\SQLServerGrammer.php` method.

Then in your Models or Repositories you will need to `extend` the `Base` model to bring in the __override__ methods.

## app/models/Base.php
```php
<?php

class Base extends Eloquent {

  /**
   * Get a fresh timestamp for the model.
   *
   * Overrides:
   * vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php
   *
   * @return (int) timestamp
   */
  public function freshTimestamp()
  {
    return time();
  }

  /**
   * Don't mutate our
   *  (int, UTC Epoch) 1400468556
   *  to (string, MySQL TIMESTAMP) '2000-00-00 00:00:00'
   *  on INSERT or UPDATE
   *
   * Overrides:
   * vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php
   *
   * @return (int) timestamp
   */
  public function fromDateTime($value)
  {
    return $value;
  }

  // Uncomment, if you don't want Carbon API on SELECTs
  // protected function asDateTime($value)
  // {
  //   return $value;
  // }

  /**
   * Reset the format for database stored dates to Unix Timestamp
   *
   * Overrides:
   * vendor/laravel/framework/src/Illuminate/Database/Query/Grammars/SqlServerGrammar.php
   *
   * @return string
   */
  public function getDateFormat()
  {
    return 'U'; // PHP date() Seconds since the Unix Epoch
  }
}
```

## Intent, Requisites

  * Allow integer Unix Timestamps that __are not__ converted or manipulated by Laravel, it's Carbon Date package or MySQL.
  * Preserve Laravel's cool helper functions that automatically update your `created_at` and `updated_at` (or any column names you add to the Laravel $dates() array). As you can see in the example file `hello.php` the code's SQL statements are not including any date columns, but letting Laravel provide them. BUT you can see in the `output.html` file (or by loading up the app and running / yourself) that Laravel is inserting (int) type timestamps into our date columns. Neat!
  * Allow you to use the Carbon API on any dates you get __back__ from the database, so you can output nice human friendly dates using Carbon's clean API.
  * Tell Laravel about our new date format used in the `SQLServerGrammer.php` class, so it is knowledgeable about our preferred format if needed.

I've only included the relevant files for the example. If you composer create new project, just copy over all the files in the `app` directory. There is also a basic `user.sql` table schema so you can add a quick SQLite3 database if you want to test. Maybe someone can create the migrations and PR it.

## Demo

It should work out of the box with the default Laravel "hello" route mapped to / on default installs.

_NOTE_: `hello.php` has a 3 second `sleep()` before the `UPDATE` is run so the timestamp can be different. Just sayin' in case you run the example and it seems to hang.

## Files

  * `app/models/Base.php`: Contains the override methods.
  * `app/models/User.php`: Just a simple model to show how it inherits the overrides and allows us to do the DB stuff for the example.
  * `app/views/hello.php`: INSERTs a record, UPDATES it, outputs a bunch of `var_dump` and `QueryLog` so you can see how the overrides work.
  * `output.html`: An xdebug dump of the before and after from `INSERTING` a new record and `UPDATING` it. Drag/Drop on your browser to see the whole process.
  * `users.sql` Simple db table schema for testing. Recommend SQLite3 for quick setup.

## Improvements?

It would be great if someone could contribute a way to have the overrides be more __global__ so you don't have to `extend` each Model. Would it be better as a `Package`?
