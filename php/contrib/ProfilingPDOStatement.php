<?php

/**
 * PDOStatement with Profiling Information.
 * Inject into PDO Class:
 * <code>
 * $pdo = new PDO(...);
 * $pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('ProfilingPDOStatement', array($pdo));
 * </code>
 */
class ProfilingPDOStatement extends \PDOStatement {

	protected $pdo;

    protected function __construct($pdo) {
        $this->pdo = $pdo;
    }

	public function execute(array $input_parameters = null) {

		ProfF::i()->inc('SQLCount');

		ProfF::i()->startTiming('SQLElapsed');
		$result = parent::execute($input_parameters);
		ProfF::i()->finishTiming('SQLElapsed');

		return $result;
	}

}
