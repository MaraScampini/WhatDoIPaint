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
            ['id' => self::$LEVEL_RELAX, 'label' => 'Relaxed'],
            ['id' => self::$LEVEL_FOCUSED, 'label' => 'Focused'],
            ['id' => self::$LEVEL_ALL_OUT, 'label' => 'All out'],
            ['id' => self::$LEVEL_COMPETITION, 'label' => 'Competition'],
            ['id' => self::$LEVEL_ARMY_PAINTING, 'label' => 'Army painting']
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

    public static function getTechniquesSelector(): array
    {
        return [
            ['id' => self::$TECHNIQUE_LAYERING, 'value' => self::$TECHNIQUE_LAYERING, 'label' => 'Layering'],
            ['id' => self::$TECHNIQUE_NMM, 'value' => self::$TECHNIQUE_NMM, 'label' => 'Non-Metallic Metal (NMM)'],
            ['id' => self::$TECHNIQUE_OSL, 'value' => self::$TECHNIQUE_OSL, 'label' => 'Object Source Lighting (OSL)'],
            ['id' => self::$TECHNIQUE_SKIN, 'value' => self::$TECHNIQUE_SKIN, 'label' => 'Skin'],
            ['id' => self::$TECHNIQUE_FABRIC, 'value' => self::$TECHNIQUE_FABRIC, 'label' => 'Fabric'],
            ['id' => self::$TECHNIQUE_LEATHER, 'value' => self::$TECHNIQUE_LEATHER, 'label' => 'Leather'],
            ['id' => self::$TECHNIQUE_GEMS, 'value' => self::$TECHNIQUE_GEMS, 'label' => 'Gems'],
            ['id' => self::$TECHNIQUE_WEATHERING, 'value' => self::$TECHNIQUE_WEATHERING, 'label' => 'Weathering'],
            ['id' => self::$TECHNIQUE_WET_BLENDING, 'value' => self::$TECHNIQUE_WET_BLENDING, 'label' => 'Wet blending'],
            ['id' => self::$TECHNIQUE_DRY_BRUSH, 'value' => self::$TECHNIQUE_DRY_BRUSH, 'label' => 'Dry brush'],
            ['id' => self::$TECHNIQUE_BASING, 'value' => self::$TECHNIQUE_BASING, 'label' => 'Basing'],
            ['id' => self::$TECHNIQUE_STIPPLED_BLENDING, 'value' => self::$TECHNIQUE_STIPPLED_BLENDING, 'label' => 'Stippled blending'],
            ['id' => self::$TECHNIQUE_TMM, 'value' => self::$TECHNIQUE_TMM, 'label' => 'True Metallic Metal (TMM)'],
            ['id' => self::$TECHNIQUE_HAIR_FUR, 'value' => self::$TECHNIQUE_HAIR_FUR, 'label' => 'Hair or animal fur']
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