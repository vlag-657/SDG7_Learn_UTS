@if (session('success'))
    <div class="alert-dismissible" style="background:#eaf8ee; border:1px solid #c2ebd0; color:#186337; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; font-size:14px; box-shadow: 0 4px 12px rgba(35,118,77,0.08);">
        <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.closest('.alert-dismissible').remove()" style="background:none; border:none; cursor:pointer; color:#186337; font-size:18px; line-height:1;">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="alert-dismissible" style="background:#fff1f0; border:1px solid #ffd1cd; color:#a4261d; padding:14px 18px; border-radius:12px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; font-size:14px; box-shadow: 0 4px 12px rgba(220,38,38,0.08);">
        <div style="display:flex; align-items:center; gap:10px;">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" onclick="this.closest('.alert-dismissible').remove()" style="background:none; border:none; cursor:pointer; color:#a4261d; font-size:18px; line-height:1;">&times;</button>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert-dismissible" style="background:#fff1f0; border:1px solid #ffd1cd; color:#a4261d; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:14px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px; font-weight:700;">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>Periksa kembali formulir Anda:</span>
        </div>
        <ul style="margin:0; padding-left:24px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
