<?php

namespace tbQuar\Facades;

use Illuminate\Support\Facades\Facade;
use tbQuar\Generate;

class Quar extends Facade
{
    /**
     * QrCode version constants.
     *
     * QrCode supports 40 versions (1-40); each higher version adds 4 modules
     * per side and therefore more data capacity. These constants are provided
     * for readability, e.g. Quar::version(Quar::VERSION_13). The version()
     * method also accepts any integer between 1 and 40 directly.
     */
    public const VERSION_1 = 1;

    public const VERSION_2 = 2;

    public const VERSION_3 = 3;

    public const VERSION_4 = 4;

    public const VERSION_5 = 5;

    public const VERSION_6 = 6;

    public const VERSION_7 = 7;

    public const VERSION_8 = 8;

    public const VERSION_9 = 9;

    public const VERSION_10 = 10;

    public const VERSION_11 = 11;

    public const VERSION_12 = 12;

    public const VERSION_13 = 13;

    public const VERSION_14 = 14;

    public const VERSION_15 = 15;

    public const VERSION_16 = 16;

    public const VERSION_17 = 17;

    public const VERSION_18 = 18;

    public const VERSION_19 = 19;

    public const VERSION_20 = 20;

    public const VERSION_21 = 21;

    public const VERSION_22 = 22;

    public const VERSION_23 = 23;

    public const VERSION_24 = 24;

    public const VERSION_25 = 25;

    public const VERSION_26 = 26;

    public const VERSION_27 = 27;

    public const VERSION_28 = 28;

    public const VERSION_29 = 29;

    public const VERSION_30 = 30;

    public const VERSION_31 = 31;

    public const VERSION_32 = 32;

    public const VERSION_33 = 33;

    public const VERSION_34 = 34;

    public const VERSION_35 = 35;

    public const VERSION_36 = 36;

    public const VERSION_37 = 37;

    public const VERSION_38 = 38;

    public const VERSION_39 = 39;

    public const VERSION_40 = 40;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        self::clearResolvedInstance(Generate::class);

        return Generate::class;
    }
}
