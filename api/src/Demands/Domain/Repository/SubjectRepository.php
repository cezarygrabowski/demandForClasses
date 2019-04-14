<?php


namespace Demands\Domain\Repository;


use Demands\Domain\Subject;

interface SubjectRepository
{
    public function findByName(string $name): ?Subject;
}