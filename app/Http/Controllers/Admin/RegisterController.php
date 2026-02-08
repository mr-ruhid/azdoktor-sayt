<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | Bu kontroller yeni istifadəçilərin qeydiyyatı və yaradılmasını idarə edir.
    | "RegistersUsers" traiti (xüsusiyyəti) lazımi metodları avtomatik təmin edir.
    |
    */

    use RegistersUsers;

    /**
     * Qeydiyyatdan sonra istifadəçilərin yönləndiriləcəyi yer.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Yeni bir controller instansiyası yaradın.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Tətbiq üçün qeydiyyat formasını göstərin.
     * * Biz standart 'auth.register' faylı əvəzinə xüsusi 'auth.user_register' faylını istifadə edirik.
     * Həmçinin layout üçün lazımi Page obyektini (SEO başlığı üçün) göndəririk.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Qeydiyyat');

        return view('auth.user_register', compact('page'));
    }

    /**
     * Gələn qeydiyyat sorğusu üçün bir doğrulama (validator) instansiyası əldə edin.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'], // Telefon nömrəsi opsionaldır
            'birth_date' => ['required', 'date'], // Doğum tarixi məcburidir
        ]);
    }

    /**
     * Doğrulama keçdikdən sonra yeni bir istifadəçi instansiyası yaradın.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'birth_date' => $data['birth_date'],
            'role_type' => 0, // 0 = Pasiyent (Standart İstifadəçi)
            'password' => Hash::make($data['password']),
        ]);
    }
}
