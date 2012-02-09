<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder, you can edit this class
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	final class BillTransaction extends AutoBillTransaction
	{
		/**
		 * @return BillTransaction
		 */
		public static function create()
		{
			return new self;
		}

		// FIXME XXX: transaction
		public function execute()
		{
			$debit = $this->getDebit();
			$credit = $this->getCredit();

			$debit->reduceBalance($this->getValue());
			$credit->addBalance($this->getValue());

			$debit->da()->save($debit);
			$credit->da()->save($credit);

			return $this;
		}
	}
?>