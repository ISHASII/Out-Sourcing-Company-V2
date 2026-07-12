# 📊 Dokumentasi Sistem Pendukung Keputusan (SPK) - Metode Profile Matching
## PT. Unggul Cipta Indah (Outsourcing Portal)

Dokumen ini menjelaskan secara rinci metodologi, rumus matematika, skala pembobotan, dan konfigurasi kriteria penilaian dalam **Sistem Pendukung Keputusan (SPK)** menggunakan metode **Profile Matching** (Pencocokan Profil) untuk seleksi pelamar kerja di PT. Unggul Cipta Indah.

---

## 📌 Pendahuluan Metode Profile Matching

**Profile Matching** adalah metode yang digunakan untuk membandingkan kompetensi/kualifikasi pelamar (profil kandidat) dengan standar kompetensi jabatan yang dibutuhkan (profil jabatan/ideal). 
Proses ini mencari selisih (**GAP**) antara kualifikasi pelamar dengan kualifikasi ideal. Semakin kecil GAP yang dihasilkan, maka bobot nilainya akan semakin besar, yang berarti pelamar memiliki kecocokan yang tinggi dengan posisi tersebut.

---

## 🧮 Alur & Rumus Perhitungan Matematika

Proses perhitungan SPK Profile Matching di sistem ini terdiri dari 6 tahap utama:

### Langkah 1: Menentukan Aspek & Kriteria Penilaian
Setiap lowongan pekerjaan memiliki konfigurasi kriteria yang dikelompokkan menjadi:
1. **Core Factor (CF)**: Kriteria utama yang paling dibutuhkan dan bersifat wajib dipenuhi oleh pelamar untuk menunjang performa pekerjaan.
2. **Secondary Factor (SF)**: Kriteria pendukung yang menjadi nilai tambah bagi pelamar.
3. **Nonaktif**: Kriteria yang diabaikan dan tidak dimasukkan ke dalam perhitungan bobot SPK untuk lowongan tersebut.

---

### Langkah 2: Menghitung Selisih (GAP)
Nilai GAP dihitung dengan mengurangkan nilai kualifikasi pelamar ($Cand$) dengan nilai target ideal lowongan ($Ideal$):

$$\text{GAP} = \text{Nilai Pelamar } (Cand) - \text{Nilai Ideal } (Ideal)$$

#### Aturan Penilaian Nilai Kualifikasi ($Cand$ vs $Ideal$):

1. **Jenis Kelamin (Gender)**
   * **Ideal** = $5$
   * **Pelamar** ($Cand$) = $5$ jika cocok dengan target (atau jika target adalah `both`/semua), dan $1$ jika tidak cocok.
   * $\text{GAP} = 0$ (cocok) atau $\text{GAP} = -4$ (tidak cocok).

2. **Usia**
   * **Ideal** = $5$
   * **Pelamar** ($Cand$):
     * Jika usia masuk dalam rentang target `[min_age, max_age]`, maka $Cand = 5$ ($\text{GAP} = 0$).
     * Jika usia tidak diisi (`null`), maka $Cand = 1$ ($\text{GAP} = -4$).
     * Jika usia kurang dari minimal (`usia < min`), maka $Cand = \max(1, 5 - (min - usia))$.
       * *Contoh:* Target min 25 tahun. Pelamar berusia 24 tahun $\rightarrow Cand = 4$ ($\text{GAP} = -1$).
     * Jika usia lebih dari maksimal (`usia > max`), maka $Cand = \max(1, 5 - (usia - max))$.
       * *Contoh:* Target max 35 tahun. Pelamar berusia 37 tahun $\rightarrow Cand = 3$ ($\text{GAP} = -2$).

3. **Pendidikan Terakhir**
   * Jenjang pendidikan dikonversi menjadi bobot nilai peringkat (rank):
     * **SMA/SMK** = $1$
     * **D3** = $2$
     * **S1** = $3$
     * **S2** = $4$
     * **S3** = $5$
   * $\text{GAP} = \text{Rank Pendidikan Pelamar} - \text{Rank Pendidikan Target (Ideal)}$
     * *Contoh:* Target pendidikan D3 (Rank 2), pendidikan pelamar S1 (Rank 3) $\rightarrow \text{GAP} = 3 - 2 = +1$ (kelebihan kompetensi).
     * *Contoh:* Target pendidikan D3 (Rank 2), pendidikan pelamar SMA (Rank 1) $\rightarrow \text{GAP} = 1 - 2 = -1$ (kekurangan kompetensi).

