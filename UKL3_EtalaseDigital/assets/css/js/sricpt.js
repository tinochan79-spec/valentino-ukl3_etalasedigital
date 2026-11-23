document.addEventListener('DOMContentLoaded', () => {
  // Menu mobile
  const toggle = document.querySelector('.nav-toggle');
  const menu = document.getElementById('menu');
  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const buka = menu.classList.toggle('terbuka');
      toggle.setAttribute('aria-expanded', buka);
    });
  }

  // Konfirmasi sebelum hapus
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
  });

  // Pesan flash: tutup manual atau otomatis
  document.querySelectorAll('.flash').forEach(f => {
    const tutup = f.querySelector('button');
    if (tutup) tutup.addEventListener('click', () => f.remove());
    if (f.classList.contains('flash-sukses')) setTimeout(() => f.remove(), 6000);
  });

  // Tampilkan / sembunyikan password
  document.querySelectorAll('[data-toggle-pw]').forEach(b => {
    b.addEventListener('click', () => {
      const i = document.getElementById(b.dataset.togglePw);
      const lihat = i.type === 'password';
      i.type = lihat ? 'text' : 'password';
      b.textContent = lihat ? 'Sembunyikan' : 'Tampilkan';
    });
  });

  // Pratinjau gambar + cek ukuran/format di sisi klien
  const file = document.getElementById('gambar');
  const preview = document.getElementById('preview');
  if (file && preview) {
    file.addEventListener('change', () => {
      const f = file.files[0];
      const pesan = file.closest('.field').querySelector('.error-text');
      pesan.textContent = '';
      if (!f) return;
      if (!['image/jpeg', 'image/png', 'image/webp'].includes(f.type)) {
        pesan.textContent = 'Format gambar harus JPG, PNG, atau WebP.'; file.value = ''; return;
      }
      if (f.size > 2 * 1024 * 1024) {
        pesan.textContent = 'Ukuran gambar maksimal 2 MB.'; file.value = ''; return;
      }
      preview.src = URL.createObjectURL(f);
      preview.hidden = false;
    });
  }

  // Validasi form interaktif
  const aturan = {
    username: v => /^[a-zA-Z0-9_]{4,30}$/.test(v) || 'Username 4–30 karakter: huruf, angka, atau garis bawah.',
    password: v => v === '' || v.length >= 6 || 'Password minimal 6 karakter.',
  };
  function cek(input) {
    const pesan = input.closest('.field')?.querySelector('.error-text');
    if (!pesan) return true;
    let hasil = true;
    const v = input.value.trim();
    if (input.required && v === '' && input.type !== 'file') hasil = 'Kolom ini wajib diisi.';
    else if (input.dataset.match) {
      const lain = document.getElementById(input.dataset.match);
      if (lain && input.value !== lain.value) hasil = 'Konfirmasi password tidak sama.';
    } else if (input.type === 'url' && v !== '' && !/^https?:\/\/.+/i.test(v)) hasil = 'Tautan harus diawali http:// atau https://.';
    else if (input.id === 'username' && input.closest('form').querySelector('#password[autocomplete="new-password"]')) hasil = aturan.username(v);
    else if (input.id === 'password' && input.autocomplete === 'new-password') hasil = aturan.password(input.value);
    else if (input.minLength > 0 && v !== '' && v.length < input.minLength) hasil = 'Minimal ' + input.minLength + ' karakter.';
    pesan.textContent = hasil === true ? '' : hasil;
    input.classList.toggle('invalid', hasil !== true);
    return hasil === true;
  }
  document.querySelectorAll('form[data-validate]').forEach(form => {
    const inputs = form.querySelectorAll('input:not([type=hidden]):not([type=file]), textarea');
    inputs.forEach(i => i.addEventListener('blur', () => cek(i)));
    form.addEventListener('submit', e => {
      let valid = true;
      inputs.forEach(i => { if (!cek(i)) valid = false; });
      if (!valid) { e.preventDefault(); form.querySelector('.invalid')?.focus(); }
    });
  });
});
    