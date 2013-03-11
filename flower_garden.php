<?php

/*
Sub problem: make a garden of n - 1
How does i relate to j when j + 1 = i?

if I know how f0 and f1 line up, how can I know where f2 goes? Do you have to know
    how f2 relates to both f0 and f1? If f0 must be before f1 and f2 must got before f0

    f0 < f1 and f2 < f0 does not mean  f2 < f0 < f1?

    f[3, 1, 2] < f[2, 4, 5]
    if we have f[4, 3, 6] even though
        f[4, 3, 6] < f[3, 1, 2]
        f[4, 3, 6] > f[2, 4, 5]

        so we would have f3 f2 f4
    f2 f1 f0
    but if f1 < f2 and f0 < 


*/

class FlowerGarden {

    /**
     * @param height - an array of ints that will have between 2 and 50 
        elements, values between 1 and 1000 inclusive. No repeated elements
     * @param bloom - an array of ints that has same number of elements as 
        height, values range between 1 and 365 inclusive
     * @param wilt - an array of ints that has same number of elements as 
        height, values range between 1 and 365 inclusive. Wilt indicates
        the day of year that the flower will die
     * 
     *
     * For each element i of bloom and wilt, wilt[i] > bloom[i]
     **/
    public function get_ordering(array $height, array $bloom, array $wilt) {
        $flowers = $this->_create_flowers($height, $bloom, $wilt);
        $garden = array(array_pop($flowers));

        while (!empty($flowers)) {

            $current = array_pop($flowers);
            $taller = $current->height > $garden[0]->height;
            $idx = $taller ? count($garden) - 1 : 0;
            $inc = $taller ? -1 : 1;
            $insert_at = -1;


            while ($insert_at < 0 && (($taller && $idx >= 0) || (!$taller && $idx < count($garden)))) {

                $in_garden = $garden[$idx];

                if ($taller && !$this->_can_plant_in_front_of($current, $in_garden)) {
                    $insert_at = $idx;
                } else if (!$taller && $this->_can_plant_in_front_of($current, $in_garden)) {
                    $insert_at = $idx;
                }

                $idx += $inc;
            }

            if ($taller) {

                if ($insert_at < 0) {
                    //we must put this taller one all the way at the front
                    array_unshift($garden, $current);
                } else {
                    $garden = array_merge(
                        array_slice($garden, 0, $insert_at + 1),
                        array($current),
                        array_slice($garden, $insert_at + 1)
                    );
                }

            } else {

                if ($insert_at < 0) {
                    //we must put this in back
                    $garden[] = $current;
                } else {
                    $garden = array_merge(
                        array_slice($garden, 0, $insert_at),
                        array($current),
                        array_slice($garden, $insert_at)
                    );
                }
            }
        }

        return $garden;
    }

    protected function _can_plant_in_front_of($to_plant, $planted) {

        if ($this->_does_overlap($to_plant, $planted)) {
            return $to_plant->height < $planted->height;
        } 

        return $to_plant->height > $planted->height;
    }

    protected function _does_overlap($flower_a, $flower_b) {
        return ($flower_a->bloom >= $flower_b->bloom && $flower_a->bloom <= $flower_b->wilt)
            || ($flower_b->bloom >= $flower_a->bloom && $flower_b->bloom <= $flower_a->wilt);
    }


    protected function _create_flowers(
        array $height, array $bloom, array $wilt) {

        $flowers = array();

        for ($i = 0; $i < count($height); $i++) {
            $flowers[] = new Flower($i, $height[$i], $bloom[$i], $wilt[$i]);
        }

        return $flowers;
    }

}

class Flower {

    public $index;
    public $height;
    public $bloom;
    public $wilt;

    public function __construct($index, $height, $bloom, $wilt) {
        $this->index = $index;
        $this->height = $height;
        $this->bloom = $bloom;
        $this->wilt = $wilt;
    }

}
