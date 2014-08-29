<?php

namespace Example;

use Respect\Validation\Validator as v;

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

    public function updateUser($id = null, $data = [])
    {
        if (v::numeric()->validate($id) && $this->validateUser($data)) {
            $this->users[$id] = $data;
        }
    }

    public function saveUser($data = [])
    {
        if ($this->validateUser($data)) {
            $this->users[] = $data;
            return 5;
        }
        throw new \ApiBird\Error\BadRequestException('Unable to Parse Data.');
    }

    public function getUser($id = null)
    {
        if (v::numeric()->validate($id) && isset($this->users[$id])) {
            return $this->users[$id];
        }
        throw new \ApiBird\Error\NotFoundException('Resource Not Found.');
    }

    public function deleteUser($id = null)
    {
        if (v::numeric()->validate($id)) {
            unset($this->users[$id]);
            return true;
        }
        return false;
    }

    public function listUsers()
    {
        return $this->users;
    }

    public function validateUser($data)
    {
        return v::arr()->key('name', v::string())
                        ->key('phone', v::numeric())
                        ->validate($data);
    }

}
