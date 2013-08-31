<?php

$db_type = 'mysql';
$db_host = 'localhost';
$db_name = 'YOUR_DATABASE_NAME';
$db_username = 'YOUR_DATABASE_USER_NAME';
$db_password = 'YOUR_DATABASE_USER_PASSWORD';
$db_prefix = '';
$p_connect = false;

$base_url = 'http://nco5ranerted3nkt.onion/escrow';

$cookie_name = 'forum_cookie_2641ab';
$cookie_domain = '';
$cookie_path = '/';
$cookie_secure = 0;

$guid = 'PASTE_HERE_YOUR_BLOCKCHAIN_USER_ID';
$blockchainpassword1 = 'PASTE_HERE_YOUR_PRIMARY_BLOCKCHAIN_PASSWORD';
$blockchainRoot = 'https://blockchain.info/merchant/';
$blockchainUserRoot = $blockchainRoot.$guid.'/';

$outgoingaddress = 'YOUR_MAIN_BTC_BLOCKCHAIN_ADDRESS';
$blockchainoutgoingaddresslabel = 'outgoing';
//blockchain fees
$blockchainminimumtransaction = 0.001;
$blockchainfee = 0.0001;
$blockchainmovebetweenwalletsfee = 0.0001;
//gpg
$gpgtempfileurl = '';

define('FORUM', 1);


// Enable DEBUG mode by removing // from the following line
//define('FORUM_DEBUG', 1);

// Enable show DB Queries mode by removing // from the following line
//define('FORUM_SHOW_QUERIES', 1);

// Enable forum IDNA support by removing // from the following line
//define('FORUM_ENABLE_IDNA', 1);

// Disable forum CSRF checking by removing // from the following line
//define('FORUM_DISABLE_CSRF_CONFIRM', 1);

// Disable forum hooks (extensions) by removing // from the following line
//define('FORUM_DISABLE_HOOKS', 1);

// Disable forum output buffering by removing // from the following line
//define('FORUM_DISABLE_BUFFERING', 1);

// Disable forum async JS loader by removing // from the following line
//define('FORUM_DISABLE_ASYNC_JS_LOADER', 1);

// Disable forum extensions version check by removing // from the following line
//define('FORUM_DISABLE_EXTENSIONS_VERSION_CHECK', 1);
