<?php

if (!function_exists('getDataType')) {
    function getDataType($type)
    {
        $data_type_arr = array(
            "exchange" => "Exchange",
            "onedrive" => "Onedrive",
            "sharepoint" => "SharePoint",
            "teams" => "Teams",
            "sharepoint-teams" => "SharePoint-Teams"
        );
        if ($type) {
            if (in_array($type, array_keys($data_type_arr))) {
                return $data_type_arr[$type];
            } else {
                return '';
            }
        } else {
            return $data_type_arr;
        }
    }
}
