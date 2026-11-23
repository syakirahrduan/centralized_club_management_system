<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    // Allows these fields to be saved to the database
    protected $fillable = [
        'name', 
        'email', // official club email (roboteam@iium.edu.my)
        'category', 
        'description', 
        'logo_path', 
        'status', 
        'admin_id'
    ];

    // Connects the club to its assigned Club Admin
    public function admin()
    {
        // A club has one admin (User) assigned to it
        return $this->hasOne(\App\Models\User::class, 'club_id')->where('role', 'club_admin');
    }
}