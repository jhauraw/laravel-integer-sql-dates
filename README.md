Laravel Integer SQL Dates
=========================

How to use INTEGER UNIX Timestamps for your MySQL dates in Laravel, while preserving the auto-update magic and Carbon API.

I have been using ints for my date columns in MySQL and really like not worrying about MySQL transforming dates and what timezone it is running in vs. what timezone the machine is running in. I searched around for a solution and came up with this one, which was best for the purpose I needed it for.

## What it is intended to do

  * Allow integer Unix Timestamps that __are not__ converted or manipulated by Laravel, it's Carbon Date package or MySQL.
  * Preserve Laravel's cool helper functions that automatically update your `created_at` and `updated_at` (or any column names you add to the Laravel $dates() array). As you can see in the example file `hello.php` the code's SQL statements are not including any date columns, but letting Laravel provide them. BUT you can see in the `output.html` file (or by loading up the app and running / yourself) that Laravel is inserting (int) type timestamps into our date columns. Neat!
  * Allow you to use the Carbon API on any dates you get __back__ from the database, so you can output nice human friendly dates using Carbon's clean API.
  * Tell Laravel about our new date format used in the `SQLServerGrammer.php` class, so it is knowledgeable about our preferred format if needed.

I've only included the relevant files for the example. If you composer create new project, just copy over all the files in the `app` directory. There is also a basic `user.sql` table schema so you can add a quick SQLite3 database if you want to test. Maybe someone can create the migrations and PR it.

It should work out of the box with the default Laravel "hello" route mapped to / on default installs.

## Overrides

Basically, I approached it by creating a `Base.php` model and in it overriding the necessary `\Eloquent\Model.php` methods and one `\Query\SQLServerGrammer.php` method.

Then in your Models or Repositories you will need to `extend` the `Base` model to bring in the __override__ methods.

## output.html

An xdebug dump of the before and after from `INSERTING` a new record and `UPDATING` it.

## users.sql

Simple db table schema for testing. Recommend SQLite3 for quick setup.

## Improvements?

It would be great if someone could contribute a way to have the overrides be more __global__ so you don't have to `extend` each Model.
