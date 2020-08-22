<?php
/**
 * Apps Json Response Trait.
 * 
 * @category   Http
 * @package    App\Http
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Repositories;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\Schema;

class Repository
{
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;
    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    function __construct() {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver'=> getenv('DB_CONNECTION'),
            'host'=> getenv('DB_HOST'),
            'database'=> getenv('DB_DATABASE'),
            'username'=> getenv('DB_USERNAME'),
            'password'=> getenv('DB_PASSWORD'),
            'charset'=> 'utf8',
            'collation'=> 'utf8_unicode_ci',
            'prefix'=> getenv('DB_PREFIX'),
        ]);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // create a new record in the database
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // update record in the database
    public function update(array $data, $id)
    {
        $record = $this->find($id);
        return $record->update($data);
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    // show the record with the given id
    public function show($id)
    {
        return $this->model-findOrFail($id);
    }

    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Set the associated model
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    // Eager load database relationships
    public function with($relations)
    {
        return $this->model->with($relations);
    }
}