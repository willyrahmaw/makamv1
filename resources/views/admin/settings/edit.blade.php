@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear-fill me-2"></i>Pengaturan Website
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h6 class="text-muted mb-3 mt-2"><i class="bi bi-info-circle me-2"></i>Informasi Umum</h6>

                    <div class="mb-3">
                        <label class="form-label">Nama Website <span class="text-danger">*</span></label>
                        <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" 
                               value="{{ old('site_name', $settings['site_name'] ?? '') }}" required>
                        @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Nama website yang akan ditampilkan di navbar dan footer</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Website</label>
                        <textarea name="site_description" class="form-control @error('site_description') is-invalid @enderror" rows="3" 
                                  placeholder="Deskripsi singkat tentang website">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                        @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Deskripsi yang akan ditampilkan di footer</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Logo Website</label>
                        @if(isset($settings['site_logo']) && $settings['site_logo'])
                        <div class="mb-2">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['site_logo']) }}" alt="Logo" style="max-height: 100px;" class="img-thumbnail">
                            <div class="form-check mt-2">
                                <input type="checkbox" name="delete_logo" value="1" class="form-check-input" id="delete_logo">
                                <label class="form-check-label text-danger" for="delete_logo">
                                    Hapus logo saat ini
                                </label>
                            </div>
                        </div>
                        @endif
                        <input type="file" name="site_logo" class="form-control @error('site_logo') is-invalid @enderror" 
                               accept="image/*">
                        @error('site_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Format: JPG, PNG, GIF, SVG. Maksimal 2MB</small>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3 mt-4"><i class="bi bi-file-text me-2"></i>Footer</h6>

                    <div class="mb-3">
                        <label class="form-label">Teks Footer</label>
                        <textarea name="footer_text" class="form-control @error('footer_text') is-invalid @enderror" rows="2" 
                                  placeholder="Teks yang akan ditampilkan di footer">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea>
                        @error('footer_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Contoh: © 2024 Digitalisasi Makam. Semua hak dilindungi.</small>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3 mt-4"><i class="bi bi-search me-2"></i>SEO (Search Engine Optimization)</h6>

                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="2" 
                                  maxlength="255" placeholder="Deskripsi singkat untuk mesin pencari (maksimal 255 karakter)">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Deskripsi yang akan muncul di hasil pencarian Google</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" 
                               placeholder="Contoh: makam, digitalisasi, ngadirejo, temanggung">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kata kunci yang dipisahkan dengan koma</small>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3 mt-4"><i class="bi bi-geo-alt me-2"></i>Peta & Layanan Kelurahan</h6>
                    <p class="text-muted small mb-3">Tampilan di halaman Peta (iframe peta) dan daftar layanan kelurahan.</p>

                    <div class="mb-3">
                        <label class="form-label">URL Embed Peta (Google Maps)</label>
                        <input type="url" name="map_embed_url" class="form-control @error('map_embed_url') is-invalid @enderror" 
                               value="{{ old('map_embed_url', $settings['map_embed_url'] ?? '') }}" 
                               placeholder="https://www.google.com/maps/embed?pb=...">
                        @error('map_embed_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Salin link embed dari Google Maps (Share → Embed map → copy src="...")</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Layanan Kelurahan</label>
                        <textarea name="layanan_kelurahan" class="form-control @error('layanan_kelurahan') is-invalid @enderror" rows="6" 
                                  placeholder="Satu layanan per baris">{{ old('layanan_kelurahan', $settings['layanan_kelurahan'] ?? '') }}</textarea>
                        @error('layanan_kelurahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Daftar layanan kelurahan, satu per baris. Contoh: Surat Keterangan Domisili, KK, KTP-El, dll.</small>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3 mt-4"><i class="bi bi-palette me-2"></i>Pengaturan Warna Blok Makam</h6>
                    <p class="text-muted small mb-3">Atur warna blok berdasarkan jumlah makam yang terisi. Warna akan otomatis diterapkan pada denah makam.</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna Merah (Penuh) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="color_merah" class="form-control form-control-color" 
                                       value="{{ old('blok_warna_merah', $settings['blok_warna_merah'] ?? '#FF6B6B') }}" 
                                       title="Pilih warna merah" onchange="document.getElementById('text_merah').value = this.value">
                                <input type="text" name="blok_warna_merah" id="text_merah" class="form-control" 
                                       value="{{ old('blok_warna_merah', $settings['blok_warna_merah'] ?? '#FF6B6B') }}" 
                                       pattern="^#[0-9A-Fa-f]{6}$" placeholder="#FF6B6B" 
                                       onchange="document.getElementById('color_merah').value = this.value">
                            </div>
                            <small class="text-muted">Warna untuk blok yang penuh (jumlah makam >= threshold merah)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna Kuning (Lumayan) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="color_kuning" class="form-control form-control-color" 
                                       value="{{ old('blok_warna_kuning', $settings['blok_warna_kuning'] ?? '#FFD93D') }}" 
                                       title="Pilih warna kuning" onchange="document.getElementById('text_kuning').value = this.value">
                                <input type="text" name="blok_warna_kuning" id="text_kuning" class="form-control" 
                                       value="{{ old('blok_warna_kuning', $settings['blok_warna_kuning'] ?? '#FFD93D') }}" 
                                       pattern="^#[0-9A-Fa-f]{6}$" placeholder="#FFD93D"
                                       onchange="document.getElementById('color_kuning').value = this.value">
                            </div>
                            <small class="text-muted">Warna untuk blok yang lumayan terisi (threshold kuning <= jumlah < threshold merah)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna Hijau (Ada) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="color_hijau" class="form-control form-control-color" 
                                       value="{{ old('blok_warna_hijau', $settings['blok_warna_hijau'] ?? '#6BCF7F') }}" 
                                       title="Pilih warna hijau" onchange="document.getElementById('text_hijau').value = this.value">
                                <input type="text" name="blok_warna_hijau" id="text_hijau" class="form-control" 
                                       value="{{ old('blok_warna_hijau', $settings['blok_warna_hijau'] ?? '#6BCF7F') }}" 
                                       pattern="^#[0-9A-Fa-f]{6}$" placeholder="#6BCF7F"
                                       onchange="document.getElementById('color_hijau').value = this.value">
                            </div>
                            <small class="text-muted">Warna untuk blok yang masih ada tempat (jumlah < threshold kuning)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Warna Putih (Default/Kosong) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="color" id="color_putih" class="form-control form-control-color" 
                                       value="{{ old('blok_warna_putih', $settings['blok_warna_putih'] ?? '#FFFFFF') }}" 
                                       title="Pilih warna putih" onchange="document.getElementById('text_putih').value = this.value">
                                <input type="text" name="blok_warna_putih" id="text_putih" class="form-control" 
                                       value="{{ old('blok_warna_putih', $settings['blok_warna_putih'] ?? '#FFFFFF') }}" 
                                       pattern="^#[0-9A-Fa-f]{6}$" placeholder="#FFFFFF"
                                       onchange="document.getElementById('color_putih').value = this.value">
                            </div>
                            <small class="text-muted">Warna default untuk blok kosong (0 makam)</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Threshold Merah (Penuh) <span class="text-danger">*</span></label>
                            <input type="number" name="blok_threshold_merah" class="form-control @error('blok_threshold_merah') is-invalid @enderror" 
                                   value="{{ $errors->has('blok_threshold_merah') ? old('blok_threshold_merah') : ($settings['blok_threshold_merah'] ?? '10') }}" 
                                   min="0" required>
                            @error('blok_threshold_merah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jumlah makam minimum untuk blok dianggap penuh (warna merah)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Threshold Kuning (Lumayan) <span class="text-danger">*</span></label>
                            <input type="number" name="blok_threshold_kuning" class="form-control @error('blok_threshold_kuning') is-invalid @enderror" 
                                   value="{{ $errors->has('blok_threshold_kuning') ? old('blok_threshold_kuning') : ($settings['blok_threshold_kuning'] ?? '5') }}" 
                                   min="0" required>
                            @error('blok_threshold_kuning')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jumlah makam minimum untuk blok dianggap lumayan terisi (warna kuning)</small>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Keterangan:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Merah (Penuh):</strong> Jumlah makam >= Threshold Merah</li>
                            <li><strong>Kuning (Lumayan):</strong> Threshold Kuning <= Jumlah makam < Threshold Merah</li>
                            <li><strong>Hijau (Ada):</strong> 1 <= Jumlah makam < Threshold Kuning</li>
                            <li><strong>Putih (Kosong):</strong> Jumlah makam = 0</li>
                        </ul>
                    </div>

                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
