<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.04.14
 * Time: 10:49
 */

namespace Geekhub\ResourceBundle\Command;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LiipImagesClearCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('liip:cache:clear')
            ->setDescription('Crearing Liip Images cache.')
            ->addArgument('filters', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'Images filters.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filters = $input->getArgument('filters');
        $cachePrefixBase = $this->getContainer()->getParameter('liip_imagine.cache_prefix');
        /** @var CacheManager cacheManager */
        $cacheManager  = $this->getContainer()->get('liip_imagine.cache.manager');

        if (empty($filters)) {
            $filters = null;
            $cacheManager->clearResolversCache($cachePrefixBase);
        } else {
            foreach ($filters as $filter) {
                $cacheManager->clearResolversCache($cachePrefixBase . '/' . $filter .'/');
            }
        }
    }
}
