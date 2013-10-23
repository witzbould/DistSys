<?php

namespace DistSys\ShopBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;


/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
	public function loadUserByUsername($username)
	{
        $q = $this
            ->createQueryBuilder('u')
            ->join('u.roles', 'r')
            ->where('(u.username = :username OR u.email = :email) AND r.name != :roleA AND r.name != :roleB')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->setParameter('roleA', 'ROLE_INACTIVE')
            ->setParameter('roleB', 'ROLE_DELETED')
            ->getQuery()
        ;
	
			
		try {
	
			// The Query::getSingleResult() method throws an exception
			// if there is no record matching the criteria.
			$user = $q->getSingleResult();


		} catch (NoResultException $e) {
			//throw new UsernameNotFoundException(sprintf('Unable to find an active admin RCTracksUserBundle:User object identified by "%s".', $username), null, 0, $e);
			return null;
		}
	
	
	
		//$user->setLastLogin(new \DateTime());
		//$user->setIp($_SERVER['SERVER_ADDR']);
		$em = $this->getEntityManager();
		$em->flush();
	
	
		//$this->lastLogin = date("Y.m.d H:i:s");
		return $user;
	}
	
	public function isUserUnique($username, $email)
	{
		$q = $this
		->createQueryBuilder('u')
		->where('u.username = :username OR u.email = :email')
		->setParameter('username', $username)
		->setParameter('email', $email)
		->getQuery()
		;

		try {
			// The Query::getSingleResult() method throws an exception
			// if there is no record matching the criteria.
			$user = $q->getSingleResult();
		} catch (NoResultException $e) {
			//throw new UsernameNotFoundException(sprintf('Unable to find an active admin RCTracksUserBundle:User object identified by "%s".', $username), null, 0, $e);
			return true;
		}
		 
		//$this->lastLogin = date("Y.m.d H:i:s");
		return false;
	}
	

	
	
	public function loadUserByActivateCode($code)
	{
		$q = $this
		->createQueryBuilder('u')
		->join('u.activations', 'a')
		->join('u.roles', 'r')
		->where('a.code = :code')
		->setParameter('code', $code)
		->getQuery()
		;
	
	
	
		try {
	
			// The Query::getSingleResult() method throws an exception
			// if there is no record matching the criteria.
			$user = $q->getSingleResult();
		} catch (NoResultException $e) {
			//throw new UsernameNotFoundException(sprintf('Unable to find an active admin RCTracksUserBundle:User object identified by "%s".', $username), null, 0, $e);
			return null;
		}
		 
	
	
	
		//$this->lastLogin = date("Y.m.d H:i:s");
		return $user;
	}
	
	
	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
		}
	
		return $this->find($user->getId());
	}
	
	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}
}