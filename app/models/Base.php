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