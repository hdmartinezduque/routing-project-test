<?php

namespace App\Controllers;

class MetricsController
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


    public function index($patientId)
    {
        if (empty($patientId)) {
            $this->respondError('Patient ID is required', 400);
        }

        // //patientsMetrics = listpatientsMetrics();
        $metrics = [
            ['id' => 1, 'type' => 'Blood Pressure', 'value' => '120/80'],
            ['id' => 2, 'type' => 'Heart Rate', 'value' => '72 bpm']
        ];

        $this->respond([
            'patientId' => $patientId,
            'metrics' => $metrics
        ]);
    }

    public function get($patientId, $metricId)
    {
        if (empty($patientId) || empty($metricId)) {
            $this->respondError('Patient ID and Metric ID are required', 400);
        }

        // $patientMetric = getPatientsMetrics();
        $metric = [
            'id' => $metricId,
            'patientId' => $patientId,
            'type' => 'Blood Pressure',
            'value' => '120/80'
        ];

        $this->respond($metric);
    }

    public function create($patientId)
    {
        if (empty($patientId)) {
            $this->respondError('Patient ID is required', 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->respondError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['type'], $data['value'])){
            $this->respondError('Missing required fiels', 400);
        }

        // addPatientsMetrics();
        $this->respond([
            'message' => "Metric created successfully for patient ID $patientId",
            'metric' => $data
        ], 201);
    }

    public function update($patientId, $metricId)
    {
        if (empty($patientId) || empty($metricId)) {
            $this->respondError('Patient ID and Metric ID are required', 400);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->respondError('Invalid or missing JSON payload', 400);
        }

        if(!isset($data['value'])){
            $this->respondError('Missing required fiels', 400);
        }

        // // updatePatientsMetric();
        $this->respond([
            'message' => "Metric ID $metricId updated for patient ID $patientId",
            'updated_data' => $data
        ]);
    }

    public function delete($patientId, $metricId)
    {
        if (empty($patientId) || empty($metricId)) {
            $this->respondError('Patient ID and Metric ID are required', 400);
        }

        // // deletePatientsMetric();
        $this->respond([
            'message' => "Metric ID $metricId deleted for patient ID $patientId"
        ]);
    }

}
