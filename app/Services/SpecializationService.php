<?php

namespace App\Services;

use App\Repositories\SpecializationRepository;

class SpecializationService
{
    /**
     * Create a new class instance.
     */
    protected $specializationRepository;

    public function __construct(SpecializationRepository $specializationRepository)
    {
        $this->specializationRepository = $specializationRepository;
    }

    public function getAllSpecializations()
    {
        return $this->specializationRepository->getAll();
    }

    public function createSpecialization($data)
    {
        return $this->specializationRepository->create($data);
    }

    public function updateSpecialization($specialization, $data)
    {
        return $this->specializationRepository->update($specialization, $data);
    }

    public function deleteSpecialization($specialization)
    {
        return $this->specializationRepository->delete($specialization);
    }
}
