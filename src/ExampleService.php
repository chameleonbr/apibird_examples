<?php

namespace Example;

class ExampleService extends \Phalcon\DI\Injectable
{
    
    protected $users = [
        1 => [
            'name' => 'John',
            'phone' => '1233456785'
        ],
        2 => [
            'name' => 'Jack',
            'phone' => '5435435123'
        ],
        3 => [
            'name' => 'Greg',
            'phone' => '5435435435'
        ],
        4 => [
            'name' => 'Mike',
            'phone' => '8768678678'
        ],
    ];
    protected $lastId = 4;
            

    public function saveUser($id = null,$data = [])
    {
        
    }

    public function getUser($id = null)
    {
        
    }

    public function deleteUser($id = null)
    {
        
    }

    public function listUsers()
    {
        
    }

}
