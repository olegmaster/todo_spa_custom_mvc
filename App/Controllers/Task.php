<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Task as TaskModel;

/**
 * Task controller
 *
 */
class Task extends Controller
{

    public function get(){

        $page = $_GET['page'] ?? 1;
        $orderBy = $_GET['order_by'] ?? 'id';
        $orderDirection = $_GET['order_direction'] ?? 'DESC';

        $data = TaskModel::getTasks((int)$page, $orderBy, $orderDirection);

        $this->showJson($data);

    }

    public function add()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (@empty($data['username']) || @empty($data['email']) || @empty($data['text'])) {
           $this->showJson(['error' => 'invalid data']);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->showJson(['error' => 'invalid email']);
        }

        if(!TaskModel::createTask($data)){
            $this->showJson(['error' => 'error, check for duplicates']);
        } else {
            $this->showJson(['success' => 'task has been inserted']);
        }

    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (@empty($data['id']) || @empty($data['username']) || @empty($data['email']) || @empty($data['text']) || @!isset($data['is_completed'])) {
            $this->showJson(['error' => 'invalid data']);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->showJson(['error' => 'invalid email']);
        }


        if(!TaskModel::updateTask($data)){
            $this->showJson(['error' => 'error, check the correct id or duplicate email']);
        } else {
            $this->showJson(['success' => 'task has been updated']);
        }

    }

}
