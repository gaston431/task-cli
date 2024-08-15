<?php

class App
{
    public function __construct() {
        if (!file_exists('task.json')) {
            file_put_contents('task.json','');
        }
    }

    public function addTask($argv)
    {
        if (!isset($argv[2])) {
            return 'Enter a description for a task';
        }

        $content = json_decode(file_get_contents('task.json'));

        $counter = (!isset($content)) ? 1 : $content[count($content) - 1]->id + 1;

        $task = [
            'id' => $counter,
            'description' => $argv[2],
            "status" => 'todo',
            'createdAt' => date("Y-m-d H:i:s"),
            'updatedAt' => date("Y-m-d H:i:s")
        ];

        $content[] = $task;
        file_put_contents('task.json', json_encode($content, JSON_PRETTY_PRINT));

        return "task {$argv[2]} added";
    }

    public function updateTask($id, $description = null, $status = null)
    {
        $this->checkTaskId($id);

        if (!isset($description) && !isset($status))
            return "Enter a new description";

        $content = json_decode(file_get_contents('task.json'));

        for ($i = 0; $i < count($content); $i++) {
            if ($content[$i]->id == $id) {

                $content[$i]->description = $description ?? $content[$i]->description;
                $content[$i]->status = $status ?? $content[$i]->status;
                $content[$i]->updatedAt = date("Y-m-d H:i:s");

                file_put_contents('task.json', json_encode($content, JSON_PRETTY_PRINT));

                return "task id {$id} updated";
            }
        }

        return "task id not found";
    }

    public function deleteTask($argv)
    {
        $this->checkTaskId($argv[2] ?? null);

        $content = json_decode(file_get_contents('task.json'));

        for ($i = 0; $i < count($content); $i++) {
            if ($content[$i]->id == $argv[2]) {
                array_splice($content, $i, 1);

                file_put_contents('task.json', json_encode($content, JSON_PRETTY_PRINT));

                return "task id {$argv[2]} deleted";
            }
        }

        return "task id not found";
    }

    public function listTasks($argv)
    {
        if (!isset($argv[2])) {
            $content = file_get_contents('task.json');
        }

        if (isset($argv[2])) {
            $array = [];

            $content = json_decode(file_get_contents('task.json'));

            for ($i = 0; $i < count($content); $i++) {
                if ($content[$i]->status == $argv[2]) {
                    $array[] = $content[$i];
                }
            }

            $content = json_encode($array, JSON_PRETTY_PRINT);
        }

        return "Tasks listed \n {$content}";
    }

    private function checkTaskId($id)
    {
        if (!isset($id)) {
            echo 'Enter a task id';
            exit();
        }
    }

    public function help(){
        return "show help command";
    }
}
