phpProfF
========

A simple profiling Framework for PHP

It's goal is to provide a high level performance view for php applications in a production system. For example elapsed script time, elapsed time and count for SQLs, maximum memory usage. All data that will be gathered is defined by the developer, this allows total flexibility. There are methods to measure elapsed time, increment counters and just store plain data. At the end of the script the data can be exported. An export function to save the gathered record into a CSV file is included, but it can be stored anywhere.

The collected data can then be analysed, with the help of a database or other tools.

Example
====
```php

// create an instance, it is also used for static access
$proff = new ProfF();

// Fields are where the data is stored in
$proff->initFields(array('Time', 'Action', 'IP', 'Elapsed', 'SQLElapsed', 'SQLCount', 'MaxMem'));

// time the whole script
$proff->startTiming('Elapsed');
usleep(500000);

// Request Information
$proff->set('Time', date('Y-m-d H:i:s'));
$proff->set('Action', 'saveSomething');
$proff->set('IP', $_SERVER['REMOTE_ADDR']);


// this is where you code would normally be called
// the functions can be called through the static access ProfF::i()
// calling startTiming and finishTiming repeatedly for the same Field aggregates the elapsed times
for ($i = 10; $i > 0; $i--) {
	ProfF::i()->startTiming('SQLElapsed');
	ProfF::i()->inc('SQLCount');
	usleep(500000);
	ProfF::i()->finishTiming('SQLElapsed');
}


// finish timing for script
$proff->finishTiming('Elapsed');

$proff->set('MaxMem', memory_get_peak_usage(true));

// save record in CSV file
$proff->appendToCSVFile(__DIR__.'/prof.csv');

```
