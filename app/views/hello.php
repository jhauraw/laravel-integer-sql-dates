<?php
$user = new User;

$time1 = time();

echo PHP_EOL . '<hr>';
echo 'Create New Record.' . PHP_EOL . PHP_EOL;

$create = $user->create(array('email' => $time1 . '@me.com'));

var_dump($create);

echo PHP_EOL . '<hr>';
echo 'Retrieve New Record.' . PHP_EOL . PHP_EOL;

$row = $user->find($create->id);

var_dump($row);

echo PHP_EOL . '<hr>';
echo 'Sleeping 3 Seconds.' . PHP_EOL;
echo 'Update field `email`.' . PHP_EOL . PHP_EOL;

sleep(3);
$time2 = time();
$row->email = $time2 . '@me.com';
$row->save();

var_dump($row->created_at);
var_dump($row);

echo PHP_EOL . '<hr>';
echo 'Dump Query Log.' . PHP_EOL . PHP_EOL;

dd(DB::getQueryLog());
