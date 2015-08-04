<?php
/**
 * catécorie Commande
 */
namespace Main\CommonBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

/**
 * Commande d'execution : php app/console soutien:store
 * @package RIP
 * @subpackage CommandRipCoeur
 * @category classes
 */
class AvailabilityOldCommand extends ContainerAwareCommand
{
    /**
     * Nom de l'appel de la commande
     */
    protected function configure()
    {
        $this->setName('availability:old');
    }

    /**
     * Exécution de la commande
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $datesService = $this->getContainer()->get('service_availability');
        return $datesService->removeOldDates();
    }
}