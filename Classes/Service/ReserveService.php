<?php

declare(strict_types=1);

/*
 * This file is part of the package slub/slub-web-profile
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace Slub\SlubWebProfile\Service;

use Slub\SlubWebProfile\Domain\Model\Dto\ApiConfiguration;
use Slub\SlubWebProfile\Http\Request;

class ReserveService
{
    /**
     * @var ApiConfiguration
     */
    protected $apiConfiguration;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param ApiConfiguration $apiConfiguration
     */
    public function injectApiConfiguration(ApiConfiguration $apiConfiguration): void
    {
        $this->apiConfiguration = $apiConfiguration;
    }

    /**
     * @param Request $request
     */
    public function injectRequest(Request $request): void
    {
        $this->request = $request;
    }

    /**
     * @return array|null
     * @throws \JsonException
     */
    public function getReserveCurrent(): ?array
    {
        $uri = $this->apiConfiguration->getReserveCurrentUri();
        return $this->request->process($uri)['reserveCurrent'];
    }

    /**
     * @param int $user
     * @param array $data
     * @return array|null
     * @throws \JsonException
     */
    public function deleteReserveCurrent(int $user, array $data): ?array
    {
        if ($user === 0) {
            return null;
        }

        $uri = $this->apiConfiguration->getReserveCurrentDeleteUri();

        return $this->request->process($uri, 'POST', [
            'body' => json_encode([
                'delete' => $data
            ], JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @param int $page
     * @return array|null
     * @throws \JsonException
     */
    public function getReserveHistory(int $page): ?array
    {
        // Update the path for the api call for pagination
        $this->apiConfiguration->updateReservePaginatedPaths($page);

        $uri = $this->apiConfiguration->getReserveHistoryUri();
        return $this->request->process($uri);
    }

    /**
     * @return array|null
     * @throws \JsonException
     */
    public function getReserveHold(): ?array
    {
        $uri = $this->apiConfiguration->getReserveHoldUri();
        return $this->request->process($uri);
    }
}
