<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Utils\Tests\Unit;

use Everon\Component\Utils\TestCase\MockeryTest;
use Everon\Component\Utils\Tests\Unit\Doubles\CollectionStub;

class CollectionTest extends MockeryTest
{

    /**
     * @var CollectionStub
     */
    protected $CollectionStub;

    /**
     * @var array
     */
    protected $arrayFixture = [
        'foo' => 1,
        'bar' => 'barValue',
        'fuzz' => null,
        'nested_item' => [
            'foo' => null,
        ],
    ];

    protected function setUp()
    {
        $this->CollectionStub = new CollectionStub($this->arrayFixture);
    }

    public function test_to_array_using_data_property()
    {
        $expected = [
            'foo' => 1,
            'bar' => 'barValue',
            'fuzz' => null,
            'nested_item' => [
                'foo' => null,
            ],
        ];

        $this->assertEquals($expected, $this->CollectionStub->toArray(true));
    }

    public function test_to_array_using_getArrayableData_method()
    {
        $data = [
            'arrayable_data' => 'foobar',
        ];

        $this->CollectionStub->setData($data);

        $this->assertEquals($data, $this->CollectionStub->toArray(true));
    }

    public function test_is_iterable()
    {
        $this->assertTrue($this->CollectionStub->canLoop());

        $this->CollectionStub->setData(new \ArrayObject([]));
        $this->assertTrue($this->CollectionStub->canLoop());

        $this->CollectionStub->setData(null);
        $this->assertFalse($this->CollectionStub->canLoop());
    }

    public function test_merge_default()
    {
        $this->CollectionStub->mergeData([
            'fuzz' => 'NOT NULL',
            'nested_item' => [
                'foo' => 'bar',
            ],
        ]);

        $data = $this->CollectionStub->getData();

        $this->assertEquals($data['fuzz'], 'NOT NULL');
        $this->assertEquals($data['nested_item'], [
            'foo' => 'bar',
        ]);
    }

}
