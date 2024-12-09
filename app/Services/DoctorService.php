<?php

namespace App\Services;

use Auth;
use App\Models\Doctor;
use App\Repositories\DoctorRepository;

class DoctorService
{
    protected $doctorRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function getAllDoctors()
    {
        return $this->doctorRepository->all();
    }

    public function createDoctor(array $data)
    {
        return $this->doctorRepository->create($data);
    }

    public function getDoctor($id)
    {

        $doctor = Doctor::where('user_id', Auth::user()->id)->first();

        if ($doctor->id == $id) {
            return $this->doctorRepository->find($id);
        } else
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to view this doctor.'
                ],
                403
            );
    }

    public function updateDoctor($id, array $data)
    {
        $doctor = Doctor::where('user_id', Auth::user()->id)->first();

        if ($doctor->id == $id) {
            $this->doctorRepository->update($id, $data);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Doctor updated successfully'
                ],200
            );
        } else
            return response()->json(
                [
                    'status' => false,
                    'message' => 'You are not authorized to update this doctor.'
                ],
                403
            );
    }

    public function deleteDoctor($id)
    {
        $doctor = Doctor::where('user_id', Auth::user()->id)->first();

        if ($doctor->id == $id) {
            $this->doctorRepository->delete($id);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Doctor deleted successfully'
                ],200
            );
        } else return response()->json(
            [
                'status' => false,
                'message' => 'You are not authorized to delete this doctor.'
            ],
            403
        );
    }
}
