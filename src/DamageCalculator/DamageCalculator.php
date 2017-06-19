<?php


namespace App\DamageCalculator;


class DamageCalculator
{
    private const BEGGINING = 'fe';
    private const DAI = 'dai';
    private const AIN = 'ain';
    private const JEE = 'jee';
    private const JE = 'je';
    private const NE = 'ne';
    private const AI = 'ai';

    /** @var array */
    private const SUBSPELL_DMG = [
        self::DAI => 5,
        self::AIN => 3,
        self::JEE => 3,
        self::JE => 2,
        self::NE => 2,
        self::AI => 2,
    ];

    /**
     * Returns the highest damage for given spell.
     */
    public function calculate(string $spell): int
    {
        if (!$this->isValid($spell)) {
            return 0;
        }
        $spellDamage = 1;

        $spell = $this->trimSpell($spell);

        while (strlen($spell) > 1) {
            $subspellFound = false;
            foreach (static::SUBSPELL_DMG as $subspell => $damage) {
                if (0 === stripos($spell, $subspell)) {
                    $spellDamage += $damage;
                    $spell = $this->cutSpell($spell, $subspell);
                    $subspellFound = true;
                    break;
                }
            }
            if (!$subspellFound) {
                $spell = $this->walkSpell($spell);
                --$spellDamage;
            }
        }

        if ($spellDamage < 0) {
            return 0;
        }
        return $spellDamage;
    }

    /**
     * Returns true if spell starts with fe and ends with ai.
     */
    private function isValid(string $spell): bool
    {
        if (substr_count($spell, static::BEGGINING) !== 1) {
            return false;
        }

        $fePosition = stripos($spell, static::BEGGINING);
        $aiPosition = strripos($spell, static::AI);
        if (false === $fePosition
            || false === $aiPosition) {

            return false;
        }

        return $fePosition + 1 < $aiPosition;
    }

    /**
     * Returns spell trimmed to format 'fe......ai'
     */
    private function trimSpell(string $spell): string
    {
        $fePosition = stripos($spell, static::BEGGINING);
        $aiPosition = strripos($spell, static::AI);
        $length = $aiPosition - $fePosition;
        $start = $fePosition + strlen(static::BEGGINING);

        return substr($spell, $start, $length);
    }

    /**
     * Removes subspell from the beginning of spel.
     */
    private function cutSpell(string $spell, string $subspell): string
    {
        return substr($spell, strlen($subspell));
    }

    /**
     * Removes first letter from subspell.
     */
    private function walkSpell(string $spell): string
    {
        return substr($spell, 1);
    }
}
