# silverstripe-commonancestor

A small utility class that accepts an array of class name, compares their class ancestories, and identifies their closest common ancestor class.

Class is pretty well commented, jumping into it should answer any initial questions.

## Requirements

SilverStripe 4

## Installation

`composer require fromholdio/silverstripe-commonancestor`

## (Contrived) Example

Assuming a page class hierarchy of:

* SiteTree
    * Page
     * BlogHolder
     * BlogPost
       * ArticlePost
       * VideoPost

```php
$exampleOne = [VideoPost::class, ArticlePost::class];
$resultOne = CommonAncestor::get_closest($exampleOne);
// returns 'BlogPost'

$exampleTwo = [VideoPost::class, BlogPost::class];
$resultTwo = CommonAncestor::get_closest($exampleTwo);
// returns 'BlogPost'

$exampleThree = [VideoPost::class, BlogHolder::class];
$resultThree = CommonAncestor::get_closest($exampleThree);
// returns 'Page'
```

The `get_closest` function also accepts a second argument, which allows you to include/exclude classes that have tables (`$tablesOnly`; set to `false` by default).
