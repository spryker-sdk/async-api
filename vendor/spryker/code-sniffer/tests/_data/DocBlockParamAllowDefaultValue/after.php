<?php declare(strict_types = 1);

namespace Spryker;

class FixMe
{
    /**
     * @param array $array
     * @param string|null $string
     * @return void
     */
    public function dontTouchMe(array $array, $string = null): void
    {
    }

    /**
     * @param array|null $array
     * @param string|null $string
     * @return void
     */
    public function touchMe(array $array = null, $string = null): void
    {
    }

    /**
     * @param array<string, array<string, mixed>>|null $array
     * @return void
     */
    public function generics(array $array = null): void
    {
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\SalesPaymentTransfer> $x
     *
     * @return array<\Orm\Zed\Payment\Persistence\SpySalesPayment>
     */
    public function iterableTest(iterable $x = []): array
    {
        return $x;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>|iterable $itemTransfers
     *
     * @return array<string>
     */
    protected function getCountryIso2Codes(iterable $itemTransfers): array
    {
        return array_unique($itemTransfers);
    }
}
