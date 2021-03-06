<?php

namespace Intern;

class AcademicMajorList {

    private $majors;

    /**
     * Creates an AcademicMajorList object given an array of stdClass objects containing
     * undergraduate and graduate majors, and potentially including duplicates (from BannerMajorsProvider).
     *
     * @see BannerMajorsProvider
     */
    public function __construct()
    {
        $this->majors = array();
    }

    public function addMajorsArray(Array $majorsArray)
    {
        // Add each of the given majors to list, checking for duplicates
        foreach($majorsArray as $major){
            $this->addIfNotDuplicate($major);
        }
    }

    public function addMajor(AcademicMajor $major)
    {
        $this->majors[] = $major;
    }

    public function getMajorsByLevel(string $level): array
    {
        $filteredMajors = array();

        foreach($this->majors as $m){
            if($m->getLevel() === $level){
                $filteredMajors[] = $m;
            }
        }

        $this->sortList($filteredMajors);

        return $filteredMajors;
    }

    /**
     * Adds the array represnting a major to the set of majors if it is not already in the list.
     * Prevents duplciate major arrays from being added to the list.
     *
     * @param Array $major The array holding a single major
     */
    public function addIfNotDuplicate(AcademicMajor $major)
    {
        // Look through each sub-array in the set of majors
        foreach($this->majors as $m){
            // If the sub-array we're looking at matches the single major we were given, then we've
            // found a duplicate and we can stop looking any further
            if($m->getLevel() === $major->getLevel()
                && $m->getDescription() === $major->getDescription()){
                return;
            }
        }

        // If we didn't find any duplicates (i.e. $major did not exist in $destArray), then add it
        $this->majors[] = $major;
    }

    private function sortList(&$list)
    {
        usort($list, array('self', 'compareFunc'));
    }

    public static function compareFunc($a, $b)
    {
        return strcasecmp($a->getDescription(), $b->getDescription());
    }
}
