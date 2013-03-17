<?php

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag;

/**
 * StatisticalClassifierServiceContainer
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 */
class StatisticalClassifierServiceContainer extends Container
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parameters = $this->getDefaultParameters();

        $this->services =
        $this->scopedServices =
        $this->scopeStacks = array();

        $this->set('service_container', $this);

        $this->scopes = array();
        $this->scopeChildren = array();
    }

    /**
     * Gets the 'cache' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return CacheCache\Cache A CacheCache\Cache instance.
     */
    protected function getCacheService()
    {
        return $this->services['cache'] = new \CacheCache\Cache($this->get('cache.backend'));
    }

    /**
     * Gets the 'cache.backend' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return CacheCache\Backends\File A CacheCache\Backends\File instance.
     */
    protected function getCache_BackendService()
    {
        return $this->services['cache.backend'] = new \CacheCache\Backends\File(array('dir' => './resources/indexes/', 'file_extension' => '.idx'));
    }

    /**
     * Gets the 'classifier.naive_bayes' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Classifier\NaiveBayes A Camspiers\StatisticalClassifier\Classifier\NaiveBayes instance.
     */
    protected function getClassifier_NaiveBayesService()
    {
        return $this->services['classifier.naive_bayes'] = new \Camspiers\StatisticalClassifier\Classifier\NaiveBayes($this->get('index.index'), $this->get('tokenizer.word'), $this->get('normalizer.stopword_lowercase'));
    }

    /**
     * Gets the 'console.application' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Application A Camspiers\StatisticalClassifier\Console\Application instance.
     */
    protected function getConsole_ApplicationService()
    {
        $this->services['console.application'] = $instance = new \Camspiers\StatisticalClassifier\Console\Application();

        $instance->add($this->get('console.command.index.create'));
        $instance->add($this->get('console.command.index.remove'));
        $instance->add($this->get('console.command.index.prepare'));
        $instance->add($this->get('console.command.train.document'));
        $instance->add($this->get('console.command.train.director'));
        $instance->add($this->get('console.command.classify'));

        return $instance;
    }

    /**
     * Gets the 'console.command.classify' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Command\ClassifyCommand A Camspiers\StatisticalClassifier\Console\Command\ClassifyCommand instance.
     */
    protected function getConsole_Command_ClassifyService()
    {
        $this->services['console.command.classify'] = $instance = new \Camspiers\StatisticalClassifier\Console\Command\ClassifyCommand();

        $instance->setCache($this->get('cache'));

        return $instance;
    }

    /**
     * Gets the 'console.command.index.create' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Command\Index\CreateCommand A Camspiers\StatisticalClassifier\Console\Command\Index\CreateCommand instance.
     */
    protected function getConsole_Command_Index_CreateService()
    {
        $this->services['console.command.index.create'] = $instance = new \Camspiers\StatisticalClassifier\Console\Command\Index\CreateCommand();

        $instance->setCache($this->get('cache'));

        return $instance;
    }

    /**
     * Gets the 'console.command.index.prepare' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Command\Index\PrepareCommand A Camspiers\StatisticalClassifier\Console\Command\Index\PrepareCommand instance.
     */
    protected function getConsole_Command_Index_PrepareService()
    {
        $this->services['console.command.index.prepare'] = $instance = new \Camspiers\StatisticalClassifier\Console\Command\Index\PrepareCommand();

        $instance->setCache($this->get('cache'));

        return $instance;
    }

    /**
     * Gets the 'console.command.index.remove' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Command\Index\RemoveCommand A Camspiers\StatisticalClassifier\Console\Command\Index\RemoveCommand instance.
     */
    protected function getConsole_Command_Index_RemoveService()
    {
        $this->services['console.command.index.remove'] = $instance = new \Camspiers\StatisticalClassifier\Console\Command\Index\RemoveCommand();

        $instance->setCache($this->get('cache'));

        return $instance;
    }

    /**
     * Gets the 'console.command.train.director' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Command\Train\DirectoryCommand A Camspiers\StatisticalClassifier\Console\Command\Train\DirectoryCommand instance.
     */
    protected function getConsole_Command_Train_DirectorService()
    {
        $this->services['console.command.train.director'] = $instance = new \Camspiers\StatisticalClassifier\Console\Command\Train\DirectoryCommand();

        $instance->setCache($this->get('cache'));

        return $instance;
    }

    /**
     * Gets the 'console.command.train.document' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Console\Command\Train\DocumentCommand A Camspiers\StatisticalClassifier\Console\Command\Train\DocumentCommand instance.
     */
    protected function getConsole_Command_Train_DocumentService()
    {
        $this->services['console.command.train.document'] = $instance = new \Camspiers\StatisticalClassifier\Console\Command\Train\DocumentCommand();

        $instance->setCache($this->get('cache'));

        return $instance;
    }

    /**
     * Gets the 'converter.converter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\DataSource\Converter A Camspiers\StatisticalClassifier\DataSource\Converter instance.
     */
    protected function getConverter_ConverterService()
    {
        return $this->services['converter.converter'] = new \Camspiers\StatisticalClassifier\DataSource\Converter($this->get('converter.from'), $this->get('converter.to'));
    }

    /**
     * Gets the 'converter.from' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getConverter_FromService()
    {
        throw new RuntimeException('You have requested a synthetic service ("converter.from"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'converter.to' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getConverter_ToService()
    {
        throw new RuntimeException('You have requested a synthetic service ("converter.to"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'index.index' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @throws RuntimeException always since this service is expected to be injected dynamically
     */
    protected function getIndex_IndexService()
    {
        throw new RuntimeException('You have requested a synthetic service ("index.index"). The DIC does not know how to construct this service.');
    }

    /**
     * Gets the 'logger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Monolog\Logger A Monolog\Logger instance.
     */
    protected function getLoggerService()
    {
        $this->services['logger'] = $instance = new \Monolog\Logger('classifier');

        $instance->pushHandler($this->get('logger.stream'));

        return $instance;
    }

    /**
     * Gets the 'logger.stream' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Monolog\Handler\StreamHandler A Monolog\Handler\StreamHandler instance.
     */
    protected function getLogger_StreamService()
    {
        return $this->services['logger.stream'] = new \Monolog\Handler\StreamHandler('logs/classifier.log', 100);
    }

    /**
     * Gets the 'normalizer.lowercase' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Lowercase A Camspiers\StatisticalClassifier\Normalizer\Lowercase instance.
     */
    protected function getNormalizer_LowercaseService()
    {
        return $this->services['normalizer.lowercase'] = new \Camspiers\StatisticalClassifier\Normalizer\Lowercase();
    }

    /**
     * Gets the 'normalizer.porter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Porter A Camspiers\StatisticalClassifier\Normalizer\Porter instance.
     */
    protected function getNormalizer_PorterService()
    {
        return $this->services['normalizer.porter'] = new \Camspiers\StatisticalClassifier\Normalizer\Porter();
    }

    /**
     * Gets the 'normalizer.stopword' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Stopword A Camspiers\StatisticalClassifier\Normalizer\Stopword instance.
     */
    protected function getNormalizer_StopwordService()
    {
        return $this->services['normalizer.stopword'] = new \Camspiers\StatisticalClassifier\Normalizer\Stopword(array(0 => 'a\'s', 1 => 'able', 2 => 'about', 3 => 'above', 4 => 'according', 5 => 'accordingly', 6 => 'across', 7 => 'actually', 8 => 'after', 9 => 'afterwards', 10 => 'again', 11 => 'against', 12 => 'ain\'t', 13 => 'all', 14 => 'allow', 15 => 'allows', 16 => 'almost', 17 => 'alone', 18 => 'along', 19 => 'already', 20 => 'also', 21 => 'although', 22 => 'always', 23 => 'am', 24 => 'among', 25 => 'amongst', 26 => 'an', 27 => 'and', 28 => 'another', 29 => 'any', 30 => 'anybody', 31 => 'anyhow', 32 => 'anyone', 33 => 'anything', 34 => 'anyway', 35 => 'anyways', 36 => 'anywhere', 37 => 'apart', 38 => 'appear', 39 => 'appreciate', 40 => 'appropriate', 41 => 'are', 42 => 'aren\'t', 43 => 'around', 44 => 'as', 45 => 'aside', 46 => 'ask', 47 => 'asking', 48 => 'associated', 49 => 'at', 50 => 'available', 51 => 'away', 52 => 'awfully', 53 => 'be', 54 => 'became', 55 => 'because', 56 => 'become', 57 => 'becomes', 58 => 'becoming', 59 => 'been', 60 => 'before', 61 => 'beforehand', 62 => 'behind', 63 => 'being', 64 => 'believe', 65 => 'below', 66 => 'beside', 67 => 'besides', 68 => 'best', 69 => 'better', 70 => 'between', 71 => 'beyond', 72 => 'both', 73 => 'brief', 74 => 'but', 75 => 'by', 76 => 'c\'mon', 77 => 'c\'s', 78 => 'came', 79 => 'can', 80 => 'can\'t', 81 => 'cannot', 82 => 'cant', 83 => 'cause', 84 => 'causes', 85 => 'certain', 86 => 'certainly', 87 => 'changes', 88 => 'clearly', 89 => 'co', 90 => 'com', 91 => 'come', 92 => 'comes', 93 => 'concerning', 94 => 'consequently', 95 => 'consider', 96 => 'considering', 97 => 'contain', 98 => 'containing', 99 => 'contains', 100 => 'corresponding', 101 => 'could', 102 => 'couldn\'t', 103 => 'course', 104 => 'currently', 105 => 'definitely', 106 => 'described', 107 => 'despite', 108 => 'did', 109 => 'didn\'t', 110 => 'different', 111 => 'do', 112 => 'does', 113 => 'doesn\'t', 114 => 'doing', 115 => 'don\'t', 116 => 'done', 117 => 'down', 118 => 'downwards', 119 => 'during', 120 => 'each', 121 => 'edu', 122 => 'eg', 123 => 'eight', 124 => 'either', 125 => 'else', 126 => 'elsewhere', 127 => 'enough', 128 => 'entirely', 129 => 'especially', 130 => 'et', 131 => 'etc', 132 => 'even', 133 => 'ever', 134 => 'every', 135 => 'everybody', 136 => 'everyone', 137 => 'everything', 138 => 'everywhere', 139 => 'ex', 140 => 'exactly', 141 => 'example', 142 => 'except', 143 => 'far', 144 => 'few', 145 => 'fifth', 146 => 'first', 147 => 'five', 148 => 'followed', 149 => 'following', 150 => 'follows', 151 => 'for', 152 => 'former', 153 => 'formerly', 154 => 'forth', 155 => 'four', 156 => 'from', 157 => 'further', 158 => 'furthermore', 159 => 'get', 160 => 'gets', 161 => 'getting', 162 => 'given', 163 => 'gives', 164 => 'go', 165 => 'goes', 166 => 'going', 167 => 'gone', 168 => 'got', 169 => 'gotten', 170 => 'greetings', 171 => 'had', 172 => 'hadn\'t', 173 => 'happens', 174 => 'hardly', 175 => 'has', 176 => 'hasn\'t', 177 => 'have', 178 => 'haven\'t', 179 => 'having', 180 => 'he', 181 => 'he\'s', 182 => 'hello', 183 => 'help', 184 => 'hence', 185 => 'her', 186 => 'here', 187 => 'here\'s', 188 => 'hereafter', 189 => 'hereby', 190 => 'herein', 191 => 'hereupon', 192 => 'hers', 193 => 'herself', 194 => 'hi', 195 => 'him', 196 => 'himself', 197 => 'his', 198 => 'hither', 199 => 'hopefully', 200 => 'how', 201 => 'howbeit', 202 => 'however', 203 => 'i\'d', 204 => 'i\'ll', 205 => 'i\'m', 206 => 'i\'ve', 207 => 'ie', 208 => 'if', 209 => 'ignored', 210 => 'immediate', 211 => 'in', 212 => 'inasmuch', 213 => 'inc', 214 => 'indeed', 215 => 'indicate', 216 => 'indicated', 217 => 'indicates', 218 => 'inner', 219 => 'insofar', 220 => 'instead', 221 => 'into', 222 => 'inward', 223 => 'is', 224 => 'isn\'t', 225 => 'it', 226 => 'it\'d', 227 => 'it\'ll', 228 => 'it\'s', 229 => 'its', 230 => 'itself', 231 => 'just', 232 => 'keep', 233 => 'keeps', 234 => 'kept', 235 => 'know', 236 => 'known', 237 => 'knows', 238 => 'last', 239 => 'lately', 240 => 'later', 241 => 'latter', 242 => 'latterly', 243 => 'least', 244 => 'less', 245 => 'lest', 246 => 'let', 247 => 'let\'s', 248 => 'like', 249 => 'liked', 250 => 'likely', 251 => 'little', 252 => 'look', 253 => 'looking', 254 => 'looks', 255 => 'ltd', 256 => 'mainly', 257 => 'many', 258 => 'may', 259 => 'maybe', 260 => 'me', 261 => 'mean', 262 => 'meanwhile', 263 => 'merely', 264 => 'might', 265 => 'more', 266 => 'moreover', 267 => 'most', 268 => 'mostly', 269 => 'much', 270 => 'must', 271 => 'my', 272 => 'myself', 273 => 'name', 274 => 'namely', 275 => 'nd', 276 => 'near', 277 => 'nearly', 278 => 'necessary', 279 => 'need', 280 => 'needs', 281 => 'neither', 282 => 'never', 283 => 'nevertheless', 284 => 'new', 285 => 'next', 286 => 'nine', 287 => 'no', 288 => 'nobody', 289 => 'non', 290 => 'none', 291 => 'noone', 292 => 'nor', 293 => 'normally', 294 => 'not', 295 => 'nothing', 296 => 'novel', 297 => 'now', 298 => 'nowhere', 299 => 'obviously', 300 => 'of', 301 => 'off', 302 => 'often', 303 => 'oh', 304 => 'ok', 305 => 'okay', 306 => 'old', 307 => 'on', 308 => 'once', 309 => 'one', 310 => 'ones', 311 => 'only', 312 => 'onto', 313 => 'or', 314 => 'other', 315 => 'others', 316 => 'otherwise', 317 => 'ought', 318 => 'our', 319 => 'ours', 320 => 'ourselves', 321 => 'out', 322 => 'outside', 323 => 'over', 324 => 'overall', 325 => 'own', 326 => 'particular', 327 => 'particularly', 328 => 'per', 329 => 'perhaps', 330 => 'placed', 331 => 'please', 332 => 'plus', 333 => 'possible', 334 => 'presumably', 335 => 'probably', 336 => 'provides', 337 => 'que', 338 => 'quite', 339 => 'qv', 340 => 'rather', 341 => 'rd', 342 => 're', 343 => 'really', 344 => 'reasonably', 345 => 'regarding', 346 => 'regardless', 347 => 'regards', 348 => 'relatively', 349 => 'respectively', 350 => 'right', 351 => 'said', 352 => 'same', 353 => 'saw', 354 => 'say', 355 => 'saying', 356 => 'says', 357 => 'second', 358 => 'secondly', 359 => 'see', 360 => 'seeing', 361 => 'seem', 362 => 'seemed', 363 => 'seeming', 364 => 'seems', 365 => 'seen', 366 => 'self', 367 => 'selves', 368 => 'sensible', 369 => 'sent', 370 => 'serious', 371 => 'seriously', 372 => 'seven', 373 => 'several', 374 => 'shall', 375 => 'she', 376 => 'should', 377 => 'shouldn\'t', 378 => 'since', 379 => 'six', 380 => 'so', 381 => 'some', 382 => 'somebody', 383 => 'somehow', 384 => 'someone', 385 => 'something', 386 => 'sometime', 387 => 'sometimes', 388 => 'somewhat', 389 => 'somewhere', 390 => 'soon', 391 => 'sorry', 392 => 'specified', 393 => 'specify', 394 => 'specifying', 395 => 'still', 396 => 'sub', 397 => 'such', 398 => 'sup', 399 => 'sure', 400 => 't\'s', 401 => 'take', 402 => 'taken', 403 => 'tell', 404 => 'tends', 405 => 'th', 406 => 'than', 407 => 'thank', 408 => 'thanks', 409 => 'thanx', 410 => 'that', 411 => 'that\'s', 412 => 'thats', 413 => 'the', 414 => 'their', 415 => 'theirs', 416 => 'them', 417 => 'themselves', 418 => 'then', 419 => 'thence', 420 => 'there', 421 => 'there\'s', 422 => 'thereafter', 423 => 'thereby', 424 => 'therefore', 425 => 'therein', 426 => 'theres', 427 => 'thereupon', 428 => 'these', 429 => 'they', 430 => 'they\'d', 431 => 'they\'ll', 432 => 'they\'re', 433 => 'they\'ve', 434 => 'think', 435 => 'third', 436 => 'this', 437 => 'thorough', 438 => 'thoroughly', 439 => 'those', 440 => 'though', 441 => 'three', 442 => 'through', 443 => 'throughout', 444 => 'thru', 445 => 'thus', 446 => 'to', 447 => 'together', 448 => 'too', 449 => 'took', 450 => 'toward', 451 => 'towards', 452 => 'tried', 453 => 'tries', 454 => 'truly', 455 => 'try', 456 => 'trying', 457 => 'twice', 458 => 'two', 459 => 'un', 460 => 'under', 461 => 'unfortunately', 462 => 'unless', 463 => 'unlikely', 464 => 'until', 465 => 'unto', 466 => 'up', 467 => 'upon', 468 => 'us', 469 => 'use', 470 => 'used', 471 => 'useful', 472 => 'uses', 473 => 'using', 474 => 'usually', 475 => 'value', 476 => 'various', 477 => 'very', 478 => 'via', 479 => 'viz', 480 => 'vs', 481 => 'want', 482 => 'wants', 483 => 'was', 484 => 'wasn\'t', 485 => 'way', 486 => 'we', 487 => 'we\'d', 488 => 'we\'ll', 489 => 'we\'re', 490 => 'we\'ve', 491 => 'welcome', 492 => 'well', 493 => 'went', 494 => 'were', 495 => 'weren\'t', 496 => 'what', 497 => 'what\'s', 498 => 'whatever', 499 => 'when', 500 => 'whence', 501 => 'whenever', 502 => 'where', 503 => 'where\'s', 504 => 'whereafter', 505 => 'whereas', 506 => 'whereby', 507 => 'wherein', 508 => 'whereupon', 509 => 'wherever', 510 => 'whether', 511 => 'which', 512 => 'while', 513 => 'whither', 514 => 'who', 515 => 'who\'s', 516 => 'whoever', 517 => 'whole', 518 => 'whom', 519 => 'whose', 520 => 'why', 521 => 'will', 522 => 'willing', 523 => 'wish', 524 => 'with', 525 => 'within', 526 => 'without', 527 => 'won\'t', 528 => 'wonder', 529 => 'would', 530 => 'wouldn\'t', 531 => 'yes', 532 => 'yet', 533 => 'you', 534 => 'you\'d', 535 => 'you\'ll', 536 => 'you\'re', 537 => 'you\'ve', 538 => 'your', 539 => 'yours', 540 => 'yourself', 541 => 'yourselves', 542 => 'zero'));
    }

    /**
     * Gets the 'normalizer.stopword_lowercase' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Grouped A Camspiers\StatisticalClassifier\Normalizer\Grouped instance.
     */
    protected function getNormalizer_StopwordLowercaseService()
    {
        return $this->services['normalizer.stopword_lowercase'] = new \Camspiers\StatisticalClassifier\Normalizer\Grouped(array(0 => $this->get('normalizer.stopword'), 1 => $this->get('normalizer.lowercase')));
    }

    /**
     * Gets the 'normalizer.stopword_lowercase_porter' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Normalizer\Grouped A Camspiers\StatisticalClassifier\Normalizer\Grouped instance.
     */
    protected function getNormalizer_StopwordLowercasePorterService()
    {
        return $this->services['normalizer.stopword_lowercase_porter'] = new \Camspiers\StatisticalClassifier\Normalizer\Grouped(array(0 => $this->get('normalizer.stopword'), 1 => $this->get('normalizer.lowercase'), 2 => $this->get('normalizer.porter')));
    }

    /**
     * Gets the 'tokenizer.word' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Tokenizer\Word A Camspiers\StatisticalClassifier\Tokenizer\Word instance.
     */
    protected function getTokenizer_WordService()
    {
        return $this->services['tokenizer.word'] = new \Camspiers\StatisticalClassifier\Tokenizer\Word();
    }

    /**
     * Gets the 'transform.dl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\DL A Camspiers\StatisticalClassifier\Transform\DL instance.
     */
    protected function getTransform_DlService()
    {
        return $this->services['transform.dl'] = new \Camspiers\StatisticalClassifier\Transform\DL();
    }

    /**
     * Gets the 'transform.idf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\IDF A Camspiers\StatisticalClassifier\Transform\IDF instance.
     */
    protected function getTransform_IdfService()
    {
        return $this->services['transform.idf'] = new \Camspiers\StatisticalClassifier\Transform\IDF();
    }

    /**
     * Gets the 'transform.tf' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\TF A Camspiers\StatisticalClassifier\Transform\TF instance.
     */
    protected function getTransform_TfService()
    {
        return $this->services['transform.tf'] = new \Camspiers\StatisticalClassifier\Transform\TF();
    }

    /**
     * Gets the 'transform.tf_threaded' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Camspiers\StatisticalClassifier\Transform\TFThreaded A Camspiers\StatisticalClassifier\Transform\TFThreaded instance.
     */
    protected function getTransform_TfThreadedService()
    {
        return $this->services['transform.tf_threaded'] = new \Camspiers\StatisticalClassifier\Transform\TFThreaded();
    }

    /**
     * {@inheritdoc}
     */
    public function getParameter($name)
    {
        $name = strtolower($name);

        if (!(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters))) {
            throw new InvalidArgumentException(sprintf('The parameter "%s" must be defined.', $name));
        }

        return $this->parameters[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function hasParameter($name)
    {
        $name = strtolower($name);

        return isset($this->parameters[$name]) || array_key_exists($name, $this->parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setParameter($name, $value)
    {
        throw new LogicException('Impossible to call set() on a frozen ParameterBag.');
    }

    /**
     * {@inheritDoc}
     */
    public function getParameterBag()
    {
        if (null === $this->parameterBag) {
            $this->parameterBag = new FrozenParameterBag($this->parameters);
        }

        return $this->parameterBag;
    }
    /**
     * Gets the default parameters.
     *
     * @return array An array of the default parameters
     */
    protected function getDefaultParameters()
    {
        return array(
            'stopwords' => array(
                0 => 'a\'s',
                1 => 'able',
                2 => 'about',
                3 => 'above',
                4 => 'according',
                5 => 'accordingly',
                6 => 'across',
                7 => 'actually',
                8 => 'after',
                9 => 'afterwards',
                10 => 'again',
                11 => 'against',
                12 => 'ain\'t',
                13 => 'all',
                14 => 'allow',
                15 => 'allows',
                16 => 'almost',
                17 => 'alone',
                18 => 'along',
                19 => 'already',
                20 => 'also',
                21 => 'although',
                22 => 'always',
                23 => 'am',
                24 => 'among',
                25 => 'amongst',
                26 => 'an',
                27 => 'and',
                28 => 'another',
                29 => 'any',
                30 => 'anybody',
                31 => 'anyhow',
                32 => 'anyone',
                33 => 'anything',
                34 => 'anyway',
                35 => 'anyways',
                36 => 'anywhere',
                37 => 'apart',
                38 => 'appear',
                39 => 'appreciate',
                40 => 'appropriate',
                41 => 'are',
                42 => 'aren\'t',
                43 => 'around',
                44 => 'as',
                45 => 'aside',
                46 => 'ask',
                47 => 'asking',
                48 => 'associated',
                49 => 'at',
                50 => 'available',
                51 => 'away',
                52 => 'awfully',
                53 => 'be',
                54 => 'became',
                55 => 'because',
                56 => 'become',
                57 => 'becomes',
                58 => 'becoming',
                59 => 'been',
                60 => 'before',
                61 => 'beforehand',
                62 => 'behind',
                63 => 'being',
                64 => 'believe',
                65 => 'below',
                66 => 'beside',
                67 => 'besides',
                68 => 'best',
                69 => 'better',
                70 => 'between',
                71 => 'beyond',
                72 => 'both',
                73 => 'brief',
                74 => 'but',
                75 => 'by',
                76 => 'c\'mon',
                77 => 'c\'s',
                78 => 'came',
                79 => 'can',
                80 => 'can\'t',
                81 => 'cannot',
                82 => 'cant',
                83 => 'cause',
                84 => 'causes',
                85 => 'certain',
                86 => 'certainly',
                87 => 'changes',
                88 => 'clearly',
                89 => 'co',
                90 => 'com',
                91 => 'come',
                92 => 'comes',
                93 => 'concerning',
                94 => 'consequently',
                95 => 'consider',
                96 => 'considering',
                97 => 'contain',
                98 => 'containing',
                99 => 'contains',
                100 => 'corresponding',
                101 => 'could',
                102 => 'couldn\'t',
                103 => 'course',
                104 => 'currently',
                105 => 'definitely',
                106 => 'described',
                107 => 'despite',
                108 => 'did',
                109 => 'didn\'t',
                110 => 'different',
                111 => 'do',
                112 => 'does',
                113 => 'doesn\'t',
                114 => 'doing',
                115 => 'don\'t',
                116 => 'done',
                117 => 'down',
                118 => 'downwards',
                119 => 'during',
                120 => 'each',
                121 => 'edu',
                122 => 'eg',
                123 => 'eight',
                124 => 'either',
                125 => 'else',
                126 => 'elsewhere',
                127 => 'enough',
                128 => 'entirely',
                129 => 'especially',
                130 => 'et',
                131 => 'etc',
                132 => 'even',
                133 => 'ever',
                134 => 'every',
                135 => 'everybody',
                136 => 'everyone',
                137 => 'everything',
                138 => 'everywhere',
                139 => 'ex',
                140 => 'exactly',
                141 => 'example',
                142 => 'except',
                143 => 'far',
                144 => 'few',
                145 => 'fifth',
                146 => 'first',
                147 => 'five',
                148 => 'followed',
                149 => 'following',
                150 => 'follows',
                151 => 'for',
                152 => 'former',
                153 => 'formerly',
                154 => 'forth',
                155 => 'four',
                156 => 'from',
                157 => 'further',
                158 => 'furthermore',
                159 => 'get',
                160 => 'gets',
                161 => 'getting',
                162 => 'given',
                163 => 'gives',
                164 => 'go',
                165 => 'goes',
                166 => 'going',
                167 => 'gone',
                168 => 'got',
                169 => 'gotten',
                170 => 'greetings',
                171 => 'had',
                172 => 'hadn\'t',
                173 => 'happens',
                174 => 'hardly',
                175 => 'has',
                176 => 'hasn\'t',
                177 => 'have',
                178 => 'haven\'t',
                179 => 'having',
                180 => 'he',
                181 => 'he\'s',
                182 => 'hello',
                183 => 'help',
                184 => 'hence',
                185 => 'her',
                186 => 'here',
                187 => 'here\'s',
                188 => 'hereafter',
                189 => 'hereby',
                190 => 'herein',
                191 => 'hereupon',
                192 => 'hers',
                193 => 'herself',
                194 => 'hi',
                195 => 'him',
                196 => 'himself',
                197 => 'his',
                198 => 'hither',
                199 => 'hopefully',
                200 => 'how',
                201 => 'howbeit',
                202 => 'however',
                203 => 'i\'d',
                204 => 'i\'ll',
                205 => 'i\'m',
                206 => 'i\'ve',
                207 => 'ie',
                208 => 'if',
                209 => 'ignored',
                210 => 'immediate',
                211 => 'in',
                212 => 'inasmuch',
                213 => 'inc',
                214 => 'indeed',
                215 => 'indicate',
                216 => 'indicated',
                217 => 'indicates',
                218 => 'inner',
                219 => 'insofar',
                220 => 'instead',
                221 => 'into',
                222 => 'inward',
                223 => 'is',
                224 => 'isn\'t',
                225 => 'it',
                226 => 'it\'d',
                227 => 'it\'ll',
                228 => 'it\'s',
                229 => 'its',
                230 => 'itself',
                231 => 'just',
                232 => 'keep',
                233 => 'keeps',
                234 => 'kept',
                235 => 'know',
                236 => 'known',
                237 => 'knows',
                238 => 'last',
                239 => 'lately',
                240 => 'later',
                241 => 'latter',
                242 => 'latterly',
                243 => 'least',
                244 => 'less',
                245 => 'lest',
                246 => 'let',
                247 => 'let\'s',
                248 => 'like',
                249 => 'liked',
                250 => 'likely',
                251 => 'little',
                252 => 'look',
                253 => 'looking',
                254 => 'looks',
                255 => 'ltd',
                256 => 'mainly',
                257 => 'many',
                258 => 'may',
                259 => 'maybe',
                260 => 'me',
                261 => 'mean',
                262 => 'meanwhile',
                263 => 'merely',
                264 => 'might',
                265 => 'more',
                266 => 'moreover',
                267 => 'most',
                268 => 'mostly',
                269 => 'much',
                270 => 'must',
                271 => 'my',
                272 => 'myself',
                273 => 'name',
                274 => 'namely',
                275 => 'nd',
                276 => 'near',
                277 => 'nearly',
                278 => 'necessary',
                279 => 'need',
                280 => 'needs',
                281 => 'neither',
                282 => 'never',
                283 => 'nevertheless',
                284 => 'new',
                285 => 'next',
                286 => 'nine',
                287 => 'no',
                288 => 'nobody',
                289 => 'non',
                290 => 'none',
                291 => 'noone',
                292 => 'nor',
                293 => 'normally',
                294 => 'not',
                295 => 'nothing',
                296 => 'novel',
                297 => 'now',
                298 => 'nowhere',
                299 => 'obviously',
                300 => 'of',
                301 => 'off',
                302 => 'often',
                303 => 'oh',
                304 => 'ok',
                305 => 'okay',
                306 => 'old',
                307 => 'on',
                308 => 'once',
                309 => 'one',
                310 => 'ones',
                311 => 'only',
                312 => 'onto',
                313 => 'or',
                314 => 'other',
                315 => 'others',
                316 => 'otherwise',
                317 => 'ought',
                318 => 'our',
                319 => 'ours',
                320 => 'ourselves',
                321 => 'out',
                322 => 'outside',
                323 => 'over',
                324 => 'overall',
                325 => 'own',
                326 => 'particular',
                327 => 'particularly',
                328 => 'per',
                329 => 'perhaps',
                330 => 'placed',
                331 => 'please',
                332 => 'plus',
                333 => 'possible',
                334 => 'presumably',
                335 => 'probably',
                336 => 'provides',
                337 => 'que',
                338 => 'quite',
                339 => 'qv',
                340 => 'rather',
                341 => 'rd',
                342 => 're',
                343 => 'really',
                344 => 'reasonably',
                345 => 'regarding',
                346 => 'regardless',
                347 => 'regards',
                348 => 'relatively',
                349 => 'respectively',
                350 => 'right',
                351 => 'said',
                352 => 'same',
                353 => 'saw',
                354 => 'say',
                355 => 'saying',
                356 => 'says',
                357 => 'second',
                358 => 'secondly',
                359 => 'see',
                360 => 'seeing',
                361 => 'seem',
                362 => 'seemed',
                363 => 'seeming',
                364 => 'seems',
                365 => 'seen',
                366 => 'self',
                367 => 'selves',
                368 => 'sensible',
                369 => 'sent',
                370 => 'serious',
                371 => 'seriously',
                372 => 'seven',
                373 => 'several',
                374 => 'shall',
                375 => 'she',
                376 => 'should',
                377 => 'shouldn\'t',
                378 => 'since',
                379 => 'six',
                380 => 'so',
                381 => 'some',
                382 => 'somebody',
                383 => 'somehow',
                384 => 'someone',
                385 => 'something',
                386 => 'sometime',
                387 => 'sometimes',
                388 => 'somewhat',
                389 => 'somewhere',
                390 => 'soon',
                391 => 'sorry',
                392 => 'specified',
                393 => 'specify',
                394 => 'specifying',
                395 => 'still',
                396 => 'sub',
                397 => 'such',
                398 => 'sup',
                399 => 'sure',
                400 => 't\'s',
                401 => 'take',
                402 => 'taken',
                403 => 'tell',
                404 => 'tends',
                405 => 'th',
                406 => 'than',
                407 => 'thank',
                408 => 'thanks',
                409 => 'thanx',
                410 => 'that',
                411 => 'that\'s',
                412 => 'thats',
                413 => 'the',
                414 => 'their',
                415 => 'theirs',
                416 => 'them',
                417 => 'themselves',
                418 => 'then',
                419 => 'thence',
                420 => 'there',
                421 => 'there\'s',
                422 => 'thereafter',
                423 => 'thereby',
                424 => 'therefore',
                425 => 'therein',
                426 => 'theres',
                427 => 'thereupon',
                428 => 'these',
                429 => 'they',
                430 => 'they\'d',
                431 => 'they\'ll',
                432 => 'they\'re',
                433 => 'they\'ve',
                434 => 'think',
                435 => 'third',
                436 => 'this',
                437 => 'thorough',
                438 => 'thoroughly',
                439 => 'those',
                440 => 'though',
                441 => 'three',
                442 => 'through',
                443 => 'throughout',
                444 => 'thru',
                445 => 'thus',
                446 => 'to',
                447 => 'together',
                448 => 'too',
                449 => 'took',
                450 => 'toward',
                451 => 'towards',
                452 => 'tried',
                453 => 'tries',
                454 => 'truly',
                455 => 'try',
                456 => 'trying',
                457 => 'twice',
                458 => 'two',
                459 => 'un',
                460 => 'under',
                461 => 'unfortunately',
                462 => 'unless',
                463 => 'unlikely',
                464 => 'until',
                465 => 'unto',
                466 => 'up',
                467 => 'upon',
                468 => 'us',
                469 => 'use',
                470 => 'used',
                471 => 'useful',
                472 => 'uses',
                473 => 'using',
                474 => 'usually',
                475 => 'value',
                476 => 'various',
                477 => 'very',
                478 => 'via',
                479 => 'viz',
                480 => 'vs',
                481 => 'want',
                482 => 'wants',
                483 => 'was',
                484 => 'wasn\'t',
                485 => 'way',
                486 => 'we',
                487 => 'we\'d',
                488 => 'we\'ll',
                489 => 'we\'re',
                490 => 'we\'ve',
                491 => 'welcome',
                492 => 'well',
                493 => 'went',
                494 => 'were',
                495 => 'weren\'t',
                496 => 'what',
                497 => 'what\'s',
                498 => 'whatever',
                499 => 'when',
                500 => 'whence',
                501 => 'whenever',
                502 => 'where',
                503 => 'where\'s',
                504 => 'whereafter',
                505 => 'whereas',
                506 => 'whereby',
                507 => 'wherein',
                508 => 'whereupon',
                509 => 'wherever',
                510 => 'whether',
                511 => 'which',
                512 => 'while',
                513 => 'whither',
                514 => 'who',
                515 => 'who\'s',
                516 => 'whoever',
                517 => 'whole',
                518 => 'whom',
                519 => 'whose',
                520 => 'why',
                521 => 'will',
                522 => 'willing',
                523 => 'wish',
                524 => 'with',
                525 => 'within',
                526 => 'without',
                527 => 'won\'t',
                528 => 'wonder',
                529 => 'would',
                530 => 'wouldn\'t',
                531 => 'yes',
                532 => 'yet',
                533 => 'you',
                534 => 'you\'d',
                535 => 'you\'ll',
                536 => 'you\'re',
                537 => 'you\'ve',
                538 => 'your',
                539 => 'yours',
                540 => 'yourself',
                541 => 'yourselves',
                542 => 'zero',
            ),
            'cache.backend.options' => array(
                'dir' => './resources/indexes/',
                'file_extension' => '.idx',
            ),
        );
    }
}
