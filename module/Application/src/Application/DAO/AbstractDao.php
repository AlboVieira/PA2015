<?php

namespace Application\Dao;

use Components\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;


class AbstractDao
{
    protected $entity;
    protected $tbalias;
    /** @var EntityManager em */
    private $entityManager;

    public function __construct(){
        $this->getEntityManager();
    }

    public function getEntityManager(){
        $this->entityManager = $GLOBALS['entityManager'];
        return $this->entityManager;
    }

    public function validaPerfil($user){
        $objUser = $this->entityManager->getRepository('Application\Entity\User')->findById($user);
        return $objUser[0]->getPerfil();
    }

    public function getEntity(){
        return $this->entity;
    }

    public function getTbAlias(){
        return $this->tbalias;
    }

    //retorna querybuilder

    public function createQueryBuilder(){
        return $qb = $this->em->createQueryBuilder();
    }
    
    public function salvar(AbstractEntity $entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function excluir(AbstractEntity $entity){
        $this->entityManager->merge($entity);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function updateEntity(AbstractEntity $entity){
        $this->entityManager->merge($entity);
        $this->entityManager->flush();
    }

    public function findById($key, $entity)
    {
        $em = $this->entityManager;
        return $em->getRepository($entity)->find($key);
    }

    public function findAll($entity)
    {
        $em = $this->entityManager;
        return $em->getRepository($entity)->findAll();
    }

    // nao ficou legal isso nao, tente evitar usar qeuery nativa
    public function queryNativa($columns = null, $table){
    	    	
    	if($columns === null){
    		$columns = '*';
    	}elseif(is_array($columns)){
    		$columns = implode(',', $columns);
    	}
    	
    	$em = $this->entityManager;
    	$conn = $em->getConnection();
    	$statement = "SELECT {$columns}  FROM {$table}";
    	$result_set = $conn->fetchAll($statement);
    	
    	return $result_set;
    }

}