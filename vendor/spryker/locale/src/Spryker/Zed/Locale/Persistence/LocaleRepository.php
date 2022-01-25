<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Locale\Persistence\LocalePersistenceFactory getFactory()
 */
class LocaleRepository extends AbstractRepository implements LocaleRepositoryInterface
{
    /**
     * @param array<string> $localeNames
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleTransfersByLocaleNames(array $localeNames): array
    {
        $localeEntities = $this->getFactory()->createLocaleQuery()
            ->filterByLocaleName_In($localeNames)
            ->find();

        return $this->mapLocaleEntitiesToLocaleTransfers($localeEntities);
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleTransferByLocaleName(string $localeName): ?LocaleTransfer
    {
        $localeEntity = $this->getFactory()->createLocaleQuery()
            ->filterByLocaleName($localeName)
            ->findOne();

        if (!$localeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createLocaleMapper()
            ->mapLocaleEntityToLocaleTransfer($localeEntity, new LocaleTransfer());
    }

    /**
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer|null
     */
    public function findLocaleByIdLocale(int $idLocale): ?LocaleTransfer
    {
        $localeEntity = $this->getFactory()->createLocaleQuery()
            ->filterByIdLocale($idLocale)
            ->findOne();

        if (!$localeEntity) {
            return null;
        }

        return $this->getFactory()
            ->createLocaleMapper()
            ->mapLocaleEntityToLocaleTransfer($localeEntity, new LocaleTransfer());
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Locale\Persistence\SpyLocale> $localeEntities
     *
     * @return array<\Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function mapLocaleEntitiesToLocaleTransfers(ObjectCollection $localeEntities): array
    {
        $localeTransfers = [];
        $localeMapper = $this->getFactory()->createLocaleMapper();

        foreach ($localeEntities as $localeEntity) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer = $localeMapper->mapLocaleEntityToLocaleTransfer($localeEntity, $localeTransfer);

            $localeTransfers[] = $localeTransfer;
        }

        return $localeTransfers;
    }
}
