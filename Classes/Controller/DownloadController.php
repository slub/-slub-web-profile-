<?php

declare(strict_types=1);

/*
 * This file is part of the package slub/slub-web-profile
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Slub\SlubWebProfile\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class DownloadController extends ActionController
{
    public function listAction(): void
    {
        $this->view->assignMultiple([
            'fileFormats' => GeneralUtility::trimExplode(',', $this->settings['download']['fileFormat'])
        ]);
    }
}
