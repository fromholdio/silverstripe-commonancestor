<?php

namespace Fromholdio\CommonAncestor;

use SilverStripe\Core\ClassInfo;

class CommonAncestor
{
    /**
     * Accepts an array of class names, whose ancestries will be compared, to identify
     * their closest common ancestor class.
     *
     * @param array $classes Array of class names to find a common ancestor for.
     * @param boolean $tablesOnly Only include classes that have a table in the database.
     *
     * @return string Common ancestor class name.
     */
    public static function get_closest(array $classes, $tablesOnly = false): string
    {
        if (empty($classes)) {
            throw new \InvalidArgumentException(
                'You must provide at least one class in your $classes array.'
            );
        }

        foreach ($classes as $class) {
            if (!ClassInfo::exists($class)) {
                throw new \InvalidArgumentException(
                    'Invalid class provided to CommonAncestor::get_closest().'
                    . $class . ' does not exist.'
                );
            }
        }

        if (count($classes) === 1) {
            return reset($classes);
        }

        /*
         * Loop over each class, making a list of all ancestor classes,
         * building a count of how many times each class appears in ancestry.
         */
        $ancestors = [];
        foreach ($classes as $class) {
            $myAncestors = ClassInfo::ancestry($class, $tablesOnly);

            foreach ($myAncestors as $myAncestor) {
                if (isset($ancestors[$myAncestor])) {
                    $ancestors[$myAncestor] = $ancestors[$myAncestor] + 1;
                }
                else {
                    $ancestors[$myAncestor] = 1;
                }
            }
        }

        /*
         * Ancestor classes are common if their count is equal to the total supplied classes
         * (these are the classes that all supplied classes share in their ancestry trees)
         */
        foreach ($ancestors as $ancestorClass => $ancestorCount) {

            // Remove classes that do not appear in ancestry of all supplied classes
            if ($ancestorCount !== count($classes)) {
                unset($ancestors[$ancestorClass]);
            }
        }

        /*
         * If only one ancestor class remains, return that value
         */
        if (count($ancestors) === 1) {
            return current(array_flip($ancestors));
        }

        /*
         * If more than one ancestor class, determine which is closest in hierarchy
         * to the supplied classes. To do this we simply use the first of the supplied
         * classes and walk through its ancestry until we find the first match.
         */
        $myAncestors = array_reverse(ClassInfo::ancestry(reset($classes)), $tablesOnly);
        foreach ($myAncestors as $myAncestor) {
            if (array_key_exists($myAncestor, $ancestors)) {
                return $myAncestor;
            }
        }

        return '';
    }
}
