<?php
namespace Start\Service\Impl;

use Start\Service\IMessageService;
use Doctrine\ORM\EntityManager;
use Start\Entity\Message;
use Start\Exception\EntityNotFoundException;

class MessageServiceImpl implements IMessageService {
	
	private $messageRepository;
	private $entityManager;
	
	public function __construct(EntityManager $em) {
		$this->messageRepository = $em->getRepository("Start\Entity\Message");
		$this->entityManager = $em;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Start\Service\IMessageService::getAllMessages()
	 */
	public function getAllMessages() {
		return $this->messageRepository->findAll();
	}

	/**
	 * {@inheritDoc}
	 * @see \Start\Service\IMessageService::getMessageById()
	 */
	public function getMessageById($id) {
		return $this->messageRepository->find($id);
	}

	/**
	 * {@inheritDoc}
	 * @see \Start\Service\IMessageService::createMessage()
	 */
	public function createMessage($message = "") {
		$entity = new Message();
		$entity->setMessage($message);
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
		
		return $entity;
	}

	/**
	 * {@inheritDoc}
	 * @see \Start\Service\IMessageService::updateMessage()
	 */
	public function updateMessage($id, $string = "") {
		$message = $this->getMessageById($id);
		if($message === null) {
			throw new EntityNotFoundException("exception.msg.entityNotFound");
		}
		$message->setMessage($string);
		$this->entityManager->persist($message);
		$this->entityManager->flush();
		
		return $message;
	}

	/**
	 * {@inheritDoc}
	 * @see \Start\Service\IMessageService::deleteMessageById()
	 */
	public function deleteMessageById($id) {
		$queryBuilder = $this->messageRepository->createQueryBuilder("msg")->delete()->where("msg.id = :id");
		$queryBuilder->setParameter(":id", $id);
		$affectedRows = $queryBuilder->getQuery()->execute();
		
		if($affectedRows === 0) {
			throw new EntityNotFoundException("exception.msg.entityNotFound");
		}
		
	}

	/**
	 * {@inheritDoc}
	 * @see \Start\Service\IMessageService::createExampleMessages()
	 */
	public function createExampleMessages() {
		$count = $this->messageRepository->createQueryBuilder("msg")
		->select("COUNT(msg.id)")->getQuery()->getSingleScalarResult();
		if($count > 0) {
			return;
		}
		
		$messagesToCreate = 3;
		for($i = 0; $i < $messagesToCreate; $i++) {
			$message = new Message();
			$message->setMessage("This is message No. " . ($i + 1));
			$this->entityManager->persist($message);
		}
		
		$this->entityManager->flush();
	}

}
