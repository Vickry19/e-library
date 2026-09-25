<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'alamat',
        'email',
        'image',
        'password',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function totalKeranjang()
    {
        return Temp::where('id_user', $this->id)->count();
    }

    public function totalBooking()
    {
        return BookingDetail::whereIn('id_booking', function ($query) {
            $query->select('id_booking')
                ->from(with(new Booking)->getTable())
                ->where('id_user', $this->id);
        })->count();
    }

    public function totalSedangPinjam()
    {
        return PinjamDetail::whereIn('no_pinjam', function ($query) {
            $query->select('no_pinjam')
                ->from(with(new Pinjam)->getTable())
                ->where('status', 'Pinjam')
                ->where('id_user', $this->id);
        })->count();
    }

    public function totalRiwayatPinjam()
    {
        return PinjamDetail::whereIn('no_pinjam', function ($query) {
            $query->select('no_pinjam')
                ->from(with(new Pinjam)->getTable())
                ->where('id_user', $this->id);
        })->count();
    }
}