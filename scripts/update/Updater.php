<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2016 (original work) Open Assessment Technologies SA;
 *
 *
 */

namespace oat\taoRevision\scripts\update;

use common_Exception;
use common_exception_NotImplemented as NotImplementedException;
use common_ext_ExtensionUpdater;
use oat\oatbox\filesystem\FileSystemService;
use oat\taoRevision\model\RepositoryInterface;
use oat\taoRevision\model\RepositoryService;

/**
 *
 * @author Joel Bout <joel@taotesting.com>
 */
class Updater extends common_ext_ExtensionUpdater
{
    /**
     * @param string $initialVersion
     *
     * @return string $versionUpdatedTo
     * @throws common_Exception
     */
    public function update($initialVersion)
    {
        if ($this->isBetween('1.0.0', '2.0.0')) {
            throw new NotImplementedException('Updates from versions prior to 2.0.0 are not longer supported');
        }

        $this->skip('2.0.0', '2.1.2');

        if ($this->isVersion('2.1.2')) {
            $fss = $this->getServiceManager()->get(FileSystemService::SERVICE_ID);
            $fss->createFileSystem(RepositoryService::FILES_SYSTEM_NAME, 'tao/revisions');
            $this->getServiceManager()->register(FileSystemService::SERVICE_ID, $fss);

            $repositoryService = $this->getServiceManager()->get(RepositoryInterface::SERVICE_ID);
            $repositoryService->setOption(RepositoryService::OPTION_FS, RepositoryService::FILES_SYSTEM_NAME);
            $this->getServiceManager()->register(RepositoryInterface::SERVICE_ID, $repositoryService);
            $this->setVersion('2.2.0');
        }

        $this->skip('2.2.0', '8.0.0');
    }
}
