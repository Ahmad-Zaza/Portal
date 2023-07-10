<?php

return [
    //--- Marketplace Parameters ----------------------------------------------//
    "MARKET_PLACE_TENANT_ID" => env("MARKET_PLACE_TENANT_ID", null),
    "MARKET_PLACE_CLIENT_ID" => env("MARKET_PLACE_CLIENT_ID", null),
    "MARKET_PLACE_SECRET" => env("MARKET_PLACE_SECRET", null),
    "MARKET_PLACE_GRANT_TYPE" => env("MARKET_PLACE_GRANT_TYPE", null),
    "MARKET_PLACE_RESOURCE" => env("MARKET_PLACE_RESOURCE", null),
    //----------------------------------------------------------------------------//


    //--- Partner Center Parameters ----------------------------------------------//
    "PARTNER_CENTER_URL" => env("PARTNER_CENTER_URL", null),
    "PARTNER_CENTER_TENANT_ID" => env("PARTNER_CENTER_TENANT_ID", null),
    "PARTNER_CENTER_CLIENT_ID" => env("PARTNER_CENTER_CLIENT_ID", null),
    "PARTNER_CENTER_SECRET" => env("PARTNER_CENTER_SECRET", null),
    "PARTNER_CENTER_GRANT_TYPE" => env("PARTNER_CENTER_GRANT_TYPE", null),
    "PARTNER_CENTER_RESOURCE" => env("PARTNER_CENTER_RESOURCE", null),
    "PARTNER_CENTER_REFRESH_TOKEN" => env("PARTNER_CENTER_REFRESH_TOKEN", null),
    //----------------------------------------------------------------------------//

    //--- Azure Parameters -------------------------------------------------------//
    "AZURE_URL" => env("AZURE_URL", null),
    "AZURE_CLIENT_ID" => env("AZURE_CLIENT_ID", null),
    "AZURE_SECRET" => env("AZURE_SECRET", null),
    "AZURE_GRANT_TYPE" => env("AZURE_GRANT_TYPE", null),
    "AZURE_RESOURCE" => env("AZURE_RESOURCE", null),
    "AZURE_REFRESH_TOKEN" => env("AZURE_REFRESH_TOKEN", null),
    //----------------------------------------------------------------------------//

    //--- Graph Parameters -------------------------------------------------------//
    "GRAPH_CLIENT_ID" => env("GRAPH_CLIENT_ID", null),
    "GRAPH_SECRET" => env("GRAPH_SECRET", null),
    "GRAPH_RESPONSE_TYPE" => env("GRAPH_RESPONSE_TYPE", null),
    "GRAPH_RESPONSE_MODE" => env("GRAPH_RESPONSE_MODE", null),
    "GRAPH_SCOPE" => env("GRAPH_SCOPE", null),
    "GRAPH_ADMIN_SCOPE" => env("GRAPH_ADMIN_SCOPE", null),
    "GRAPH_REDIRECT_URL" => env("GRAPH_REDIRECT_URL", null),
    "GRAPH_ADMIN_REDIRECT_URL" => env("GRAPH_ADMIN_REDIRECT_URL", null),
    "GRAPH_LOGOUT_URL" => env("GRAPH_LOGOUT_URL", null),
    "GRAPH_API_URL" => env("GRAPH_API_URL",null),
    //----------------------------------------------------------------------------//

    //--- Azure Parameters -------------------------------------------------------//
    "EXPORTED_FILES_EXPIRATION_DAYS" => env("EXPORTED_FILES_EXPIRATION_DAYS", 14),
    "UPLOAD_BLOB_BLOCK_MEGA_SIZE" => env("UPLOAD_BLOB_BLOCK_MEGA_SIZE", 20),
    "MINUTES_BEFORE_BLOB_LINK_EXPIRE" => env("MINUTES_BEFORE_BLOB_LINK_EXPIRE", 60),
    "EXPLORING_RESTORE_ITEMS_WARNING_COUNT" => env("EXPLORING_RESTORE_ITEMS_WARNING_COUNT", 200),
    "EXPLORING_RESTORE_ITEMS_STOPPING_COUNT" => env("EXPLORING_RESTORE_ITEMS_STOPPING_COUNT", 250),
    "EXPLORING_RESTORE_ITEMS_LIMIT_COUNT" => env("EXPLORING_RESTORE_ITEMS_LIMIT_COUNT", 100),
    "DIRECT_DOWNLOAD_MEGA_LIMIT" => env("DIRECT_DOWNLOAD_MEGA_LIMIT", 20),
    "TEMP_BLOB_FILE_EXPIRATION_MINUTES" => env("TEMP_BLOB_FILE_EXPIRATION_MINUTES", 1),
    "DIRECT_DOWNLOAD_ITEMS_COUNT_LIMIT" => env("DIRECT_DOWNLOAD_ITEMS_COUNT_LIMIT", 3),
    "TRIAL_LICENSE_COUNT" => env("TRIAL_LICENSE_COUNT", 25),
    "TRIAL_EXPIRY_DAYS" => env("TRIAL_EXPIRY_DAYS", 30),
    "ALLOWED_RESTORE_DAYS_AFTER_LICENSE_EXPIRED" => env("ALLOWED_RESTORE_DAYS_AFTER_LICENSE_EXPIRED", 15),
    "REMAINING_DAYS_BEFORE_DATA_DELETE_AFTER_EXPIRED" => env("REMAINING_DAYS_BEFORE_DATA_DELETE_AFTER_EXPIRED", 15),
    "EXPLORING_EDISCOVERY_ITEMS_LIMIT_COUNT" => env("EXPLORING_EDISCOVERY_ITEMS_LIMIT_COUNT", 250),
    "INSERTING_EDISCOVERY_ITEMS_LIMIT_COUNT" => env("INSERTING_EDISCOVERY_ITEMS_LIMIT_COUNT", 100),
    //----------------------------------------------------------------------------//

    //--- Veeam Parameters -------------------------------------------------------//
    "VEEAM_AAD_AUTHENTICATION_CERTIFICATE" => env("VEEAM_AAD_AUTHENTICATION_CERTIFICATE", null),
    "VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD" => env("VEEAM_AAD_AUTHENTICATION_CERTIFICATE_PASSWORD", null),
    //----------------------------------------------------------------------------//
];
