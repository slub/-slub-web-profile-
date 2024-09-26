<?php

declare(strict_types=1);

/*
 * This file is part of the package slub/slub-web-profile
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Slub\SlubWebProfile\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

class ILLRequestHoldInfoViewHelper extends AbstractViewHelper {
	public function initializeArguments()
    {
		parent::initializeArguments();
		$this->registerArgument('barcode', 'string', 'The barcode of the item', TRUE);
	}

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
		$barcode = strtolower($arguments['barcode']) ?? '';
		$lastCharacter = substr($barcode, -1);

		return $lastCharacter === 'k'
            ? 'Fernleihe (Kopien)'
            : 'Fernleihe';
	}
}
