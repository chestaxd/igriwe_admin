<?php


namespace App\Repository;


use App\Service\ApiResponseDecorator;
use App\Service\PagePrototypeTranslator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiRepository
{
    use ApiRepositoryTrait;
    /** @var HttpClientInterface */
    private $client;
    protected $pageTranslationService;
    protected $metaInfo = [];

    public function __construct(HttpClientInterface $client, PagePrototypeTranslator $pageTranslationService)
    {
        $this->client = $client;
        $this->pageTranslationService = $pageTranslationService;

    }

    /**
     * @param string $url
     * @param array  $params
     *
     * @return array|null
     */
    private function apiCall(string $url, array $params = [])
    {

        $url = $this->apiPrefix . $url;
        $params['locale'] = $this->locale;
        foreach ($params as $param => $value) {
            $url = str_replace("%{$param}%", $value, $url);
        }
        try {
            $response = $this->client->request('GET', $url);
            $res = $response->toArray();
            $this->metaInfo = [];
            if (isset($res['hydra:totalItems'])) {
                $this->metaInfo['totalItems'] = $res['hydra:totalItems'];
            }
            if (isset($res['hydra:totalItems'])) {
                $this->metaInfo['totalItems'] = $res['hydra:totalItems'];
            }

            if (isset($res['hydra:member'])) {
                return ApiResponseDecorator::decorate($res['hydra:member'], $this->pageTranslationService);
            } else {
                return ApiResponseDecorator::decorate($res, $this->pageTranslationService);
            }
        } catch (TransportExceptionInterface $exception) {
        } catch (\Exception $exception) {
        } catch (ClientExceptionInterface $e) {
        } catch (DecodingExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        }
        return null;
    }

    public function getRequestMeta()
    {
        return $this->metaInfo;
    }

}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}