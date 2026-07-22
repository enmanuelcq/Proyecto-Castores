<?php
// Service/BCVService.php

class BCVService
{
    private const API_URL = 'https://ve.dolarapi.com/v1/dolares/oficial';
    
    public function obtenerTasa(): ?float
    {
        try {
            $ch = curl_init(self::API_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (isset($data['promedio']) && $data['promedio'] > 0) {
                    return (float)$data['promedio'];
                }
                if (isset($data['precio']) && $data['precio'] > 0) {
                    return (float)$data['precio'];
                }
            }
            return null;
        } catch (Exception $e) {
            error_log("BCVService Error: " . $e->getMessage());
            return null;
        }
    }
}
