<?php

namespace App\EntityListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Etape;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\EntityManager;
use App\Entity\Couleur;

class EtapeListener {
    
    private $em;
    
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

        /** @PrePersist */
    public function prePersistHandler(Etape $etape, LifecycleEventArgs $event) {
        $couleurs = $this->getCouleurRepository()->findAll();
        $key = array_rand($couleurs);
        $etape->setCouleur($couleurs[$key]);
    }
    
    private function getCouleurRepository(){
        return $this->em->getRepository(Couleur::class);
    }
}
