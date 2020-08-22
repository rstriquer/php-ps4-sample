<?php
/**
 * App AppModel
 * 
 * @category   Models
 * @package    App\Models
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Models;

use Exception;

trait newUUIDTrait
{
    /**
     * The full path to the hardware generated UUID file
     * @var string
     */
    static string $UUID_FILE = '/proc/sys/kernel/random/uuid';

    /**
     * Get a UYUID from Unix Operational system
     */
    protected function newUUID(): string
    {
        if (!is_file(self::$UUID_FILE)) {
            throw new Exception('File \'' . self::$UUID_FILE .'\' not found');
        }
        return substr(file_get_contents(self::$UUID_FILE), 0, -1);
    }
}
