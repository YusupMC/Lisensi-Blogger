# 🔒 Lisensi Blogger

![License](https://img.shields.io/badge/license-MIT-blue.svg)

Sistem lisensi sederhana untuk memproteksi penggunaan template Blogger agar hanya bisa digunakan oleh domain yang telah terdaftar secara resmi.

---

## ✨ Fitur Utama

- ✅ Verifikasi lisensi langsung dari template Blogger.
- ✅ Dashboard Admin berbasis SBAdmin (Bootstrap 5).
- ✅ Autentikasi login via file konfigurasi.
- ✅ Manajemen lisensi (tambah, edit, hapus, reset).
- ✅ Data lisensi disimpan dalam format JSON.
- ✅ Endpoint publik (`proxy_lisensi.php`) untuk pengecekan lisensi.

---

## 📦 Instalasi

Upload semua file ke hosting / server yang mendukung PHP.  
Edit file konfigurasi di `config.php`:
- Ubah username dan password login admin.
- Sesuaikan path file JSON lisensi jika diperlukan.  
Buka `/admin` melalui browser dan login menggunakan akun yang telah diatur di `config.php`.

---

## 🌐 Integrasi di Template Blogger

Tambahkan kode berikut ke dalam `<head>` atau sebelum `</body>` pada template Blogger:

<pre>
&lt;script&gt;
document.addEventListener("DOMContentLoaded", function () {
  const domain = location.hostname;
  const apiUrl = "https://lisensi.yusupmadcani.my.id/proxy_lisensi.php?domain=" + domain;

  fetch(apiUrl)
    .then(response =&gt; {
      if (!response.ok) throw new Error("HTTP error " + response.status);
      return response.json();
    })
    .then(data =&gt; {
      if (data.status !== "active") {
        document.open();
        document.write(`
          &lt;div style='text-align:center;padding:40px;'&gt;
            &lt;h1 style='color:red;'&gt;Lisensi Tidak Valid / Kadaluarsa&lt;/h1&gt;
            &lt;p&gt;Hubungi pengembang untuk mendapatkan lisensi resmi.&lt;/p&gt;
          &lt;/div&gt;
        `);
        document.close();
      }
    })
    .catch(error =&gt; {
      console.error("Fetch error:", error);
      document.open();
      document.write(`
        &lt;div style='text-align:center;padding:40px;'&gt;
          &lt;h1 style='color:red;'&gt;Gagal Cek Lisensi&lt;/h1&gt;
          &lt;p&gt;Periksa koneksi internet atau hubungi admin.&lt;/p&gt;
        &lt;/div&gt;
      `);
      document.close();
    });
});
&lt;/script&gt;
</pre>

📌 **Catatan:** Pastikan domain pengguna sudah ditambahkan di dashboard admin dan endpoint `proxy_lisensi.php` dapat diakses secara publik.


---

## 🛠 Teknologi yang Digunakan

- PHP (Backend)  
- JSON (Sebagai database ringan)  
- SBAdmin Bootstrap 5 (Untuk UI admin)  
- JavaScript (Validasi lisensi di template Blogger)

---

## 👨‍💻 Kontribusi

Pull request sangat disambut!  
Untuk perubahan besar, silakan buka issue terlebih dahulu untuk diskusi.

---
![Version](https://img.shields.io/badge/version-v1.1-blue)

> **Versi:** `V.1.1`  
> Rilis pertama dari sistem Lisensi Blogger dengan fitur:
> - Validasi domain pengguna via `proxy_lisensi.php`
> - Dashboard Admin berbasis SBAdmin (Bootstrap 5)
> - Penyimpanan data lisensi menggunakan file JSON
> - Verifikasi lisensi langsung di template Blogger via JavaScript

[Lisensi-Blogger-main.zip](https://github.com/user-attachments/files/19615052/Lisensi-Blogger-main.zip)

