<?php

namespace App;

class BallsLogic
{


    /**
     * Main function that create the groups
     * @param $nrOfColors
     * @param array $input (optional)
     * @return array
     */
    public function run($nrOfColors, $input = []): array
    {

        if (isset($input) && !empty($input) && !$this->checkValidInput($input, $nrOfColors)) {
            return [
                'hasError' => true,
                'error' => 'Input is not valid'
            ];
        }

        if (!isset($nrOfColors) || empty($nrOfColors)) {
            return [
                'hasError' => true,
                'error' => 'Number of colors is not set'
            ];
        }

        $nrOfColors = intval($nrOfColors);


        if (!is_numeric($nrOfColors)) {
            return [
                'hasError' => true,
                'error' => 'Number of colors is not a number'
            ];
        }

        if ($nrOfColors > 10 || $nrOfColors < 1) {
            return [
                'hasError' => true,
                'error' => 'The number of colors is greater than 0 and smaller than 11'
            ];
        }



        if (isset($input) && !empty($input)) {
            $colorDistribution = $input;
        } else {
            $colorDistribution = $this->createColorDistribution($nrOfColors);
        }

        $groups = $this->groupBalls($colorDistribution);

        $isValid = $this->checkIfValid($groups, $colorDistribution);

        if ($isValid) {
            return [
                'hasError' => false,
                'colors' => $colorDistribution,
                'groups' => $groups
            ];
        } else {
            return [
                'hasError' => true,
                'error' => 'Grouping did not pass the validity check'
            ];
        }
    }

    /**
     * Check if the number of balls in the input array is equal to the
     * number of colors squared
     * @param $input
     * @param $nrOfColors
     * @return bool
     */
    private function checkValidInput($input, $nrOfColors): bool
    {
        return $this->countBalls($input) === ($nrOfColors * $nrOfColors);
    }

    /**
     * Create a color distribution based on the number of colors
     *
     * @param $nrOfColors
     * @return array
     */
    private function createColorDistribution($nrOfColors): array
    {
        /*  We add 1 ball to each color because the addRandomBalls function has a non 0 chance
        *  of not adding a ball to a color */
        $colorDistribution = $this->addMinBalls($nrOfColors);
        $colorDistribution = $this->addRandomBalls($colorDistribution, count($colorDistribution));
        $colorDistribution = $this->setColorNames($colorDistribution);

        return $colorDistribution;
    }


    /**Replace the array index with a random color
     *
     * @param $array
     * @return array
     */
    private function setColorNames($array): array
    {
        $colorList = [
            'blue',
            'red',
            'green',
            'purple',
            'black',
            'orange',
            'yellow',
            'gold',
            'white',
            'silver'
        ];
        $newArray = [];
        for ($i = 0; $i < count($array); $i++) {
            $newArray[$colorList[$i]] = $array[$i];
        }

        return $newArray;
    }


    /**
     * Randomly fill the array with balls
     *
     * We chose to add the balls one by one so we do not get a top-heavy distribution
     *
     * @param $array
     * @param $nrOfColors
     * @return array
     */
    private function addRandomBalls($array, $nrOfColors): array
    {
        $maxBalls = $nrOfColors * $nrOfColors;
        while ($this->countBalls($array) != $maxBalls) {
            $array[rand(0, $nrOfColors - 1)] += rand(0, 1);
        }

        return $array;
    }

    /**
     * Add 1 ball for each color
     *
     * @param $nrOfColors
     * @return array
     */
    private function addMinBalls($nrOfColors): array
    {
        $colorDistribution = [];
        for ($i = $nrOfColors; $i > 0; $i--) {
            $colorDistribution[] = 1;
        }

        return $colorDistribution;
    }

    /**
     * Count how many balls are in a array
     *
     * @param $array
     * @return int
     */
    private function countBalls($array): int
    {
        $sum = 0;

        foreach ($array as $val) {
            $sum += $val;
        }

        return $sum;
    }

#### Grouping

    /**
     * Create the grouping of the array per the specifications
     *
     * @param $array
     * @return array
     */
    private function groupBalls($array): array
    {
        asort($array, SORT_NUMERIC);
        $singleArray = $this->concatArrays($array);
        $groups = [];

        while (count($groups) < count($array)) {
            $groups[] = $this->createGroup($singleArray, count($array));
        }


        return $groups;
    }


    /**
     * Flatten the array
     *
     * @param $arrays
     * @return array
     */
    private function concatArrays($arrays): array
    {
        $longArray = [];
        foreach ($arrays as $color => $count) {
            $short = array_fill(0, $count, $color);
            $longArray = array_merge($longArray, $short);
        }

        return $longArray;
    }

    /**
     * Create one group per the specifications
     *
     * Warning! This function uses recursion, be careful with the stack size
     *
     * @param $array
     * @param $nrOfItems
     * @param int $start
     * @return array
     */
    private function createGroup(&$array, $nrOfItems, $start = 0): array
    {
        $list = array_slice($array, $start, $nrOfItems);
        if (count(array_count_values($list)) > 2) {
            return $this->createGroup($array, $nrOfItems, ++$start);
        } else {
            $this->remove($array, $list);
            return $list;
        }
    }

    /**
     * Removes elements from a array in place
     * @param $array
     * @param $toRemove
     */
    private function remove(&$array, $toRemove): void
    {
        foreach ($toRemove as $value) {
            if (($key = array_search($value, $array)) !== false) {
                unset($array[$key]);
            }
        }
    }

    /**
     * Check if the grouping is done per the specifications
     *
     * @param $groups
     * @param $originalDistribution
     * @return bool
     */
    private function checkIfValid($groups, $originalDistribution): bool
    {
        $results = [];

        foreach ($groups as $group) {
            $r = array_count_values($group);
            foreach ($r as $color => $count) {
                if (!isset($results[$color])) {
                    $results[$color] = 0;
                }

                $results[$color] += $count;
            }
        }

        foreach ($originalDistribution as $color => $count) {
            if ($count !== $results[$color]) {
//                echo 'Incorrect count for color ' . $color . PHP_EOL;
                return false;
            }
        }

//        echo "All good" . PHP_EOL;
        return true;
    }
}
