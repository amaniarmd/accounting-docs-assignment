<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'assigned_to',
        'deadline',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignToUser(User $user)
    {
        $this->assigned_to = $user->id;
        $this->save();
    }

    public function cancelAssignment()
    {
        $this->assigned_to = null;
        $this->save();
    }
}
