<?php

require __DIR__ . '/../vendor/autoload.php';

ini_set('memory_limit', -1);

use Camspiers\StatisticalClassifier\Classifiers\NaiveBayes;
use Camspiers\StatisticalClassifier\DataSource\PDO;
use Camspiers\StatisticalClassifier\DataSource\PDOQuery;
use Camspiers\StatisticalClassifier\DataSource\Json;
use Camspiers\StatisticalClassifier\Tokenizers\Word;
use Camspiers\StatisticalClassifier\Cache\File;

// use CacheCache

$cache = new CacheCache\Cache(new CacheCache\Backends\File(array()));

$classifier = null;

if (isset($argv[1])) {
    $cache->delete('vsa');
}

if ($cache->exists('vsa')) {
    echo 'Exists', PHP_EOL;
    $classifier = $cache->get('vsa');
}

if (!$classifier instanceof NaiveBayes) {

    echo 'No cache', PHP_EOL;

    $pdo = new \PDO('mysql:host=127.0.0.1;dbname=dev_vsa', 'root', 'root');

    $dataSource = new PDO(
        array(
            new PDOQuery('Spam', $pdo, "SELECT Comment FROM PageComment WHERE IsSpam = 1 LIMIT 1000", 'Comment'),
            new PDOQuery('Ham', $pdo, "SELECT Comment FROM PageComment WHERE IsSpam = 000", 'Comment'),
            new PDOQuery('Ham', $pdo, "SELECT CONCAT_WS(' ', Title, Content) AS Document FROM SiteTree_Live LIMIT 1000", 'Document'),
            // new PDOQuery('Ham', $pdo, "SELECT Message FROM ContactSubmission", 'Message'),
            // new PDOQuery('Ham', $pdo, "SELECT MapLeadIn FROM Partner_Live", 'MapLeadIn'),
            // new PDOQuery('Ham', $pdo, "SELECT CONCAT_WS(' ', Overview, LeadIn) AS Document FROM Programme_Live", 'Document')
        )
    );

    $classifier = new NaiveBayes(
        $dataSource,//new Json('vsa.json'),
        new Word(),
        15,
        true,
        false
    );

    $cache->set('vsa', $classifier);

} else {

    echo 'Cached', PHP_EOL;

}

$documents = array(
    "vagina preteen xxx",
    "http://www.kdkokdofkasd.com - kdkokokokoko <a href=http://www.kdkokdofkasd.com>kdkokokokoko</a> http://www.kdkokdofkasd.com",
    "http://paydayloansloanlendersinstantadvancenofax.com/#544015 - payday loan lenders , <a href=http://paydayloansloanlendersinstantadvancenofax.com/#613560>no fax payday loans</a> - http://paydayloansloanlendersinstantadvancenofax.com/#857177 no fax payday loans",
    "Well done Shona, good to hear news of you again. Have a look at my website for some of my S A pics., in book form now. Love to you both, Anthony and Sandra.",
    "Dear Aaron, Thank you for helping in promoting simple people of Papua New Guinea on this blog. We appreciate you visiting us and telling the world about us. We hope your experiences in Papua New Guinea will be with you for life. Come visit us again in the future.",
    "What a great, lively photo! It looks like the women really take pride in Smolbag. I can't wait to see more photos in the future!",
    "Children's books also arrived recently at other primary schools in Choiseul province to be distributed by VSA volunteers Scott Butcher and Margrit Rohs. These were part of a consignment of 27,000 childrens books donated by Wheeler Books in Auckland. I organise shipments which are usually transported by the RNZAF. If anyone would like to donate books, they need to be in good condition, not more than 5 years old (i.e. information in them not out of date), and to reach me at Tauranga City Libraries. Cheers, Jill",
    "Thank you so much for all you have done"
);

foreach ($documents as $index => $document) {
    if ($classifier->isSpam($document)) {
        echo "$index is spam", PHP_EOL;
    } else {
        echo "$index is not spam", PHP_EOL;
    }
}

echo 'Memory usage: ', (memory_get_peak_usage() / pow(1024, 2)), 'MB', PHP_EOL;