4. **Pengalaman Kerja (Tahun)**
   * $\text{GAP} = \text{Tahun Pengalaman Pelamar} - \text{Target Minimal Pengalaman}$
     * *Contoh:* Target min 0 tahun, pelamar memiliki 3 tahun $\rightarrow \text{GAP} = 3 - 0 = +3$.

5. **Kesiapan Penempatan (Placement Ready)**
   * **Ideal** = $5$
   * **Pelamar** ($Cand$) = $5$ jika bersedia ditempatkan di mana saja, dan $1$ jika tidak bersedia.
   * $\text{GAP} = 0$ atau $\text{GAP} = -4$.

6. **Jurusan (Major)**
   * **Ideal** = $5$
   * **Pelamar** ($Cand$) = $5$ jika jurusan cocok dengan daftar target yang diperbolehkan, dan $1$ jika tidak cocok.
   * $\text{GAP} = 0$ atau $\text{GAP} = -4$.

7. **Pilihan Kota Penempatan (Placement Choices)**
   * **Ideal** = $5$
   * **Pelamar** ($Cand$) = $5$ jika pilihan kota pelamar ada di dalam daftar pilihan kota target lowongan, dan $1$ jika tidak cocok.
   * $\text{GAP} = 0$ atau $\text{GAP} = -4$.

8. **Dokumen Kustom & Checkbox (SIM C, SIM B1, Sertifikat AGD, Keahlian Gardener/Runner, dll.)**
   * **Ideal** = $5$
   * **Pelamar** ($Cand$) = $5$ jika memiliki berkas/mencentang keahlian, dan $1$ jika tidak memiliki/tidak dicentang.
   * $\text{GAP} = 0$ atau $\text{GAP} = -4$.

---

### Langkah 3: Konversi GAP ke Bobot Nilai (Skala Kusrini, 2007)
Setelah nilai GAP diperoleh, dilakukan konversi GAP menjadi bobot nilai standar menggunakan tabel konversi berikut:

| Nilai GAP | Bobot Nilai | Keterangan |
|:---:|:---:|---|
| **0** | **5.0** | Kompetensi sesuai dengan kebutuhan (Ideal) |
| **1** | **4.5** | Kompetensi pelamar kelebihan 1 tingkat |
| **-1** | **4.0** | Kompetensi pelamar kekurangan 1 tingkat |
| **2** | **3.5** | Kompetensi pelamar kelebihan 2 tingkat |
| **-2** | **3.0** | Kompetensi pelamar kekurangan 2 tingkat |
| **3** | **2.5** | Kompetensi pelamar kelebihan 3 tingkat |
| **-3** | **2.0** | Kompetensi pelamar kekurangan 3 tingkat |
| **4** | **1.5** | Kompetensi pelamar kelebihan 4 tingkat |
| **-4** | **1.0** | Kompetensi pelamar kekurangan 4 tingkat |
| **> 4** | **1.5** | Kompetensi pelamar kelebihan luar batas |
| **< -4** | **1.0** | Kompetensi pelamar kekurangan luar batas |

---

### Langkah 4: Pengelompokan & Perhitungan Nilai Rata-rata Faktor (NCF & NSF)
Kriteria-kriteria yang telah memiliki bobot kemudian dikelompokkan menjadi **Core Factor** dan **Secondary Factor** untuk dihitung rata-ratanya masing-masing.

#### 1. Rumus Core Factor (NCF - Nilai Core Factor):
$$NCF = \frac{\sum \text{Bobot Nilai Core Factor (CF)}}{\text{Jumlah Kriteria Core Factor}}$$
*(Jika tidak ada kriteria Core Factor yang aktif, nilai NCF default adalah 5.0)*

#### 2. Rumus Secondary Factor (NSF - Nilai Secondary Factor):
$$NSF = \frac{\sum \text{Bobot Nilai Secondary Factor (SF)}}{\text{Jumlah Kriteria Secondary Factor}}$$
*(Jika tidak ada kriteria Secondary Factor yang aktif, nilai NSF default adalah 5.0)*

---

### Langkah 5: Menghitung Nilai Akhir (Total Score)
Nilai Akhir diperoleh dengan menggabungkan persentase bobot kelompok NCF dan NSF. Dalam sistem ini, kontribusi **Core Factor adalah 60%** dan **Secondary Factor adalah 40%**:

$$\text{Nilai Akhir} = (0.6 \times NCF) + (0.4 \times NSF)$$

---

### Langkah 6: Konversi Nilai Akhir ke Matching Score (%)
Nilai Akhir yang berkisar pada skala $1.0$ hingga $5.0$ dikonversi menjadi persentase kecocokan (*Matching Score*) dari $0\%$ hingga $100\%$ menggunakan rumus:

