<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Blog;
use App\Models\GuestMessage;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $projectCount = Project::count();
        $certificateCount = Certificate::count();
        $blogCount = Blog::count();
        $messageCount = Message::count();
        $unreadCount = Message::where('is_read', false)->count();
        $guestMessageCount = GuestMessage::count();
        $latestBlogs = Blog::latest()->take(3)->get();
        $profile = Profile::first();

        return view('admin.dashboard', compact('projectCount', 'certificateCount', 'blogCount', 'messageCount', 'unreadCount', 'guestMessageCount', 'latestBlogs', 'profile'));
    }
}
