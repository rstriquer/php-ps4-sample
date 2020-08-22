<?php
/**
 * App repository interface
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

interface RepositoryInterface
{
    function all();
    function create(array $data);
    function update(array $data, $id);
    function delete($id);
    function show($id);
}