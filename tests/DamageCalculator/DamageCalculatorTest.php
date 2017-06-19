<?php


namespace Tests\DamageCalculator;


use App\DamageCalculator\DamageCalculator;
use PHPUnit\Framework\TestCase;

class DamageCalculatorTest extends TestCase
{
    /** @var DamageCalculator */
    private $calculator;

    protected function setUp() : void
    {
        $this->calculator = new DamageCalculator();
    }

    /**
     * @test
     * @dataProvider validSpell
     */
    public function willReturnCorrectDamage(string $spell, int $damage) : void
    {
        $this->assertSame($damage, $this->calculator->calculate($spell));
    }

    /**
     * @test
     * @dataProvider invalidSpell
     */
    public function willReturnZero(string $spell) : void
    {
        $this->assertSame(0, $this->calculator->calculate($spell));
    }

    public function validSpell() : array
    {
        return [
            'validData1' => ['fejeneai', 7],
            'validData2' => ['fedaineai', 10],
            'validData5' => ['xxxxxfejejeeaindaiyaiaixxxxxx', 17],
            'validData6' => ['feeai', 2],
            'validData7' => ['feaineain', 7],
            'validData8' => ['fdafafeajain', 1],
            'validData9' => ['fexxxxxxxxxxai', 0],
            'validData10' => ['aifejejeeai', 8],
        ];
    }

    public function invalidSpell() : array
    {
        return [
            'invalidData1' => ['jeneai'],
            'invalidData2' => ['fenea'],
            'invalidData3' => ['aineaife'],
            'invalidData5' => ['nenefejefeneiai'],
            'invalidData6' => ['ajejejefe'],
        ];
    }
}
