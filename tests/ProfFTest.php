<?php

require_once __DIR__.'/../php/proff.php';

class ProfFTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var ProfF
	 */
	private $prof;

	protected function setUp() {
		$this->prof = new ProfF();
	}

//	protected function tearDown() {
//	}

	public function testTiming() {

		$this->prof->initFields(array('ElapsedTime'));

		$begin = microtime(true);
		$this->prof->startTiming('ElapsedTime');

		$this->prof->finishTiming('ElapsedTime');
		$end = microtime(true);

		$this->assertLessThanOrEqual($end - $begin, $this->prof->get('ElapsedTime'));
	}

	public function testTimingIncrementsField() {

		$this->prof->initFields(array('ElapsedTime'));

		$this->prof->set('ElapsedTime', 10);

		$this->prof->startTiming('ElapsedTime');
		usleep(100000);
		$this->prof->finishTiming('ElapsedTime');

		$this->assertGreaterThan(10, $this->prof->get('ElapsedTime'));
	}

	public function testAccessingFieldThatWasNotDefinedThrowsException() {
		$this->setExpectedException('Exception');

		$this->prof->get('X');
	}

	public function testInitialisedFieldsAreAccessibleAndEmpty() {
		$this->prof->initFields(array('Test'));

		$this->assertNull($this->prof->get('Test'));
	}

	public function testWritingDataToFieldStoresTheData() {
		$this->prof->initFields(array('Test'));

		$this->prof->set('Test', 10);

		$this->assertEquals(10, $this->prof->get('Test'));
	}

	public function testIncrementingFieldWithoutValueAdds1() {
		$this->prof->initFields(array('Test'));

		$this->prof->inc('Test');

		$this->assertEquals(1, $this->prof->get('Test'));
	}

	public function testIncrementingFieldWithValueAddsValue() {
		$this->prof->initFields(array('Test'));

		$this->prof->inc('Test', 5);

		$this->assertEquals(5, $this->prof->get('Test'));
	}

	public function testExportReturnsFieldsWithValues() {
		$this->prof->initFields(array('Test1', 'Test2'));

		$this->prof->inc('Test1', 5);
		$this->prof->set('Test2', 10);

		$values = $this->prof->export();

		$this->assertEquals(5, $values['Test1']);
		$this->assertEquals(10, $values['Test2']);
	}

	public function testMainInstanceIsStaticallyAvailable() {
		$this->assertEquals($this->prof, ProfF::i());
	}

	public function testSubInstanceDoesNotOverrideStaticInstance() {
		$subInstance = new ProfF(false);

		$this->assertNotSame($subInstance, ProfF::i());
	}

}
