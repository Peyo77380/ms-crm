<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $status
 * @property int $type
 * @property string $title
 * @property string $content
 * @property int $user_id
 * @property int $admin_id
 * @property string $end_date
 * @property string $archived_at
 * @property string $created_at
 * @property string $updated_at
 */

 /**
 * @OA\Schema(
 *     title="Task",
 *     description="Task model",
 *     @OA\Xml(
 *         name="Task"
 *     )
 * )
 */
class Task extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *      title="status",
     *      description="task's status id, refer tu other db",
     *      example="1",
     *      type="integer"
     * )
     */
    public $status;

    /**
     * @OA\Property(
     *      title="type",
     *      description="task's type id, refer tu other db",
     *      example="4",
     *      type="integer"
     * )
     */
    public $type;


/**
     * @OA\Property(
     *      title="title",
     *      description="task's title",
     *      example="Routine matin",
     *      type="string"
     * )
     */
    public $title;


/**
     * @OA\Property(
     *      title="content",
     *      description="everything to do",
     *      example="Faire du café",
     *      type="text"
     * )
     */
    public $content;


/**
     * @OA\Property(
     *      title="user_id",
     *      description="id's user who writes this task",
     *      example="4",
     *      type="integer"
     * )
     */
    public $user_id;


/**
     * @OA\Property(
     *      title="admin_id",
     *      description="id's user who is responsable",
     *      example="4",
     *      type="integer"
     * )
     */
    public $admin_id;


    /**
     * @OA\Property(
     *      title="end_date",
     *      description="date to finish this task",
     *      example="21/12/2010",
     *      type="date"
     * )
     */
    public $end_date;


    /**
     * @OA\Property(
     *      title="archived_at",
     *      description="date to archive",
     *      example="21/12/2010",
     *      type="date"
     * )
     */
    public $archived_at;

    /**
     * @var array
     */
    protected $fillable = ['status', 'type', 'title', 'content', 'user_id', 'admin_id', 'end_date', 'archived_at', 'created_at', 'updated_at'];

    public function admin ()
    {
        return $this->hasOne('App\Models\User');
    }

    public function user ()
    {
        return $this->hasOne('App\Models\User');
    }
}
