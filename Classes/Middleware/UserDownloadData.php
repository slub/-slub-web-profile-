<?php

declare(strict_types=1);

/*
 * This file is part of the package slub/slub-web-profile
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Slub\SlubWebProfile\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slub\SlubWebProfile\Service\DownloadService;
use Slub\SlubWebProfile\Utility\ConstantsUtility;
use Slub\SlubWebProfile\Utility\DotUtility;
use Slub\SlubWebProfile\Utility\FrontendUserUtility;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Exception;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedRelationException;
class UserDownloadData implements MiddlewareInterface
{
    /**
     * @var DownloadService
     */
    protected $downloadService;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param DownloadService $downloadService
     */
    public function injectEventService(DownloadService $downloadService): void
    {
        $this->downloadService = $downloadService;
    }

    /**
     * @throws InvalidConfigurationTypeException
     */
    public function __construct()
    {
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        $this->downloadService = $objectManager->get(DownloadService::class);
        $this->settings = $this->getSettings();
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws UnsupportedRelationException
     * @throws \JsonException
     * @throws AspectNotFoundException
     * @throws Exception
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $response = $handler->handle($request);
        $fileFormat = $request->getQueryParams()['tx_' . ConstantsUtility::EXTENSION_NAME . '_downloaddata']['fileFormat'] ?? '';

        if (empty($fileFormat)) {
            return $response;
        }

        if (!in_array($fileFormat, $this->validFileFormats())) {
            throw new UnsupportedRelationException('Invalid file format.', 1471776458);
        }

        $userIdentifier = FrontendUserUtility::getIdentifier();
        $result = $this->downloadService->getDownload($fileFormat);
        $fileName = 'slub-data-download-' . $userIdentifier . '.' . $fileFormat;
        $contentType = ($result['contentType'] ?? 'application/force-download') . '; charset=UTF-8';
        $content = ($result['data'] ?? '') . sprintf($this->settings['download']['dsvgoUrl'], $userIdentifier, $fileFormat);
        $responseBody = new Stream('php://temp', 'rw');
        $responseBody->write($content);

        return $response
            ->withBody($responseBody)
            ->withHeader('Pragma', 'public')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', $contentType)
            ->withHeader('Content-Disposition', 'attachment; filename="'. $fileName .'"')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withStatus(200);
    }

    /**
     * @return array
     */
    protected function validFileFormats(): array
    {
        return GeneralUtility::trimExplode(',', $this->settings['download']['fileFormat'] ?? 'none');
    }

    /**
     * @return array
     * @throws InvalidConfigurationTypeException
     */
    protected function getSettings(): array
    {
        $configuration = GeneralUtility::makeInstance(ConfigurationManager::class)
            ->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,'sitepackage');
        $settings = $configuration['plugin.']['tx_' . ConstantsUtility::EXTENSION_NAME . '.']['settings.'] ?? [];

        return DotUtility::remove($settings);
    }
}
