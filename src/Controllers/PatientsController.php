<?php

namespace App\Controllers;

class PatientsController
{

    private function respond($data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $statusCode,
            'data' => $data
        ]);
        exit;
    }

    private function respondError($message, int $statusCode = 400)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $statusCode,
            'error' => $message
        ]);
        exit;
    }   

    public function index()
    {
        //patients = listpatients();
        $patients = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith']
        ];
        $this->respond($patients);
    }

    public function get($patientId)
    {
        if (empty($patientId)) {
            $this->respondError('Patient ID is required', 400);
        }

        // $patient = getPatients();
        $patient = ['id' => $patientId, 'name' => "Patient $patientId"];
        $this->respond($patient);
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->respondError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['name'], $data['age'])){
            $this->respondError('Missing required fiels', 400);
        }

        // addPatients();
        $this->respond([
            'message' => 'Patient created successfully',
            'patient' => $data
        ], 201);
    }


    public function update($patientId)
    {
        if (empty($patientId)) {
            $this->respondError('Patient ID is required', 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->respondError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['name'], $data['age'])){
            $this->respondError('Missing required fiels', 400);
        }

        // updatePatients();
        $this->respond([
            'message' => "Patient with ID $patientId updated successfully",
            'updated_data' => $data
        ]);
    }

    public function delete($patientId)
    {
        if (empty($patientId)) {
            $this->respondError('Patient ID is required', 400);
        }

        // deletePatients();
        $this->respond([
            'message' => "Patient with ID $patientId deleted successfully"
        ]);
    }
}