$$\text{Matching Score (\%)} = \text{round}\left( \frac{\text{Nilai Akhir} - 1.0}{4.0} \times 100\% \right)$$

#### 🛡️ Klasifikasi Prioritas (Lolos Core)
Pelamar akan otomatis diklasifikasikan sebagai:
* **Prioritas (Lolos Core)**: Jika pelamar memenuhi **semua** kualifikasi yang bertipe **Core Factor** (tidak ada kegagalan kecocokan atau gap negatif pada kriteria bertipe Core).
* **Tidak Prioritas**: Jika pelamar gagal memenuhi **salah satu saja** kriteria bertipe **Core Factor** (misalnya, usia di luar batas aman core, jenis kelamin tidak sesuai core, atau tidak mengunggah dokumen core yang diwajibkan).

---

## 📋 Konfigurasi Kriteria per Kategori Pekerjaan
Berikut adalah pembagian tabel kriteria, target ideal, serta klasifikasi faktor (Core/Secondary) untuk masing-masing **6 kategori pekerjaan** yang terdaftar di dalam sistem:

### 1. Kategori: Driver Ambulance

| Nama Kriteria | Target Ideal | Tipe Faktor | Keterangan / Pengukuran |
|---|---|---|---|
| **Jenis Kelamin** | Pria (Male) | **Core Factor** | Wajib Pria. Jika Wanita, status prioritas gugur. |
| **Usia** | 25 - 35 Tahun | **Core Factor** | Usia di luar 25-35 akan mengurangi bobot dan menggugurkan prioritas. |
| **Pendidikan Min.** | SMA/SMK | **Core Factor** | Min. SMA/SMK. Pendidikan di atasnya (D3/S1) mendapat gap positif (+). |
| **Kesiapan Penempatan**| Bersedia (True) | **Core Factor** | Wajib bersedia ditempatkan di mana saja di area UCI. |
| **SIM B1 (Mobil Berat)**| Diunggah (True) | **Core Factor** | Wajib mengunggah bukti lisensi SIM B1 aktif. |
| **Pengalaman Kerja** | Min. 0 Tahun | **Secondary Factor** | Nilai tambah jika memiliki pengalaman lebih dari 0 tahun. |
| **Sertifikat AGD** | Diunggah (True) | **Secondary Factor** | Bukti Sertifikat Penanganan Gawat Darurat (AGD) Ambulans. |
| **SIM C (Motor)** | Diunggah (True) | **Secondary Factor** | Bukti SIM C untuk mobilitas pendukung. |
| **Jurusan** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |
| **Pilihan Kota** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |

---

### 2. Kategori: Cleaning Service

| Nama Kriteria | Target Ideal | Tipe Faktor | Keterangan / Pengukuran |
|---|---|---|---|
| **Jenis Kelamin** | Pria & Wanita (Both)| **Core Factor** | Tidak membatasi gender. Pria atau Wanita bernilai cocok (Gap 0). |
| **Usia** | 25 - 65 Tahun | **Core Factor** | Rentang usia produktif yang cukup luas untuk pekerja fisik. |
| **Pendidikan Min.** | SMA/SMK | **Core Factor** | Standar pendidikan minimal pelamar. |
| **Pengalaman Kerja** | Min. 0 Tahun | **Core Factor** | Dihitung sebagai Core Factor (min. 0 tahun). |
| **Kesiapan Penempatan**| Bersedia (True) | **Core Factor** | Wajib bersedia ditempatkan di unit mana saja. |
| **Pilihan Kota** | Jakarta Barat / Tangerang| **Secondary Factor** | Nilai tambah jika domisili/pilihan pelamar sesuai target. |
| **SIM C (Motor)** | Diunggah (True) | **Secondary Factor** | Nilai tambah jika memiliki SIM C untuk fleksibilitas berangkat kerja. |
| **Jurusan** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |
| **Sertifikat AGD** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |
| **SIM B1 (Mobil)** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |

---

### 3. Kategori: Asisten Keperawatan

| Nama Kriteria | Target Ideal | Tipe Faktor | Keterangan / Pengukuran |
|---|---|---|---|
| **Jenis Kelamin** | Pria & Wanita (Both)| **Core Factor** | Terbuka untuk semua gender. |
| **Usia** | 25 - 65 Tahun | **Core Factor** | Rentang usia kerja perawat medis asisten. |
| **Pendidikan Min.** | SMA/SMK | **Core Factor** | Standar pendidikan keperawatan vokasional. |
| **Jurusan** | Keperawatan | **Core Factor** | Wajib berlatar belakang jurusan Keperawatan. |
| **Kesiapan Penempatan**| Bersedia (True) | **Core Factor** | Wajib siap ditempatkan di mana saja. |
| **Surat Tanda Registrasi**| Diunggah (True) | **Core Factor** | Wajib mengunggah STR aktif. Jika kosong, gugur prioritas. |
| **Sertifikat Kompetensi**| Diunggah (True) | **Core Factor** | Wajib memiliki Sertifikat Kompetensi Keperawatan. |
| **Pengalaman Kerja** | Min. 0 Tahun | **Secondary Factor** | Tambahan bobot nilai jika memiliki pengalaman tahunan. |
| **Pilihan Kota** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |
| **SIM C / SIM B1** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |

