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

use Everon\Component\Collection\CollectionInterface;
use Everon\Component\Collection\Lazy;

class LazyCollectionTest extends CollectionTest
{

    /**
     * @param array $data
     *
     * @return CollectionInterface
     */
    protected function createCollectionInstance(array $data)
    {
        $Loader = function () use ($data) {
            return $data;
        };

        return new Lazy($Loader);
    }

    public function test_collection_has_Countable_interface()
    {
        parent::test_collection_has_Countable_interface();
    }

    public function test_collection_has_ArrayAccess_interface()
    {
        parent::test_collection_has_ArrayAccess_interface();
    }

    public function test_collection_has_IteratorAggregate_interface()
    {
        parent::test_collection_has_IteratorAggregate_interface();
    }

    public function test_collection_has_ArrayableInterface_interface()
    {
        parent::test_collection_has_ArrayableInterface_interface();
    }

    public function test_append()
    {
        parent::test_append();
    }

    public function test_append_array()
    {
        parent::test_append_array();
    }

    public function test_append_collections()
    {
        parent::test_append_collections();
    }

    public function test_get_with_default()
    {
        parent::test_get_with_default();
    }

    public function test_get_without_default()
    {
        parent::test_get_without_default();
    }

    public function test_has()
    {
        parent::test_has();
    }

    public function test_is_empty()
    {
        parent::test_is_empty();
    }

    public function test_remove()
    {
        parent::test_remove();
    }

    public function test_set()
    {
        parent::test_set();
    }

    public function test_append_nested_collections_deep()
    {
        parent::test_append_nested_collections_deep();
    }

    public function test_sort_values_ascending()
    {
        parent::test_sort_values_ascending();
    }

    public function test_sort_values_descending()
    {
        parent::test_sort_values_descending();
    }

    public function test_sort_keys_ascending()
    {
        parent::test_sort_keys_ascending();
    }

    public function test_sort_keys_descending()
    {
        parent::test_sort_keys_descending();
    }

    public function test_sort_by()
    {
        parent::test_sort_by();
    }

    public function test_foreach()
    {
        parent::test_foreach();
    }

    public function test_for()
    {
        parent::test_for();
    }

}
