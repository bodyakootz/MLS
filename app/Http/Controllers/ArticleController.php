<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Article;

class ArticleController extends Controller {
    public function articles() {
        return v();
    }
    public function article() {
        return v();
    }
}