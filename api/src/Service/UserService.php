<?php

namespace App\Service;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;

class UserService
{
    private $qualificationRepository;
    private $userRepository;

    public function __construct(
        SubjectRepository $qualificationRepository,
        UserRepository $userRepository
    ) {
        $this->qualificationRepository = $qualificationRepository;
        $this->userRepository = $userRepository;
    }

    public function getQualifiedLecturers(\App\Entity\Demand $demand)
    {
        /** @var Subject $subject */
        $subject = $this->qualificationRepository->findOneBy([
            'name' => $demand->getSubject()->getName()
        ]);

        return $this->userRepository->getByQualification($subject);
    }
}