<?php
/**
 * @link https://www.acemero.com
 * @see https://www.hybridmlm.io
 * @version 1.00
 * @api Laravel 5.4
 */

namespace App\Blueprint\Facades;

use App\Blueprint\Services\HtmlServices;
use Illuminate\Support\Facades\Facade as ParentFacade;

/**
 * Class HtmlServer
 * @package App\Blueprint\Facades
 */
class HtmlServer extends ParentFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return HtmlServices::class;
    }
}
