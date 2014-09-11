<?php

namespace Example;

use Respect\Validation\Validator as v;

/**
 * @SWG\Model(id="User",required="name, phone")
 */
class ExampleService extends \Phalcon\DI\Injectable
{

    protected $users = [
        1 => [
            'id' => 1,
            'name' => 'John',
            'phone' => 1233456785
        ],
        2 => [
            'id' => 2,
            'name' => 'Jack',
            'phone' => 5435435123
        ],
        3 => [
            'id' => 3,
            'name' => 'Greg',
            'phone' => 5435435435
        ],
        4 => [
            'id' => 4,
            'name' => 'Mike',
            'phone' => 8768678678
        ],
    ];
    protected $keys = [];

    /**
     * @SWG\Property(name="id",type="integer",description="Id of user")
     */
    public $id;

    /**
     * @SWG\Property(name="name",type="string",description="Name of user")
     */
    public $name;

    /**
     * @SWG\Property(name="phone",type="integer",description="Phone of user")
     */
    public $phone;

    public function updateUser($id = null, $data = [])
    {
        if (isset($this->users[$id])) {
            $this->users[$id] = array_merge($data, ['id' => $id]);
            return true;
        }
        return false;
    }

    public function saveUser($data = [])
    {
        if ($this->validateUser($data)) {
            $this->users[5] = array_merge($data, ['id' => 5]);
            return [5, $this->users[5]];
        }
        return false;
    }

    public function getUser($id = null)
    {
        if (v::numeric()->validate($id) && isset($this->users[$id])) {
            return $this->users[$id];
        }
        return false;
    }

    public function deleteUser($id = null)
    {
        try {
            if (v::numeric()->validate($id) && isset($this->users[$id])) {
                unset($this->users[$id]);
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
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
