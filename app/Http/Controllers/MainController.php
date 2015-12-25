<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Mail;
use Request;
use App\Article;
use DB;

class MainController extends Controller {

    private function getLatestNews() {
        $last_articles = DB::table('articles')
            ->orderBy('date', 'desc')
            ->take(4)
            ->get();
        return $last_articles;
    }
    public function index() {
        $articles = $this->getLatestNews();
        return v()->with('articles', $articles);
    }
    public function about() {
        return v();
    }
    public function services() {
        return v();
    }
    public function service() {
        return v();
    }
    public function contacts() {
        return v();
    }
    public function admin() {
        return v();
    }
    public function admin_feedback() {
        return v();
    }
    public function admin_subscribers() {
        return v();
    }
    public function logging() {
        $data = Request::all();
        unset($data['_token']);

        $pass = Auth::attempt($data, true);
        if ($pass) {
            return redirect()->route('admin');
        } else {
            return redirect()->route('login')->withErrors('Неверный логин или пароль!');
        }
    }
    public function login() {
        if (Auth::check()) {
            return redirect()->route('admin');
        } else {
            return v();
        }
    }

}