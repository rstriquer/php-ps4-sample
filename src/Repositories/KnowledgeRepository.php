<?php
/**
 * App KnowledgeRepository.
 * 
 * Extends KnowledgeModel specific funcionalities
 * 
 * @category   Repositories
 * @package    App\Repositories
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Repositories;

use App\Models\KnowledgeModel;
use App\Migrations\CreateQuestionTableMigration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KnowledgeRepository extends Repository implements RepositoryInterface
{
    protected KnowledgeModel $model;

    public function __construct(KnowledgeModel $model)
    {
        $this->model = $model;

        if (!is_file(getenv('DB_DATABASE')))
        {
            touch(getenv('DB_DATABASE'));
            chmod(getenv('DB_DATABASE'), 0777);
        }
        parent::__construct();
        if (!$this->schema->hasTable($this->model->getTable())) {
            $this->create_table();
        }
    }

    /**
     * Builds the table and the first records needed to the game
     * - Works like a migration to the database
     *
     * @author Ricado Soares <ricardo.soares@rentcars.com>
     */
    private function create_table(): void
    {
        $this->schema->create(
            $this->model->getTable(),
            function ($table) {
                $table->string('uuid')->unique();
                $table->string('parent');
                $table->string('item');
                $table->string('yes')->nullable();
                $table->string('no')->nullable();
                $table->timestamps();
            }
        );
        $this->model->create([
            'uuid' => '51b1e62d-cfcc-45b8-93cc-8f196c3b6f12',
            'parent' => '',
            'item' => 'Pasta',
            'yes' => 'ff1d2990-145b-405f-8f2f-1088ffd5cf1a',
            'no' => '29f0d3b3-4324-422b-9caf-887fb69a43cc',
        ]);
        $this->model->create([
            'uuid' => 'ff1d2990-145b-405f-8f2f-1088ffd5cf1a',
            'parent' => '51b1e62d-cfcc-45b8-93cc-8f196c3b6f12',
            'item' => 'Lasagna',
            'yes' => null,
            'no' => null,
        ]);
        $this->model->create([
            'uuid' => '29f0d3b3-4324-422b-9caf-887fb69a43cc',
            'parent' => '51b1e62d-cfcc-45b8-93cc-8f196c3b6f12',
            'item' => 'Chocolate cake',
            'yes' => null,
            'no' => null,
        ]);
    }

    public function findByItem(string $item): ?KnowledgeModel
    {
        $found = $this->model->where('item', $item)->first();
        if ($found !== null) {
            $this->model = $found;
            return clone $this->model;
        }
        return null;
    }
}
