<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Permission;

class PermissionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $permissions = [
        	"GET_SELF_PROBLEME",
        	"GET_OTHER_PROBLEME",
        	"POST_SELF_PROBLEME",
        	"POST_OTHER_PROBLEME",
        	"UPDATE_SELF_PROBLEME",
        	"UPDATE_OTHER_PROBLEME",
        	"DELETE_SELF_PROBLEME",
        	"DELETE_OTHER_PROBLEME",

        	"GET_SELF_PERSONNE",
        	"GET_OTHER_PERSONNE",
        	"POST_SELF_PERSONNE",
        	"POST_OTHER_PERSONNE",
        	"UPDATE_SELF_PERSONNE",
        	"UPDATE_OTHER_PERSONNE",
        	"DELETE_SELF_PERSONNE",
        	"DELETE_OTHER_PERSONNE",

        	"GET_SELF_ROLE",
        	"GET_OTHER_ROLE",
        	"POST_SELF_ROLE",
        	"POST_OTHER_ROLE",
        	"UPDATE_SELF_ROLE",
        	"UPDATE_OTHER_ROLE",
        	"DELETE_SELF_ROLE",
        	"DELETE_OTHER_ROLE",

        	"GET_SELF_HISTORIQUE_ACTION",
        	"GET_OTHER_HISTORIQUE_ACTION",
        	"POST_SELF_HISTORIQUE_ACTION",
        	"POST_OTHER_HISTORIQUE_ACTION",
        	"UPDATE_SELF_HISTORIQUE_ACTION",
        	"UPDATE_OTHER_HISTORIQUE_ACTION",
        	"DELETE_SELF_HISTORIQUE_ACTION",
        	"DELETE_OTHER_HISTORIQUE_ACTION",

        	"GET_SELF_INTERVENTION",
        	"GET_OTHER_INTERVENTION",
        	"POST_SELF_INTERVENTION",
        	"POST_OTHER_INTERVENTION",
        	"UPDATE_SELF_INTERVENTION",
        	"UPDATE_OTHER_INTERVENTION",
        	"DELETE_SELF_INTERVENTION",
        	"DELETE_OTHER_INTERVENTION",

        	"GET_SELF_IMAGE",
        	"GET_OTHER_IMAGE",
        	"POST_SELF_IMAGE",
        	"POST_OTHER_IMAGE",
        	"UPDATE_SELF_IMAGE",
        	"UPDATE_OTHER_IMAGE",
        	"DELETE_SELF_IMAGE",
        	"DELETE_OTHER_IMAGE",

        	"GET_SELF_SERVICE",
        	"GET_OTHER_SERVICE",
        	"POST_SELF_SERVICE",
        	"POST_OTHER_SERVICE",
        	"UPDATE_SELF_SERVICE",
        	"UPDATE_OTHER_SERVICE",
        	"DELETE_SELF_SERVICE",
        	"DELETE_OTHER_SERVICE",

        	"GET_SELF_STATUT",
        	"GET_OTHER_STATUT",
        	"POST_SELF_STATUT",
        	"POST_OTHER_STATUT",
        	"UPDATE_SELF_STATUT",
        	"UPDATE_OTHER_STATUT",
        	"DELETE_SELF_STATUT",
        	"DELETE_OTHER_STATUT",

        	"GET_SELF_PRIORITE",
        	"GET_OTHER_PRIORITE",
        	"POST_SELF_PRIORITE",
        	"POST_OTHER_PRIORITE",
        	"UPDATE_SELF_PRIORITE",
        	"UPDATE_OTHER_PRIORITE",
        	"DELETE_SELF_PRIORITE",
        	"DELETE_OTHER_PRIORITE",

        	"GET_SELF_HISTORIQUE_STATUT",
        	"GET_OTHER_HISTORIQUE_STATUT",
        	"POST_SELF_HISTORIQUE_STATUT",
        	"POST_OTHER_HISTORIQUE_STATUT",
        	"UPDATE_SELF_HISTORIQUE_STATUT",
        	"UPDATE_OTHER_HISTORIQUE_STATUT",
        	"DELETE_SELF_HISTORIQUE_STATUT",
        	"DELETE_OTHER_HISTORIQUE_STATUT",

        	"GET_SELF_CATEGORIE",
        	"GET_OTHER_CATEGORIE",
        	"POST_SELF_CATEGORIE",
        	"POST_OTHER_CATEGORIE",
        	"UPDATE_SELF_CATEGORIE",
        	"UPDATE_OTHER_CATEGORIE",
        	"DELETE_SELF_CATEGORIE",
        	"DELETE_OTHER_CATEGORIE"
        ];

        foreach ($permissions as $permission) {
        	$object = new Permission();
        	$object->setPermission($permission);
            $this->addReference($object->getPermission(), $object);
        	$manager->persist($object);
        }

        $manager->flush();
    }
}