<?php

require_once __DIR__ . '/../src/bootstrap.php';

mb_internal_encoding('UTF-8');

use Camspiers\StatisticalClassifier\DataSource\DataArray;
use Camspiers\StatisticalClassifier\Index\CachedIndex;
use Camspiers\StatisticalClassifier\Classifier\ComplementNaiveBayes;

$c = new StatisticalClassifierServiceContainer;
$d = new DataArray();

if (!file_exists(__DIR__ . '/../resources/language-samples')) {
    throw new Exception('Please extract language-samples.zip in resources/');
}

foreach (glob(__DIR__ . '/../resources/language-samples/*') as $file) {
    $d->addDocument(basename($file), file_get_contents($file));
}

$nb = new ComplementNaiveBayes(
    new CachedIndex(
        'language',
        $c->get('cache'),
        $d
    )
);

$examples = array(
    "Agricultura (-ae, f.), sensu latissimo, est summa omnium artium et scientiarum et technologiarum quae de terris colendis et animalibus creandis curant, ut poma, frumenta, charas, carnes, textilia, et aliae res e terra bene producantur. Specialius, agronomia est ars et scientia quae terris colendis student, agricultio autem animalibus creandis.",
    "El llatí és una llengua indoeuropea de la branca itàlica, parlada antigament pels romans. A partir de l'evolució de la seva versió vulgar en sorgiren les llengües romàniques que sobreviuen avui dia.",
    "hola",
    "Hi there, this is a tiny text",
    "* This file implements in memory hash tables with insert/del/replace/find/
             * get-random-element operations. Hash tables will auto resize if needed
              * tables of power of two in size are used, collisions are handled by
               * chaining. See the source code for more information... :)",
    "House of Cards is an American political drama series developed and produced by Beau Willimon. It is an adaptation of a previous BBC miniseries of the same name which is based on the novel by Michael Dobbs. The entire first season premiered on February 1, 2013, on the streaming service Netflix.[1] A second season of 13 episodes is currently in production.[1][2]"
);

foreach ($examples as $example) {
    echo $nb->classify($example), PHP_EOL;
}