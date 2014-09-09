<?php

namespace Example;

use Respect\Validation\Validator as v;

/**
 * @SWG\Model(id="User",required="name, phone")
 */
class ExampleService extends \Phalcon\DI\Injectable
{

    protected $users = [
        [
            'id' => 1,
            'name' => 'John',
            'phone' => 1233456785
        ],
        [
            'id' => 2,
            'name' => 'Jack',
            'phone' => 5435435123
        ],
        [
            'id' => 3,
            'name' => 'Greg',
            'phone' => 5435435435
        ],
        [
            'id' => 4,
            'name' => 'Mike',
            'phone' => 8768678678
        ],
    ];

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
        if (v::numeric()->validate($id) && $this->validateUser($data)) {
            $this->users[$id] = $data;
            return true;
        }
        return false;
    }

    public function saveUser($data = [])
    {
        if ($this->validateUser($data)) {
            $this->users[5] = $data;
            return [5, $this->users[5]];
        }
        throw new \Exception();
    }

    public function getUser($id = null)
    {
        if (v::numeric()->validate($id) && isset($this->users[$id])) {
            return $this->users[$id];
        }
        throw new \Exception();
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
