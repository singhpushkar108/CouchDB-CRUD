<?php
namespace App\Controllers;
// namespace App\Models;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CouchModel;

class CouchApi extends ResourceController{

    public function __construct(){

        $this->couchModel = new CouchModel();
    }

    public function insertData(){
        
        // print_r($_POST);exit;

        if(!empty($_POST)){

            $studentData = $_POST;

            /*     // Example student data to be inserted (replace with actual data)
            $studentData = [
                'name' => 'John Doe',
                'age' => 20,
                'email' => 'john@example.com',
                // Add other student fields here
            ];*/

            // Call insertStudent method to insert student data
            $statusCode = $this->couchModel->insertData($studentData);

            // Check status code and handle response accordingly
            if ($statusCode === 201) {
                // Success: Insertion was successful
                // return redirect()->to('/students')->with('success', 'Student data inserted successfully.');
                $data = [
                    'status' => 'succ',
                    'msg'   => 'Data Insertion Success',
                    ];
            } else {
                // Error: Insertion failed
                // return redirect()->back()->with('error', 'Failed to insert student data. Please try again.');
                $data = [
                    'status' => 'err',
                    'msg'   => 'Data Insertion Failure',
                ];
            } 

        }else{
            $data = [
                'status' => 'err',
                'msg'   => 'No Data Recieved for insertion',
            ];
        }
        return $this->respond($data,200);
    }


    public function getAllData() {
        
        // print_r('hello');exit;

        $response = $this->couchModel->getAllRecords();
        // print_r($response);exit;
        if($response){
            $data = [
                'status' => 'succ',
                'data'  => $response,
                'msg'   => 'Data Retrieved Successfully',
                ];
        }else{
            $data = [
                'status' => 'err',
                'msg'   => 'No Data found',
                ];
        }

        return $this->respond($data,200);

    }


    public function updateRecord(){

        // print_r($_POST);exit;
        if(isset($_POST)){
            $id = $_POST['_id'];
            unset($_POST['_id'], $_POST['_rev']);
            $result = $this->couchModel->update_record($id,$_POST);

            $data = ($result) ? [
                    'status' => 'succ',
                    'msg'   => 'Updation Success',
                    ]: [
                        'status' => 'err',
                        'msg'   => 'Data Updation failure',
                        ];

        }else{
            $data = [
                'status' => 'err',
                'msg'   => 'No Data recieved for updation',
                ];
        }

        return $this->respond($data,200);

    }

    public function deleterRecord(){

        // print_r($_POST);exit;
        if(isset($_POST['id']) && isset($_POST['revId'])){
            $docId    = $_POST['id'];
            $revId    = $_POST['revId'];
            $response = $this->couchModel->delete_record($docId,$revId);

            if($response){
                $data = [
                    'status' => 'succ',
                    'msg'   => 'Data Deleted Successfully',
                    ];
            }else{
                $data = [
                    'status' => 'err',
                    'msg'   => 'Error while deleting data',
                    ];
            }

        }else{
            $data = [
                'status' => 'err',
                'msg'   => 'No Id recieved',
                ];
        }

        return $this->respond($data,200);
       
    }

}
?>
