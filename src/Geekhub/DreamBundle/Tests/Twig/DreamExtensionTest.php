<?php

namespace Geekhub\DreamBundle\Tests\Twig;

use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\FinancialContribute;
use Geekhub\DreamBundle\Entity\FinancialResource;
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

        $extension = $this->getMock('DreamExtension');
        $realPercentCompleted = $extension->showPercentOfCompletionFinancial($dream);

        $this->assertEquals($percentCompleted, $realPercentCompleted);
    }

    public function dreamFinancialContributionsProvider()
    {
    	$dream1 = new Dream();
    	$resource1 = new FinancialResource();
    	$resource1->setTitle("money");
        $resource1->setQuantity(1000);
        $contribute1 = new FinancialContribute();
    	$contribute1->setFinancialResource($resource1);
        $contribute1->setQuantity(500);
        $contribute2 = new FinancialContribute();
        $contribute2->setFinancialResource($resource1);
        $contribute2->setQuantity(500);
        $dream1->addDreamFinancialContribution($contribute1);
        $dream1->addDreamFinancialContribution($contribute2);
        $dream1->addDreamFinancialResource($resource1);

        $dream2 = new Dream();
        $resource2 = new FinancialResource();
        $resource2->setTitle("money");
        $resource2->setQuantity(1000);
        $contribute3 = new FinancialContribute();
        $contribute3->setFinancialResource($resource2);
        $contribute3->setQuantity(500);
        $contribute4 = new FinancialContribute();
        $contribute4->setFinancialResource($resource2);
        $contribute4->setQuantity(400);
        $dream1->addDreamFinancialContribution($contribute3);
        $dream1->addDreamFinancialContribution($contribute4);
        $dream1->addDreamFinancialResource($resource2);

        return array(
    		array($dream1, 100),
    		array($dream2, 90),
    	);
    }


}
