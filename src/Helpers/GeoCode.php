<?php

namespace Diviky\Security\Helpers;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class GeoCode
{
    public function geocode($address = null)
    {
        if (null === $address) {
            return [];
        }

        $results = app('geocoder')->geocode($address)->get();

        try {
            $results = app('geocoder')->geocode($address)->get();
        } catch (\Exception $e) {
            return [];
        }

        $result = [];
        foreach ($results as $value) {
            try {
                $region = $value->getAdminLevels()->get(1);
            } catch (\Exception $e) {
                $region = false;
            }

            $data = $value->toArray();

            $result['provider']     = $data['providedBy'];
            $result['latitude']     = $data['latitude'];
            $result['longitude']    = $data['longitude'];
            $result['country']      = $data['country'];
            $result['country_code'] = $data['countryCode'];
            $result['city']         = $data['locality'];
            $result['region']       = ($region) ? $region->getName() : '';
            $result['region_code']  = ($region) ? $region->getCode() : '';
            $result['zipcode']      = $data['postalCode'];
            $result['locality']     = $data['locality'];
            $result['timezone']     = $data['timezone'];
        }

        return \array_map('trim', $result);
    }
}
