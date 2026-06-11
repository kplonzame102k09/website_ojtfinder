<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Company;
use App\Models\Post;
use App\Models\Activity;

class AdminController extends Controller
{
    private function authorizeAdmin()
    {
        if (Auth::id() !== 1) {
            abort(404, 'Page Not Found');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();
		$users = User::with('sessions')->get();
        
        return view('admin.index', [
            
            'users' => User::where('id', '!=', 1)->paginate(10),
            'companies' => Company::where('user_id', '!=', 1)->latest()->paginate(10),
            'posts' => Post::latest()->paginate(10),

        ]);
    }
	//========================== Delete User ==========================//
    public function destroyUser(User $user)
    {
        $this->authorizeAdmin();
        //========================== Never allow deleting super admin ==========================//
        if ($user->id === 1) {
            return back()->with('error', 'You cannot delete the super admin.');
        }
		//========================== Log Activity ==========================//
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'deleted_user',
            'subject_type' => 'User',
            'subject_id' => $user->id,
            'description' => "Deleted user: {$user->name} (ID: {$user->id})",
    ]);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
	//========================== Delete Company ==========================//
    public function destroyCompany(Company $company)
    {
        $this->authorizeAdmin();

        if ($company->user_id === 1) {
            return back()->with('error', 'You cannot delete admin-owned companies.');
        }
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'deleted_company',
            'subject_type' => 'Company',
            'subject_id' => $company->id,
            'description' => "Deleted company: {$company->company_name} (ID: {$company->id})",
        ]);
        $ownerId = $company->user_id; // store owner
        $company->delete();

        //========================== Send a special session key if the owner is currently logged in ==========================//
        if (auth()->id() == $ownerId) {
            return back()->with('deleted_by_admin', 'Your company has been deleted by admin.');
        }
        return back()->with('success', 'Company deleted successfully.');
    }
	//========================== Delete Post ==========================//
    public function destroyPost(Post $post)
    {
        $this->authorizeAdmin();

        if ($post->user_id === 1) {
            return back()->with('error', 'You cannot delete admin-owned posts.');
        }
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'deleted_post',
            'subject_type' => 'Post',
            'subject_id' => $post->id,
            'description' => "Deleted post ID: {$post->id}",
        ]);
        $ownerId = $post->user_id;
        $post->delete();

        if (auth()->id() == $ownerId) {
            return back()->with('deleted_by_admin', 'Your post has been deleted by admin.');
        }
        return back()->with('success', 'Post deleted successfully.');
    }
}