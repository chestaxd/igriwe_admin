<?php


namespace App\Repository;


trait ApiRepositoryTrait
{
    /** @var string */
    private $apiPrefix = 'http://api.carngo.com';

    /** @var string */
    private $locale = 'en';

    /**
     * @param $locale
     */
    public function setLocale($locale)
    {
        $this->pageTranslationService->setLocale($locale);
        $this->locale = $locale;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }
    /**
     * @param $slug
     *
     * @return AsyncApiRepository|array
     */
    public function getPage($slug)
    {
        $slug = urlencode(str_replace('/','%', $slug));
        return $this->apiCall('/pages/%slug%?locale=%locale%', compact('slug'));
    }

    /**
     * @param $id
     *
     * @return AsyncApiRepository|array
     */
    public function getVendor($id)
    {
        return $this->apiCall('/vendors/%id%?locale=%locale%', compact('id'));
    }


    /**
     * @param $id
     *
     * @return AsyncApiRepository|array
     */
    public function getCity($id)
    {
        return $this->apiCall('/cities/%id%?locale=%locale%', compact('id'));
    }

    /**
     * @param $slug
     *
     * @return AsyncApiRepository|array
     */
    public function getPageTexts($pageId)
    {
        return $this->apiCall('/pages/%pageId%/page_texts?locale=%locale%', compact('pageId'));
    }

    /**
     * @return AsyncApiRepository|array
     */
    public function getPromotedCountries()
    {
        return $this->apiCall('/countries?promoted=1&page=1&itemsPerPage=60&locale=%locale%');
    }

    /**
     * @return AsyncApiRepository|array
     */
    public function getCountries($items = 60)
    {
        return $this->apiCall('/countries?enabled=1&page=1&itemsPerPage='.$items.'&locale=%locale%');
    }

    /**
     * @param int $countryId
     * @param int $page
     * @param int $ipp
     *
     * @return AsyncApiRepository|array
     */
    public function getCountryCities(int $countryId, int $page, $ipp = 60)
    {
        return $this->apiCall('/countries/%countryId%/cities?rating%5Bgt%5D=1&page=%page%&itemsPerPage=%ipp%&locale=%locale%',
            compact('countryId', 'page', 'ipp'));
    }

    /**
     * @param int $countryId
     * @param int $page
     * @param int $ipp
     *
     * @return AsyncApiRepository|array
     */
    public function getCountryPlaces(int $countryId, string $type, int $page, $ipp = 60)
    {
        return $this->apiCall('/countries/%countryId%/places?type=%type%&rating%5Bgt%5D=0&page=%page%&itemsPerPage=%ipp%&locale=%locale%',
            compact('countryId', 'type', 'page', 'ipp'));
    }

    /**
     * @param int $countryId
     * @param int $page
     * @param int $ipp
     *
     * @return AsyncApiRepository|array
     */
    public function getCountryVendors(int $countryId, int $page = 1, $ipp = 12)
    {
        return $this->apiCall('/countries/%countryId%/vendors?page=%page%&itemsPerPage=%ipp%&locale=%locale%',
            compact('countryId', 'page', 'ipp'));
    }

