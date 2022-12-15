<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SubscriptionsFixture
 *
 */
class SubscriptionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'user_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'membership_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'membership_type_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tier_level_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'currency_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'reservations' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'commitment_period' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'period_type' => ['type' => 'string', 'length' => 10, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'minimum_people' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'subscribed_by_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'start_date' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'end_date' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'comment' => ['type' => 'string', 'length' => 500, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '', 'precision' => null],
        'updated_by' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'membership_id' => ['type' => 'index', 'columns' => ['membership_id'], 'length' => []],
            'membership_type_id' => ['type' => 'index', 'columns' => ['membership_type_id'], 'length' => []],
            'tier_level_id' => ['type' => 'index', 'columns' => ['tier_level_id'], 'length' => []],
            'currency_id' => ['type' => 'index', 'columns' => ['currency_id'], 'length' => []],
            'subscribed_by_id' => ['type' => 'index', 'columns' => ['subscribed_by_id'], 'length' => []],
            'updated_by' => ['type' => 'index', 'columns' => ['updated_by'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'subscriptions_ibfk_1' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscriptions_ibfk_2' => ['type' => 'foreign', 'columns' => ['membership_id'], 'references' => ['memberships', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscriptions_ibfk_3' => ['type' => 'foreign', 'columns' => ['membership_type_id'], 'references' => ['membership_types', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscriptions_ibfk_4' => ['type' => 'foreign', 'columns' => ['tier_level_id'], 'references' => ['tier_levels', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscriptions_ibfk_5' => ['type' => 'foreign', 'columns' => ['currency_id'], 'references' => ['currencies', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscriptions_ibfk_6' => ['type' => 'foreign', 'columns' => ['subscribed_by_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'subscriptions_ibfk_7' => ['type' => 'foreign', 'columns' => ['updated_by'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'user_id' => 1,
                'membership_id' => 1,
                'membership_type_id' => 1,
                'tier_level_id' => 1,
                'currency_id' => 1,
                'reservations' => 1,
                'commitment_period' => 1,
                'period_type' => 'Lorem ip',
                'minimum_people' => 1,
                'subscribed_by_id' => 1,
                'start_date' => '2018-06-07 10:31:48',
                'end_date' => '2018-06-07 10:31:48',
                'comment' => 'Lorem ipsum dolor sit amet',
                'created' => '2018-06-07 10:31:48',
                'modified' => 1528367508,
                'updated_by' => 1,
                'deleted' => 1
            ],
        ];
        parent::init();
    }
}
