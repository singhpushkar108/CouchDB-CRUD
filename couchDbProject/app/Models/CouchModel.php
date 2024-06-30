<?php

namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class CouchModel {
    private $client;
    private $database;

    public function __construct(){
          // Initialize Guzzle HTTP client with CouchDB URL
          $this->client = new Client([
            'base_uri' => 'http://vmc_admin:vmc1234@localhost:5984/', // CouchDB URL
            'timeout'  => 5, // Timeout in seconds
        ]);

        $this->database = 'students';
    }


    public function insertData($data){
        try {
            // Send POST request to CouchDB to insert a document
            $response = $this->client->request('POST', $this->database, [
                'json' => $data // Send data as JSON payload
            ]);

            // print_r($response);exit;
            $responseBody = $response->getBody()->getContents();

            // $responseData = json_decode($responseBody, true);
            // print_r($response->getStatusCode());exit;
            return $response->getStatusCode();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                return false; // Return false if insertion failed
            }
            return false;
        }
    }

    public function getAllRecords() {
        // print_r('hello');exit;
        $result = $this->client->request('GET', $this->database."/_all_docs");
        // $result = $this->client->request('GET'); // when using views

        $body = json_decode($result->getBody(),true);
        
        // print_r($body);exit;
        $documentIds = array_column($body['rows'], 'id');
        
        //Fetch each document from the database
        $documents = [];
        foreach ($documentIds as $docId) {
            $response = $this->client->request('GET', "{$this->database}/{$docId}");
            $document = json_decode($response->getBody(), true);
            $documents[] = $document;
        }

        return $documents;
        
        // return $body["rows"];
    }


    public function update_record($docId,$data){
        try {
            // Fetch the existing document
            $response = $this->client->request('GET', "{$this->database}/{$docId}");
            $document = json_decode($response->getBody(), true);
            // Merge new data with existing document
            $updatedDocument = array_merge($document, $data);
            
            // Send PUT request to CouchDB to update the document
            $response = $this->client->request('PUT', "{$this->database}/{$docId}", [
                'json' => $updatedDocument
            ]);
            // print_r($response->getStatusCode());exit;    
            if ($response->getStatusCode() == 201) {
                return true;
            } else {
                return false; 
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                return false; 
            }
            return false;
        }
    }

    public function delete_record($docId,$revId){
        // echo  $this->database . '/' . $docId;exit;
        try {

            $response = $this->client->request('DELETE', "{$this->database}/{$docId}?rev={$revId}");
            // print_r($response);exit;
            if ($response->getStatusCode() == 200) {
                return true; 
            } else {
                return false; 
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse();
                // print_r($errorResponse);exit;
                return false; // Return false if deletion failed
            }
            return false; // Return false if deletion failed
        }
    }


}

?>