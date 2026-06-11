<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_logo',
        'email',
        'address',
        'contact_number',
        'about',
        'certificate_of_corporation',
        'certificate_of_registration',
        'mayors_permit',
        'barangay_clearance',
        'is_verified',
        'verified_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