    /**
     * Top places without airports
     *
     * @param int|null $countryId
     * @param int      $ipp
     *
     * @return AsyncApiRepository|array
     */
    public function getTopPlaces(int $countryId = null, $ipp = 100)
    {
        if ($countryId) {
            return $this->apiCall('/countries/%countryId%/places?grade=A&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp', 'countryId'));
        } else {
            return $this->apiCall('/places?grade=A&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp'));
        }

    }

    /**
     * @param int|null $countryId
     * @param int      $ipp
     *
     * @return AsyncApiRepository|array
     */
    public function getTopPorts(int $countryId = null, $ipp = 100)
    {
        if ($countryId) {
            return $this->apiCall('/countries/%countryId%/places?type=port&grade=A&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp', 'countryId'));
        } else {
            return $this->apiCall('/places?type=port%&grade=A&itemsPerPage=%ipp%&locale=%locale%', compact('ipp'));
        }

    }

    /**
     * @param int|null $countryId
     * @param int      $ipp
     *
     * @return AsyncApiRepository|array
     */

    public function getTopTrainStations(int $countryId = null, $ipp = 100)
    {
        if ($countryId) {
            return $this->apiCall('/countries/%countryId%/places?type=train_station&grade=A&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp', 'countryId'));
        } else {
            return $this->apiCall('/places?type=train_station%&grade=A&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp'));
        }

    }

    /**
     * @param int|null $countryId
     * @param int      $ipp
     *
     * @return AsyncApiRepository|array
     */
    public function getTopAirports(int $countryId = null, $ipp = 100)
    {
        if ($countryId) {
            return $this->apiCall('/countries/%countryId%/places?type=airport&grade=A&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp', 'countryId'));
        } else {
            return $this->apiCall('/places?type=airport%&grade=A&itemsPerPage=%ipp%&locale=%locale%', compact('ipp'));
        }

    }


    /**
     * @param int|null $countryId
     * @param int      $ipp
     * @param int      $page
     *
     * @return AsyncApiRepository|array
     */
    public function getTopCities(int $countryId = null, $ipp = 100, $page = 1)
    {
        if ($countryId) {
            return $this->apiCall('/countries/%countryId%/cities?rating%5Bgte%5D=1&itemsPerPage=%ipp%&locale=%locale%',
                compact('ipp', 'countryId'));
        } else {
            return $this->apiCall('/cities?rating%5Bgte%5D=300&page=%page%&itemsPerPage=%ipp%&locale=%locale%',
                compact('page', 'ipp'));
        }
    }

    /**
     * @param int $cityId
     *
     * @return AsyncApiRepository|array
     */
    public function getCityPlaces(int $cityId)
    {
        return $this->apiCall('/cities/%cityId%/places?page=1&itemsPerPage=30&locale=%locale%', compact('cityId'));
    }


    /**
     * @param int $cityId
     *
     * @return AsyncApiRepository|array
     */
    public function getCityVendors(int $cityId)
    {
        return $this->apiCall('/cities/%cityId%/vendors?locale=%locale%', compact('cityId'));
    }

    /**
     * @param int $placeId
     *
     * @return AsyncApiRepository|array
     */
    public function getPlace(int $placeId)
    {
        return $this->apiCall('/places/%placeId%?locale=%locale%', compact('placeId'));
    }

    /**
     * @param int $placeId
     *
     * @return AsyncApiRepository|array
     */
    public function getPlaceVendors(int $placeId)
    {
        return $this->apiCall('/places/%placeId%/vendors?page=1&itemsPerPage=100&locale=%locale%', compact('placeId'));
    }

    /**
     * @param int $placeId
     *
     * @return AsyncApiRepository|array
     */
    public function getCountryPlaceVendors(int $countryId, int $vendorId, int $page = 1, int $ipp = 100)
    {
        return $this->apiCall('/place_vendors?exists%5Bplace.page%5D=true&country.id=%countryId%&vendor.id=%vendorId%&page=%page%&itemsPerPage=%ipp%&locale=%locale%',
            compact('countryId', 'vendorId', 'page', 'ipp'));
    }

    /**
     * @param     $blockType
     * @param     $id
     * @param int $itemPerGroup
     *
     * @return AsyncApiRepository|array
     */
    public function getCarsBlock($blockType, $id, $itemPerGroup = 6)
    {
        return $this->apiCall('/cars_blocks?type=%blockType%&id=%id%&itemsPerGroup=%itemPerGroup%&locale=%locale%',
            compact('blockType', 'id', 'itemPerGroup'));
    }

    /**
     * @param int $blockType
     * @param int $id
     * @param string $tail
     * @param int $itemPerGroup
     *
     * @return mixed
     */
    public function getTailCarsBlock($blockType, $id, $tail, $itemPerGroup = 6)
    {
        return $this->apiCall('/cars_blocks?tail=%tail%&type=%blockType%&id=%id%&itemsPerGroup=%itemPerGroup%&locale=%locale%',
            compact('tail', 'blockType', 'id', 'itemPerGroup'));
    }

}