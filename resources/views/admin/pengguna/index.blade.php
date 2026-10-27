@extends('layouts.app')

@section('title', 'Kelola Pengguna (Admin)')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
    <div>
        <a href="{{ route('admin.dashboard') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Dashboard Admin</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Daftar Pengguna Terdaftar
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Kelola dan pantau data akun pelajar serta administrator sistem.
        </p>
    </div>
</div>

<!-- Toolbar Search & Role Filter -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:24px; box-shadow:var(--shadow);">
    <form action="{{ route('admin.pengguna.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; margin:0;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
            style="flex:1; max-width:360px; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; outline:none;">

        <select name="role" onchange="this.form.submit()" style="height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; background:#fff; color:var(--ink); cursor:pointer;">
            <option value="">Semua Peran</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User (Pelajar)</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>

        <button type="submit" class="button button-quiet button-small">Cari</button>
        @if(request('q') || request('role'))
            <a href="{{ route('admin.pengguna.index') }}" class="button button-quiet button-small">Reset</a>
        @endif
    </form>
</div>

<!-- Tabel Pengguna -->
<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px; width:60px;">ID</th>
                    <th style="padding:16px 20px;">Nama & Email</th>
                    <th style="padding:16px 20px;">Peran</th>
                    <th style="padding:16px 20px;">Pekerjaan / Usia</th>
                    <th style="padding:16px 20px;">Aktivitas (Kuis/Post)</th>
                    <th style="padding:16px 20px; text-align:right;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengguna as $user)
                    <tr style="border-bottom:1px solid var(--line);">
                        <td style="padding:16px 20px; color:var(--muted); font-size:12px;">#{{ $user->id }}</td>
                        <td style="padding:16px 20px;">
                            <strong style="color:var(--ink); font-size:14px; display:block;">{{ $user->nama }}</strong>
                            <span style="color:var(--muted); font-size:12px;">{{ $user->email }}</span>
                        </td>
                        <td style="padding:16px 20px;">
                            <span style="display:inline-block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:3px 10px; border-radius:99px; background:{{ $user->role === 'admin' ? '#fef3c7' : '#e6f4ea' }}; color:{{ $user->role === 'admin' ? '#92400e' : '#137333' }};">
                                {{ strtoupper($user->role) }}
                            </span>
                        </td>
                        <td style="padding:16px 20px; font-size:13px; color:var(--muted);">
                            {{ $user->pekerjaan ?? '-' }} {{ $user->usia ? '(' . $user->usia . ' thn)' : '' }}
                        </td>
                        <td style="padding:16px 20px; font-size:13px;">
                            <span style="font-weight:700; color:var(--deep-green);">{{ $user->hasil_kuis_count ?? 0 }}</span> Kuis &bull;
                            <span style="font-weight:700; color:var(--deep-green);">{{ $user->postingan_count ?? 0 }}</span> Thread
                        </td>
                        <td style="padding:16px 20px; text-align:right;">
                            <a href="{{ route('admin.pengguna.show', $user->id) }}" class="button button-quiet button-small" style="font-size:12px;">
                                Profil & Aktivitas &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:40px 20px; text-align:center; color:var(--muted);">
                            Tidak ada data pengguna yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $pengguna->links() }}
</div>
@endsection