---

### 4. Kategori: Runner

| Nama Kriteria | Target Ideal | Tipe Faktor | Keterangan / Pengukuran |
|---|---|---|---|
| **Jenis Kelamin** | Pria (Male) | **Core Factor** | Kebutuhan fisik lapangan diutamakan Pria. |
| **Usia** | 23 - 35 Tahun | **Core Factor** | Usia prima untuk bergerak cepat. |
| **Pendidikan Min.** | SMA/SMK | **Core Factor** | Standar pendidikan minimal. |
| **Pengalaman Kerja** | Min. 0 Tahun | **Core Factor** | Minimal 0 tahun (Fresh graduate dipersilakan). |
| **Jurusan** | Kesehatan / Umum | **Core Factor** | Wajib berlatar belakang kesehatan atau umum. |
| **Kesiapan Penempatan**| Bersedia (True) | **Secondary Factor** | Kesiapan penempatan umum bernilai pendukung. |
| **Dukungan Medis** | Dicentang (True) | **Secondary Factor** | Menguasai alat bantu pendukung medis (P3K/oksigen). |
| **Istilah Medis** | Dicentang (True) | **Secondary Factor** | Memahami istilah medis dasar untuk penyerahan obat/berkas. |
| **SIM B1 / AGD** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |

---

### 5. Kategori: Gardener

| Nama Kriteria | Target Ideal | Tipe Faktor | Keterangan / Pengukuran |
|---|---|---|---|
| **Jenis Kelamin** | Pria (Male) | **Core Factor** | Diutamakan Pria untuk pekerjaan pertamanan luar ruangan. |
| **Usia** | 25 - 40 Tahun | **Core Factor** | Rentang usia matang yang memiliki daya tahan fisik baik. |
| **Pendidikan Min.** | SMA/SMK | **Core Factor** | Kualifikasi pendidikan minimal. |
| **Pengalaman Kerja** | Min. 0 Tahun | **Core Factor** | Dihitung sebagai Core Factor. |
| **Pemahaman Teknik** | Dicentang (True) | **Core Factor** | Wajib mengerti teknik lansekap/desain taman dasar. |
| **Keahlian Pembibitan**| Dicentang (True) | **Core Factor** | Wajib mengerti cara merawat bibit tanaman (Nursery). |
| **Kesiapan Penempatan**| Bersedia (True) | **Secondary Factor** | Bersedia ditempatkan di kantor regional mana saja. |
| **Keahlian Alat Berat** | Dicentang (True) | **Secondary Factor** | Mengerti penggunaan mesin pemotong rumput/alat siram otomatis. |
| **Jurusan** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |
| **SIM B1 / AGD** | Bebas | *Nonaktif* | Tidak dihitung dalam bobot SPK. |

---

### 6. Kategori: Bell Boy (Konfigurasi Dinamis / Contoh Default)

*Kategori Bell Boy di dalam portal merupakan kategori dengan pengaturan parameter yang fleksibel dan dapat diatur secara dinamis oleh HRD sewaktu membuat lowongan baru. Berikut adalah visualisasi pengaturan default sistem:*

| Nama Kriteria | Target Ideal | Tipe Faktor | Keterangan / Pengukuran |
|---|---|---|---|
| **Jenis Kelamin** | Pria (Male) | **Core Factor** | Diutamakan Pria untuk pengangkatan bagasi tamu. |
| **Usia** | 18 - 30 Tahun | **Core Factor** | Usia prima dengan fisik kuat dan penampilan rapi. |
| **Pendidikan Min.** | SMA/SMK | **Core Factor** | Standar minimal pendidikan perhotelan/umum. |
| **Kesiapan Penempatan**| Bersedia (True) | **Core Factor** | Siap ditempatkan di lobby/unit kerja mana saja. |
| **Pengalaman Kerja** | Min. 1 Tahun | **Secondary Factor** | Nilai tambah jika memiliki pengalaman di perhotelan. |
| **Kemampuan Bahasa** | Diunggah (True) | **Secondary Factor** | Nilai tambah berupa sertifikat kemampuan Bahasa Inggris (kustom doc). |
| **Jurusan** | Bebas / Pariwisata | *Nonaktif* | Opsional/Bebas, kecuali dikonfigurasi aktif oleh HRD. |
| **SIM B1 / SIM C** | Bebas | *Nonaktif* | Tidak diperlukan, kecuali diaktifkan oleh HRD. |

