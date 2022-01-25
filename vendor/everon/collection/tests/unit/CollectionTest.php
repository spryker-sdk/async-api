<?php
/**
 * This file is part of the Everon components.
 *
 * (c) Oliwier Ptak <everonphp@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Everon\Component\Collection\Tests\Unit;

use Everon\Component\Collection\Collection;
use Everon\Component\Collection\CollectionInterface;
use Everon\Component\Utils\TestCase\MockeryTest;

class CollectionTest extends MockeryTest
{

    /**
     * @var array
     */
    protected $arrayFixture = [
        'foo' => 1,
        'bar' => 'barValue',
        'fuzz' => null,
    ];

    /**
     * @var CollectionInterface
     */
    protected $Collection;

    protected function setUp()
    {
        $this->Collection = $this->createCollectionInstance($this->arrayFixture);
    }

    /**
     * @param array $data
     *
     * @return Collection
     */
    protected function createCollectionInstance(array $data)
    {
        return new Collection($data);
    }

    public function test_collection_has_Countable_interface()
    {
        $this->assertInstanceOf('\Countable', $this->Collection);
    }

    public function test_collection_has_ArrayAccess_interface()
    {
        $this->assertInstanceOf('\ArrayAccess', $this->Collection);
    }

    public function test_collection_has_IteratorAggregate_interface()
    {
        $this->assertInstanceOf('\IteratorAggregate', $this->Collection);
    }

    public function test_collection_has_ArrayableInterface_interface()
    {
        $this->assertInstanceOf('Everon\Component\Utils\Collection\ArrayableInterface', $this->Collection);
    }

    public function test_append()
    {
        $this->Collection->append(100);

        $expected = $this->arrayFixture;
        $expected[3] = 100;

        $this->assertEquals($expected, $this->Collection->toArray());
    }

    public function test_append_array()
    {
        $new_data = [
            'foobar' => 100,
        ];

        $this->Collection->appendArray($new_data);

        $expected = $this->arrayFixture + $new_data;

        $this->assertEquals($expected, $this->Collection->toArray());
    }

    public function test_append_collections()
    {
        $new_data = [
            'foobar' => 100,
        ];

        $this->Collection->appendCollection($this->createCollectionInstance($new_data));

        $expected = $this->arrayFixture + $new_data;

        $this->assertEquals($expected, $this->Collection->toArray());
    }

    public function test_get_with_default()
    {
        $this->assertEquals(25, $this->Collection->get('newkey', 25));
    }

    public function test_get_without_default()
    {
        $this->assertEquals(null, $this->Collection->get('newkey'));
    }

    public function test_has()
    {
        $this->assertEquals(false, $this->Collection->get('newkey'));
        $this->assertEquals(true, $this->Collection->get('foo'));
    }

    public function test_is_empty()
    {
        $this->assertEquals(false, $this->Collection->isEmpty());

        $EmptyCollection = $this->createCollectionInstance([]);
        $this->assertEquals(true, $EmptyCollection->isEmpty());
    }

    public function test_remove()
    {
        unset($this->arrayFixture['foo']);
        $expected = $this->arrayFixture;

        $this->Collection->remove('foo');

        $this->assertEquals($expected, $this->Collection->toArray());
    }

    public function test_set()
    {
        $this->Collection->set('newkey', 100);

        $expected = $this->arrayFixture;
        $expected['newkey'] = 100;

        $this->assertEquals($expected, $this->Collection->toArray());
    }

    public function test_collect()
    {
        $this->Collection->collect($this->arrayFixture);

        $expected = $this->arrayFixture;

        $this->assertEquals($expected, $this->Collection->toArray());
    }

    public function test_append_nested_collections_deep()
    {
        $data = [
            'foo' => $this->createCollectionInstance([
                'foo_nested' => 100,
                'bar_nested' => 100,
                'nested_collection' => $this->createCollectionInstance([
                    'foo_nested' => 200,
                    'bar_nested' => 200,
                ]),
            ]),
            'bar' => 'bar',
        ];

        $Collection = $this->createCollectionInstance($data);

        $expected = [
            'foo' => [
                'foo_nested' => 100,
                'bar_nested' => 100,
                'nested_collection' => [
                    'foo_nested' => 200,
                    'bar_nested' => 200,
                ],
            ],
            'bar' => 'bar',
        ];

        $this->assertEquals($expected, $Collection->toArray(true));
    }

    public function test_sort_values_ascending()
    {
        $Collection = $this->createCollectionInstance([
            'bar' => 3,
            'foo' => 1,
            'fuzz' => 2,
        ]);

        $Collection->sortValues();

        $expected = [
            'foo' => 1,
            'fuzz' => 2,
            'bar' => 3,
        ];

        $this->assertTrue($expected === $Collection->toArray());
    }

    public function test_sort_values_descending()
    {
        $Collection = $this->createCollectionInstance([
            'bar' => 3,
            'foo' => 1,
            'fuzz' => 2,
        ]);

        $Collection->sortValues(false);

        $expected = [
            'bar' => 3,
            'fuzz' => 2,
            'foo' => 1,
        ];

        $this->assertTrue($expected === $Collection->toArray());
    }

    public function test_sort_keys_ascending()
    {
        $this->Collection->sortKeys();

        $expected = [
            'bar' => 'barValue',
            'foo' => 1,
            'fuzz' => null,
        ];

        $this->assertTrue($expected === $this->Collection->toArray());
    }

    public function test_sort_keys_descending()
    {
        $this->Collection->sortKeys(false);

        $expected = [
            'fuzz' => null,
            'foo' => 1,
            'bar' => 'barValue',
        ];

        $this->assertTrue($expected === $this->Collection->toArray());
    }

    public function test_sort_by()
    {
        $Collection = $this->createCollectionInstance([
            'fuzz' => 2,
            'bar' => 3,
            'foo' => 1,
        ]);

        $Collection->sortBy(function ($a, $b) {
            return strcasecmp($a, $b);
        });

        $expected = [
            'bar' => 3,
            'foo' => 1,
            'fuzz' => 2,
        ];

        $this->assertTrue($expected === $Collection->toArray());
    }

    public function test_foreach()
    {
        foreach ($this->Collection as $key => $value) {
            $this->assertEquals($this->arrayFixture[$key], $value);
        }
    }

    public function test_for()
    {
        $Collection = $this->createCollectionInstance([
            'fuzz',
            'bar',
            'foo',
        ]);

        for ($x=0; $x<count($Collection); $x++) {
            $key = $Collection[$x];
            $this->assertArrayHasKey($key, $this->arrayFixture);
        }
    }

}
