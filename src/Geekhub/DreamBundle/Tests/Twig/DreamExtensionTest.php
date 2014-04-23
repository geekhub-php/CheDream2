<?php

namespace Geekhub\DreamBundle\Tests\Twig;

use Geekhub\DreamBundle\Twig\DreamExtension;
use Doctrine\Bundle\DoctrineBundle\Registry;

class DreamExtensionTest extends \PHPUnit_Framework_TestCase
{

   /**
     * @dataProvider limitedWordsProvider
     */
    public function testDisplayLimitWord($inputText, $outputText, $wordsNumber)
    {

        $limitedText = DreamExtension::displayLimitWord($inputText, $wordsNumber);

        $this->assertEquals($outputText, $limitedText);
    }

    public function limitedWordsProvider()
    {
    	return array(
    		array("word1 word2, word3 word4", "word1 word2, word3 ...", 3),
    		array("word1 word2, word3 word4 word5 word6", "word1 word2, word3 ...", 3),
    		array("word1 word2, word3 word4 word5 word6", "word1 word2, word3 word4 ...", 4),
    	);
    }

       /**
     * @dataProvider dreamFinancialContributionsProvider
     */
    public function testShowPercentOfCompletionFinancial($dream, $percentCompleted)
    {

        $realPercentCompleted = DreamExtension::showPercentOfCompletionFinancial($inputText, $wordsNumber);

        $this->assertEquals($percentCompleted, $realPercentCompleted);
    }

        public function dreamFinancialContributionsProvider()
    {
    	$dream1 = new Dream();
    	$resource1 = new FinancialResource();
    	$resource1->setTitle("money");
    	$contribute1 = new FinancialContribute();
    	$contribute1->setFinancialResource($resource1);


    	return array(
    		array($dream1, 75),
    		array($dream2, 85),
    	);
    }


}
