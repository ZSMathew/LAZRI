<?php
require_once 'config/supabase_config.php';

class StorageService {
    private $supabaseUrl;
    private $supabaseKey;
    private $bucket;
    
    public function __construct() {
        $this->supabaseUrl = SupabaseConfig::$supabaseUrl;
        $this->supabaseKey = SupabaseConfig::$supabaseKey;
        $this->bucket = SupabaseConfig::$storageBucket;
    }
    
    public function uploadImage($file, $path = '') {
        try {
            // Generate unique filename
            $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file['name']);
            $filepath = $path ? "$path/$filename" : $filename;
            
            // Prepare file for upload
            $fileContent = file_get_contents($file['tmp_name']);
            $base64Content = base64_encode($fileContent);
            
            // Supabase storage API endpoint
            $url = $this->supabaseUrl . "/storage/v1/object/" . $this->bucket . "/" . $filepath;
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->supabaseKey,
                    'Content-Type: application/json',
                    'x-upsert: true'
                ],
                CURLOPT_POSTFIELDS => json_encode([
                    'file' => $base64Content,
                    'contentType' => $file['type']
                ])
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                // Return public URL
                return $this->supabaseUrl . "/storage/v1/object/public/" . $this->bucket . "/" . $filepath;
            } else {
                error_log("Upload failed: " . $response);
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Storage upload error: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteImage($filepath) {
        try {
            $url = $this->supabaseUrl . "/storage/v1/object/" . $this->bucket . "/" . $filepath;
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $this->supabaseKey,
                    'Content-Type: application/json'
                ]
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            return $httpCode === 200;
            
        } catch (Exception $e) {
            error_log("Storage delete error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPublicUrl($filepath) {
        return $this->supabaseUrl . "/storage/v1/object/public/" . $this->bucket . "/" . $filepath;
    }
}