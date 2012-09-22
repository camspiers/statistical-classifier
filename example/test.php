<?php

require '../vendor/autoload.php';

ini_set('memory_limit', -1);

use Camspiers\StatisticalClassifier\Classifiers\NaiveBayes;
use Camspiers\StatisticalClassifier\DataSource\Json;
use Camspiers\StatisticalClassifier\Tokenizers\Word;

if (file_exists('classifier2.cache')) {
    $classifier2 = unserialize(file_get_contents('classifier2.cache'));
} else {
    $classifier2 = new NaiveBayes(
        new Json('vsa.json'),
        new Word(),
        15
    );

    file_put_contents('classifier2.cache', serialize($classifier2));
}

$documents = array(
    "vagina preteen xxx",
    "http://www.kdkokdofkasd.com - kdkokokokoko <a href=http://www.kdkokdofkasd.com>kdkokokokoko</a> http://www.kdkokdofkasd.com",
    "Well done Shona, good to hear news of you again. Have a look at my website for some of my S A pics., in book form now. Love to you both, Anthony and Sandra.",
    "Dear Aaron,

Thank you for helping in promoting simple people of Papua New Guinea on this blog. We appreciate you visiting us and telling the world about us. We hope your experiences in Papua New Guinea will be with you for life.

Come visit us again in the future.",
    "What a great, lively photo! It looks like the women really take pride in Smolbag. I can't wait to see more photos in the future!",
    "http://paydayloansloanlendersinstantadvancenofax.com/#544015 - payday loan lenders , <a href=http://paydayloansloanlendersinstantadvancenofax.com/#613560>no fax payday loans</a> - http://paydayloansloanlendersinstantadvancenofax.com/#857177 no fax payday loans",
    "Children's books also arrived recently at other primary schools in Choiseul province to be distributed by VSA volunteers Scott Butcher and Margrit Rohs. These were part of a consignment of 27,000 childrens books donated by Wheeler Books in Auckland. I organise shipments which are usually transported by the RNZAF. 

If anyone would like to donate books, they need to be in good condition, not more than 5 years old (i.e. information in them not out of date), and to reach me at Tauranga City Libraries. Cheers, Jill"
);

foreach ($documents as $index => $document) {
    if ($classifier2->isSpam(0.9999, $document)) {
        echo "$index is spam", PHP_EOL;
    } else {
        echo "$index is not spam", PHP_EOL;
    }
}

