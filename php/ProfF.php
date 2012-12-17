<?php

class ProfF {

	private $timingPrecision = 3;

	private $timings = array();

	private $fields = array();

	/**
	 *
	 * @var ProfF
	 */
	private static $mainInstance;

	public function __construct($main = true) {
		if ($main) {
			self::$mainInstance = $this;
		}
	}

	public static function i() {
		return self::$mainInstance;
	}

	public function startTiming($fieldname) {
		$this->checkField($fieldname);

		if (isset($this->timings[$fieldname])) {
			trigger_error("Timing for $fieldname was already started.", E_USER_WARNING);
		}

		$this->timings[$fieldname] = microtime(true);
	}

	public function finishTiming($fieldname) {
		$this->checkField($fieldname);

		if (!isset($this->timings[$fieldname])) {
			trigger_error("Timing for $fieldname has not been started.", E_USER_WARNING);

			return;
		}

		$this->inc($fieldname, round(microtime(true) - $this->timings[$fieldname], $this->timingPrecision));

		$this->timings[$fieldname] = null;
	}

	public function get($fieldname) {
		$this->checkField($fieldname);

		return $this->fields[$fieldname];
	}

	public function set($fieldname, $value) {
		$this->checkField($fieldname);

		$this->fields[$fieldname] = $value;
	}

	public function initFields(array $fields) {
		$this->fields = array_map(function () { return null; }, array_flip($fields));
	}

	public function inc($fieldname, $value = 1) {
		$this->checkField($fieldname);

		$this->fields[$fieldname] += $value;
	}

	public function export() {
		return $this->fields;
	}

	private function checkField($fieldname) {
		if (!array_key_exists($fieldname, $this->fields)) {
			throw new Exception("Field with the name $fieldname not found");
		}
	}

	public function appendToCSVFile($filename) {
		$filehandle = fopen($filename, 'a+');

		if ($filehandle === false) {
			trigger_error("Error appending Profiling Information to $filename", E_USER_WARNING);

			return;
		}

		fputcsv($filehandle, $this->export());

		fclose($filehandle);
	}

}
