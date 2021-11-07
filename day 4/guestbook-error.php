$host = 'localhost';
if(!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'w'));
fwrite(STDOUT, "hostname at the beginning of 'set' command "); fwrite(STDOUT,$host);
fwrite(STDOUT, "\n");