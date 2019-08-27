<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Voyage;

class VoyageExtension extends AbstractExtension{
    
    
    private $tokenStorage;
    private $em;
    
    public function __construct(TokenStorageInterface $tokenStorage, EntityManagerInterface $em) {
        
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('dernier3Voyages', [$this, 'getDernier3Voyages']),
        ];
    }

    public function getDernier3Voyages(){
        return $this->getVoyageRepository()->find3Derniers($this->tokenStorage->getToken()->getUser());
    }
    
    private function getVoyageRepository(){
        return $this->em->getRepository(Voyage::class);
    }
}
