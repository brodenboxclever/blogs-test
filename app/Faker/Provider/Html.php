<?php

namespace App\Faker\Provider;

use Faker\Provider\Base;

class Html extends Base
{
    /**
     * Generates a realistic rich text HTML string.
     *
     * @param  int  $minTags  The minimum number of top-level HTML tags to generate.
     * @param  int  $maxTags  The maximum number of top-level HTML tags to generate.
     */
    public function html(int $minTags = 6, int $maxTags = 12): string
    {
        // Always start with a Title (h1 or h2)
        $html = '<h2>'.fake()->sentence().'</h2>'.PHP_EOL;

        $tagCount = static::numberBetween(min($minTags, $maxTags), max($minTags, $maxTags));

        // Generate the remaining tags up to the maximum
        for ($i = 1; $i < $tagCount; $i++) {
            $type = $this->getRichTextTag();

            $html .= match ($type) {
                'p' => $this->generateParagraph(),
                'ul' => $this->generateList('ul'),
                'ol' => $this->generateList('ol'),
                'blockquote' => $this->generateBlockquote(),
                'h3' => '<h3>'.fake()->sentence().'</h3>'.PHP_EOL,
                'h4' => '<h4>'.fake()->sentence().'</h4>'.PHP_EOL,
                'hr' => '<hr>'.PHP_EOL,
            };
        }

        return $html;
    }

    /**
     * Uses a lottery system to return an element type based on weight.
     * Paragraphs heavily outweigh other elements.
     */
    protected function getRichTextTag(): string
    {
        $weights = [
            'p' => 65, // 65% chance
            'ul' => 10, // 10% chance
            'ol' => 10, // 10% chance
            'blockquote' => 5,  // 5% chance
            'h3' => 5,  // 5% chance
            'h4' => 5,  // 5% chance
            'hr' => 5,  // 5% chance
        ];

        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);

        $currentWeight = 0;
        foreach ($weights as $element => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $element;
            }
        }

        return 'p';
    }

    /**
     * Generates a paragraph with occasional bold (<b>) and italic (<i>) text.
     */
    protected function generateParagraph(): string
    {
        $sentences = [];
        $sentenceCount = rand(3, 7);

        for ($i = 0; $i < $sentenceCount; $i++) {
            $sentence = fake()->sentence();

            // 30% chance to inject inline formatting into a sentence
            $chance = rand(1, 100);
            if ($chance <= 15) {
                // Wrap a few words in bold
                $sentence = fake()->words(rand(2, 5), true).' <b>'.fake()->word().'</b> '.fake()->sentence();
            } elseif ($chance > 15 && $chance <= 30) {
                // Wrap a few words in italics
                $sentence = fake()->words(rand(2, 5), true).' <i>'.fake()->word().'</i> '.fake()->sentence();
            }

            $sentences[] = ucfirst(trim($sentence));
        }

        return '<p>'.implode(' ', $sentences).'</p>'.PHP_EOL;
    }

    /**
     * Generates a list (ol/ul) and allows for 2nd and 3rd level nested lists.
     */
    protected function generateList(string $type, int $currentDepth = 1, int $maxDepth = 3): string
    {
        $html = "<{$type}>".PHP_EOL;
        $itemCount = rand(3, 6);

        for ($i = 0; $i < $itemCount; $i++) {
            $html .= '<li>'.fake()->sentence(rand(4, 10));

            // 30% chance to create a nested list if we are under the max depth
            if ($currentDepth < $maxDepth && rand(1, 100) <= 30) {
                $nestedType = rand(0, 1) ? 'ul' : 'ol';
                $html .= PHP_EOL.$this->generateList($nestedType, $currentDepth + 1, $maxDepth);
            }

            $html .= '</li>'.PHP_EOL;
        }

        $html .= "</{$type}>".PHP_EOL;

        return $html;
    }

    /**
     * Generates a blockquote.
     */
    protected function generateBlockquote(): string
    {
        return '<blockquote>'.fake()->paragraph(rand(1, 3)).'</blockquote>'.PHP_EOL;
    }
}
