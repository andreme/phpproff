<?php

require_once __DIR__.'/proff.php';

$proff = new ProfF();

$proff->initFields(array('Time', 'Controller', 'Action', 'IP', 'Elapsed', 'SQLElapsed', 'SQLCount', 'RequestID', 'MaxMem'));

$proff->startTiming('Elapsed');
usleep(500000);

// Request Information
$proff->set('Time', date('Y-m-d H:i:s'));
$proff->set('RequestID', uniqid());
$proff->set('Controller', 'Test');
$proff->set('Action', 'save');
$proff->set('IP', $_SERVER['REMOTE_ADDR']);


$proff->startTiming('SQLElapsed');
$proff->inc('SQLCount');
sleep(1);
$proff->finishTiming('SQLElapsed');

// call static
for ($i = 10; $i > 0; $i--) {
	ProfF::i()->startTiming('SQLElapsed');
	ProfF::i()->inc('SQLCount');
	usleep(500000);
	ProfF::i()->finishTiming('SQLElapsed');
}


$proff->finishTiming('Elapsed');

$proff->set('MaxMem', memory_get_peak_usage(true));

$proff->appendToCSVFile(__DIR__.'/prof.csv');
