<?php

namespace App\Services;

use App\Models\Specialization;
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

    public function getSpecialization(Specialization $specialization)
    {
        return $this->specializationRepository->find($specialization->slug);
    }

    public function updateSpecialization(Specialization $specialization, $data)
    {
        return $this->specializationRepository->update($specialization->slug, $data);
    }

    public function deleteSpecialization($specialization)
    {
        return $this->specializationRepository->delete($specialization->slug);
    }
}
