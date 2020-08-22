<?php
/**
 * App KnowledgeModel
 * 
 * @category   Models
 * @package    App\Models
 * @author     Ricardo Striquer Soares <rstriquer.gmail>
 * @license    https://github.com/rstriquer/php-ps4-sample/blob/master/LICENSE
 * @version    Release: @package_version@
 */

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
use RuntimeException;

/**
 * Defines the systemn knowledge base
 * - For more detail on structure checkout KnowledgeRepository::create_table
 * @see KnowledgeRepository::create_table
 */
class KnowledgeModel extends Model
{
    protected $table = 'knowledge';
    protected $fillable = ['uuid', 'parent', 'item', 'yes', 'no'];
    protected $primaryKey = 'uuid';
    public $incrementing = false;

    use \App\Models\newUUIDTrait;

    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function getUUID():string
    {
        return $this->uuid;
    }
    public function getParent(): string
    {
        return $this->parent;
    }
    public function getItem():string
    {
        return $this->item;
    }
    public function getYes(): ?string
    {
        return $this->yes;
    }
    public function getNo(): ?string
    {
        return $this->no;
    }
    public function setYes(string $yes): bool
    {
        $this->yes = $yes;
    }
    /**
     * Creates knowledge row at table database
     * 
     * - Sample input content: {"definition": "Fruit", "element": "Maça"}
     * - Sample input content: {"definition": "Boiled", "element": "Spaghetti"}
     *
     * @param array $data
     * @author Ricado Soares <ricardo.soares@rentcars.com>
     * @throws RuntimeException
     */
    public function create(array $data): KnowledgeModel
    {
        if (isset($data['uuid'])) {
            if (!isset($data['parent']) || !isset($data['item'])) {
                throw new \RuntimeException('Missing item creating Knowledge row at table;');
            }

            // - Probably the method is been called from
            // KnowledgeRepository::create_table therefore it must be thrown
            // to parent
            return parent::create($data);
        }

        if (!isset($data['element'])) {
            throw new RuntimeException(
                '\'element\' item not found on JSON data'
            );
        }
        if (!isset($data['definition'])) {
            throw new RuntimeException(
                '\'definition\' item not found on JSON data'
            );
        }
        $parent = parent::find($this->getParent());
        $position = 'no';
        if ($parent->getYes() == $this->getUUID()) {
            $position = 'yes';
        }
        $definition = [
            'uuid' => $this->newUUID(),
            'parent' => $this->getParent(),
            'item' => $data['definition'],
            'yes' => $this->newUUID(),
            'no' => $this->getUUID(),
        ];
        $element = [
            'uuid' => $definition['yes'],
            'parent' => $definition['uuid'],
            'item' => $data['element'],
        ];
        parent::create($definition);
        parent::create($element);
        $parent->update([$position => $definition['uuid']]);
        $this->parent = $definition['uuid'];
        $this->save();
        return $this;
    }
}
