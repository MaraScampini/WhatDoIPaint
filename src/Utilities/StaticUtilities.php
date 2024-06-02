<?php

namespace App\Utilities;

class StaticUtilities
{
    // LEVELS

    public static int $LEVEL_RELAX = 1;
    public static int $LEVEL_FOCUSED = 2;
    public static int $LEVEL_ALL_OUT = 3;
    public static int $LEVEL_COMPETITION = 4;
    public static int $LEVEL_ARMY_PAINTING = 5;

    public static function getLevelSelector(): array
    {
        return [
            ['id' => self::$LEVEL_RELAX, 'name' => 'Relaxed'],
            ['id' => self::$LEVEL_FOCUSED, 'name' => 'Focused'],
            ['id' => self::$LEVEL_ALL_OUT, 'name' => 'All out'],
            ['id' => self::$LEVEL_COMPETITION, 'name' => 'Competition'],
            ['id' => self::$LEVEL_ARMY_PAINTING, 'name' => 'Army painting']
        ];
    }

    // TECHNIQUES

    public static int $TECHNIQUE_LAYERING = 1;
    public static int $TECHNIQUE_NMM = 2;
    public static int $TECHNIQUE_OSL = 3;
    public static int $TECHNIQUE_SKIN = 4;
    public static int $TECHNIQUE_FABRIC = 5;
    public static int $TECHNIQUE_LEATHER = 6;
    public static int $TECHNIQUE_GEMS = 7;
    public static int $TECHNIQUE_WEATHERING = 8;
    public static int $TECHNIQUE_WET_BLENDING = 9;
    public static int $TECHNIQUE_DRY_BRUSH = 10;
    public static int $TECHNIQUE_BASING = 11;
    public static int $TECHNIQUE_STIPPLED_BLENDING = 12;
    public static int $TECHNIQUE_TMM = 13;
    public static int $TECHNIQUE_HAIR_FUR = 14;

    public static function techniqueSelector(): array
    {
        return [
            ['id' => self::$TECHNIQUE_LAYERING, 'name' => 'Layering'],
            ['id' => self::$TECHNIQUE_NMM, 'name' => 'Non-Metallic Metal (NMM)'],
            ['id' => self::$TECHNIQUE_OSL, 'name' => 'Object Source Lighting (OSL)'],
            ['id' => self::$TECHNIQUE_SKIN, 'name' => 'Skin'],
            ['id' => self::$TECHNIQUE_FABRIC, 'name' => 'Fabric'],
            ['id' => self::$TECHNIQUE_LEATHER, 'name' => 'Leather'],
            ['id' => self::$TECHNIQUE_GEMS, 'name' => 'Gems'],
            ['id' => self::$TECHNIQUE_WEATHERING, 'name' => 'Weathering'],
            ['id' => self::$TECHNIQUE_WET_BLENDING, 'name' => 'Wet blending'],
            ['id' => self::$TECHNIQUE_DRY_BRUSH, 'name' => 'Dry brush'],
            ['id' => self::$TECHNIQUE_BASING, 'name' => 'Basing'],
            ['id' => self::$TECHNIQUE_STIPPLED_BLENDING, 'name' => 'Stippled blending'],
            ['id' => self::$TECHNIQUE_TMM, 'name' => 'True Metallic Metal (TMM)'],
            ['id' => self::$TECHNIQUE_HAIR_FUR, 'name' => 'Hair or animal fur']
        ];
    }


    // STATUS

    public static int $STATUS_BOX = 1;
    public static int $STATUS_SPRUE = 2;
    public static int $STATUS_PRINTED = 3;
    public static int $STATUS_ASSEMBLED = 4;
    public static int $STATUS_PRIMED = 5;
    public static int $STATUS_PAINTED = 6;
    public static int $STATUS_FINISHED = 7;
    public static int $STATUS_HALF_PAINTED = 8;
    public static int $STATUS_ON_HOLD = 9;

    public static function getStatusSelector(): array
    {
        return [
            ['id' => self::$STATUS_BOX, 'name' => 'Box'],
            ['id' => self::$STATUS_SPRUE, 'name' => 'Sprue'],
            ['id' => self::$STATUS_PRINTED, 'name' => 'Printed'],
            ['id' => self::$STATUS_ASSEMBLED, 'name' => 'Assembled'],
            ['id' => self::$STATUS_PRIMED, 'name' => 'Primed'],
            ['id' => self::$STATUS_HALF_PAINTED, 'name' => 'Half painted'],
            ['id' => self::$STATUS_ON_HOLD, 'name' => 'On hold'],
            ['id' => self::$STATUS_PAINTED, 'name' => 'Painted'],
            ['id' => self::$STATUS_FINISHED, 'name' => 'Finished'],
        ];
    }

}