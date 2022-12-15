<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SubscriptionFeesFixture
 *
 */
class SubscriptionFeesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'subscription_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fee_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'fee_amount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => ''],
        'fee_discount' => ['type' => 'float', 'length' => null, 'precision' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => ''],
        'comment' => ['type' => 'string', 'length' => 500, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'updated_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'subscription_id' => ['type' => 'index', 'columns' => ['subscription_id'], 'length' => []],
            'fee_type_id' => ['type' => 'index', 'columns' => ['fee_type_id', 'updated_by'], 'length' => []],
            'updated_by' => ['type' => 'index', 'columns' => ['updated_by'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'subscription_fees_ibfk_1' => ['type' => 'foreign', 'columns' => ['subscription_id'], 'references' => ['subscriptions', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscription_fees_ibfk_2' => ['type' => 'foreign', 'columns' => ['fee_type_id'], 'references' => ['fee_types', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscription_fees_ibfk_3' => ['type' => 'foreign', 'columns' => ['updated_by'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'latin1_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'subscription_id' => 1,
                'fee_type_id' => 1,
                'fee_amount' => 1,
                'fee_discount' => 1,
                'comment' => 'Lorem ipsum dolor sit amet',
                'created' => '2018-06-07 10:33:09',
                'modified' => 1528367589,
                'updated_by' => 1,
                'deleted' => 1
            ],
        ];
        parent::init();
    }
}
