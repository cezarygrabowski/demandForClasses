<?php

namespace App\Service;

use App\Entity\Role;
use App\Entity\Subject;
use App\Entity\User;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;

class UserService
{
    private $qualificationRepository;
    private $userRepository;
    private $subjectRepository;

    public function __construct(
        SubjectRepository $qualificationRepository,
        UserRepository $userRepository,
        SubjectRepository $subjectRepository
    )
    {
        $this->qualificationRepository = $qualificationRepository;
        $this->userRepository = $userRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function getQualifiedLecturers(\App\Entity\Demand $demand)
    {
        /** @var Subject $subject */
        $subject = $this->qualificationRepository->findOneBy([
            'name' => $demand->getSubject()->getName()
        ]);

        $dtos = [];
        $users = $this->userRepository->getByQualification($subject);
        foreach ($users as $user) {
            $dtos[] = \App\DTO\User::fromUser($user);
        }

        return $dtos;
    }

    public function generateUsers($data)
    {
        $lecturers = [];
        $em = $this->userRepository->getEntityManager();
        foreach ($data as $row) {
            $lecturers[] = $lecturer = $this->createUser($row);
            $em->persist($lecturer);
        }
        $em->flush();
    }

    private function createUser($row): User
    {
        if ($user = $this->userRepository->findOneBy(['username' => $row[0]])) {
            $this->addQualifications($user, $row[1]);
        } else {
            $user = new User($row[0]);
            $role = new Role();
            $role->setName($row[2]);
            $role->setUser($user);
            $user->addRole($role);
            $user->setIsActive(true);
            $user->setPassword('admin');
            $this->addQualifications($user, $row[1]);

        }
        return $user;
    }

    private function addQualifications(User $user, $row)
    {
        $subjects = $this->parseCellOntoArray($row);

        $subjects = array_unique($subjects);
        $em = $this->subjectRepository->getEntityManager();
        foreach ($subjects as $key => $value) {
            if ($subject = $this->subjectRepository->findOneBy(['shortenedName' => $key])) {
                $user->addQualification($subject);
            } else {
                $subject = new Subject();
                $subject->setShortenedName($key);
                $subject->setName($value);
//                $em->persist($subject);
//                $em->flush();
                $user->addQualification($subject);
            }
        }
//        $isThereQualificationDuplicate = print_r(array_count_values($user->getQualifications()));
    }

    private function parseCellOntoArray(string $row): array
    {
        $targetSubjects = [];
        $subjects = explode(',', $row);
        foreach ($subjects as $subject) {
            $temp = explode('-', $subject);
            if(isset($temp[1])) {
                $targetSubjects[trim($temp[0])] = trim($temp[1]);
            }
        }

        return $targetSubjects;
    }

    public function updateAutomaticalSendToPlanners(User $user, bool $data)
    {

    }
}
