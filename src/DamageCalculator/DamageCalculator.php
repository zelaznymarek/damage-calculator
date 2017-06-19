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
    private const SUBSPELL_SETS = [
        [
            self::DAI => 5,
            self::AIN => 3,
            self::JEE => 3,
            self::JE => 2,
            self::NE => 2,
            self::AI => 2,
        ],
        [
            self::DAI => 5,
            self::AI => 2,
            self::AIN => 3,
            self::JEE => 3,
            self::JE => 2,
            self::NE => 2,
        ]
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

        $resultArray = [];

        foreach (self::SUBSPELL_SETS as $set) {
            $resultArray[] = $this->calcDmgForSet($spell, $set);
        }

        $spellDamage += $this->compareDmgs($resultArray);

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
            || false === $aiPosition
        ) {

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
     * Calculates dagame for single set.
     */
    private function calcDmgForSet(string $spell, array $set) : int
    {
        $subspellDamage = 0;
        while (strlen($spell) > 1) {
            $subspellFound = false;
            foreach ($set as $subspell => $damage) {
                if (0 === stripos($spell, $subspell)) {
                    $subspellDamage += $damage;
                    $spell = $this->cutSpell($spell, $subspell);
                    $subspellFound = true;
                    break;
                }
            }
            if (!$subspellFound) {
                $spell = $this->walkSpell($spell);
                --$subspellDamage;
            }
        }

        return $subspellDamage;
    }

    /**
     * Removes subspell from the beginning of spell.
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

    /**
     * Compares damages calculated for two possible sets.
     * Returns bigger damage.
     */
    private function compareDmgs(array $dmgs) : int
    {
        return ($dmgs[0] >= $dmgs[1]) ? $dmgs[0] : $dmgs[1];
    }
}
