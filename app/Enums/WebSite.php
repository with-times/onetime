<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static NORMAL()
 * @method static static WEB_ERROR()
 * @method static static RSS_ERROR()
 */
final class WebSite extends Enum
{
    const NORMAL = 0;
    const WEB_ERROR = 1;
    const RSS_ERROR = 2;
}
