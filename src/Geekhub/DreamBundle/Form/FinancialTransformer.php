<?php
/**
 * Created by PhpStorm.
 * File: FinancialTransformer.php
 * User: Yuriy Tarnavskiy
 * Date: 17.02.14
 * Time: 16:05
 */

namespace Geekhub\DreamBundle\Form;

use Geekhub\DreamBundle\Entity\Dream;
use Geekhub\DreamBundle\Entity\DreamResource;
use Symfony\Component\Form\DataTransformerInterface;

class FinancialTransformer implements DataTransformerInterface
{
    protected $dream;

    public function __construct(Dream $dream)
    {
        $this->dream = $dream;
    }

    public function transform($tags)
    {
        return null;
    }

    public function reverseTransform($financialArrayCollection)
    {
        foreach($financialArrayCollection as $finance)
        {
            $finance->setDream($this->dream);
            $this->dream->addDreamResource($finance);
        }

        return null;
    }
}