---

## 💡 Contoh Kasus Perhitungan Lengkap

Misalkan seorang pelamar bernama **Rian Hidayat** melamar sebagai **Gardener** dengan data profil sebagai berikut:
* Gender: **Pria (Male)** $\rightarrow$ Target Gardener: Pria (Core)
* Usia: **28 Tahun** $\rightarrow$ Target Gardener: 25 - 40 Tahun (Core)
* Pendidikan: **SMA/SMK (Rank 1)** $\rightarrow$ Target Gardener: SMA/SMK (Rank 1) (Core)
* Pengalaman Kerja: **2 Tahun** $\rightarrow$ Target Gardener: 0 Tahun (Core)
* Pemahaman Teknik: **Dicentang (True)** $\rightarrow$ Target Gardener: Wajib (Core)
* Keahlian Pembibitan: **Dicentang (True)** $\rightarrow$ Target Gardener: Wajib (Core)
* Kesiapan Penempatan: **Bersedia (True)** $\rightarrow$ Target Gardener: Bersedia (Secondary)
* Keahlian Alat Berat: **Tidak Dicentang (False)** $\rightarrow$ Target Gardener: Pendukung (Secondary)

### Perhitungan GAP & Bobot Nilai:

#### 1. Core Factors (CF):
1. **Gender**: Pelamar = Pria, Target = Pria (Cocok).
   * $\text{GAP} = 5 - 5 = 0 \rightarrow \text{Bobot} = 5.0$
2. **Usia**: Usia pelamar 28 berada dalam rentang 25 - 40 (Cocok).
   * $\text{GAP} = 5 - 5 = 0 \rightarrow \text{Bobot} = 5.0$
3. **Pendidikan**: Pelamar = SMA (Rank 1), Target = SMA (Rank 1).
   * $\text{GAP} = 1 - 1 = 0 \rightarrow \text{Bobot} = 5.0$
4. **Pengalaman**: Pelamar = 2 tahun, Target = 0 tahun.
   * $\text{GAP} = 2 - 0 = +2 \rightarrow \text{Bobot} = 3.5$ (kelebihan kompetensi 2 tingkat)
5. **Pemahaman Teknik**: Dicentang (Cocok).
   * $\text{GAP} = 5 - 5 = 0 \rightarrow \text{Bobot} = 5.0$
6. **Keahlian Pembibitan**: Dicentang (Cocok).
   * $\text{GAP} = 5 - 5 = 0 \rightarrow \text{Bobot} = 5.0$

*Rata-rata Core Factor ($NCF$):*
$$NCF = \frac{5.0 + 5.0 + 5.0 + 3.5 + 5.0 + 5.0}{6} = \frac{28.5}{6} = 4.75$$

#### 2. Secondary Factors (SF):
1. **Kesiapan Penempatan**: Bersedia (Cocok).
   * $\text{GAP} = 5 - 5 = 0 \rightarrow \text{Bobot} = 5.0$
2. **Keahlian Alat Berat**: Tidak dicentang (Tidak cocok).
   * $\text{GAP} = 1 - 5 = -4 \rightarrow \text{Bobot} = 1.0$

*Rata-rata Secondary Factor ($NSF$):*
$$NSF = \frac{5.0 + 1.0}{2} = \frac{6.0}{2} = 3.0$$

#### 3. Nilai Akhir (Total Score):
$$\text{Nilai Akhir} = (0.6 \times 4.75) + (0.4 \times 3.0) = 2.85 + 1.2 = 4.05$$

#### 4. Persentase Matching Score:
$$\text{Matching Score} = \text{round}\left( \frac{4.05 - 1.0}{4.0} \times 100\% \right) = \text{round}(0.7625 \times 100\%) = 76\%$$

#### 5. Klasifikasi Prioritas:
Karena pelamar memenuhi **seluruh** kriteria **Core Factor** (tidak ada yang gugur atau bernilai $1$ pada kriteria Core), status Rian Hidayat adalah **Prioritas (Lolos Core)** dengan skor kecocokan **76%**.

---

Dibuat untuk memudahkan transparansi operasional HRD PT. Unggul Cipta Indah dalam menyeleksi tenaga kerja outsourcing berkualitas tinggi. 🚀
