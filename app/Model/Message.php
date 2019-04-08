<?php namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class Message extends Model {
    protected $fillable = ['message', 'user_from', 'user_to'];
}