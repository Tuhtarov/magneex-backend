<?php

namespace App\Repository;

use App\Entity\JobPosition;
use App\Form\Type\JobPositionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method JobPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobPosition[]    findAll()
 * @method JobPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private FormFactoryInterface $formFactory)
    {
        parent::__construct($registry, JobPosition::class);
    }


    public function createByArray(?array $jobPositionData): JobPosition
    {
        $form = $this->formFactory->create(JobPositionType::class)->submit($jobPositionData);

        if ($form->isSubmitted() && $form->isValid()) {
            $jobPosition = $form->getData();
            $this->getEntityManager()->persist($jobPosition);
            $this->getEntityManager()->flush($jobPosition);

            return $jobPosition;
        }

        throw new BadRequestException('JobPosition is not created. Invalid param.');
    }
}

