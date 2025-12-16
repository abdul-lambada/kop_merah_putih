# Koperasi Merah Putih Desa

Sistem manajemen koperasi digital untuk mendukung pertumbuhan ekonomi desa dengan fitur lengkap untuk manajemen anggota, simpan pinjam, dan unit usaha.

## 🚀 Fitur Utama

### 🏠 Landing Page Modern
- Hero section yang menarik dengan animasi
- Statistik dinamis dengan counter animation
- Service tabs yang interaktif
- Form registrasi anggota yang modern
- Fully responsive design
- Micro-interactions dan smooth animations

### 👥 Manajemen Anggota
- Registrasi anggota baru dengan validasi lengkap
- Manajemen data anggota dengan CRUD operations
- Verifikasi anggota dengan workflow approval
- Export data anggota ke CSV
- Filter berdasarkan status dan sektor usaha

### 💰 Sistem Simpan Pinjam
- Manajemen simpanan anggota
- Pengajuan dan persetujuan pinjaman
- Tracking pembayaran pinjaman
- Kalkulasi bunga otomatis
- Laporan keuangan lengkap

### 🏪 Unit Usaha
- Manajemen berbagai unit usaha (Pertanian, Peternakan, Perikanan, UMKM)
- Tracking transaksi per unit
- Laporan performa unit usaha
- Integrasi dengan sistem simpan pinjam

### 📊 Reporting & Analytics
- Dashboard analytics real-time
- Export laporan ke CSV
- Statistik koperasi lengkap
- Grafik performa bulanan
- Summary reports otomatis

### 🔐 Security & Validasi
- Rate limiting untuk registrasi
- Input sanitization dan validation
- CSRF protection
- Role-based access control
- SQL injection prevention

## 🛠️ Teknologi

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **Animations**: AOS (Animate On Scroll)
- **Icons**: Font Awesome

## 📋 Prerequisites

- PHP 8.0+
- MySQL 8.0+
- Composer
- Node.js & NPM (untuk development assets)

## 🚀 Installation

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd kop_merahputih
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` file:
   ```env
   APP_NAME="Koperasi Merah Putih"
   APP_ENV=production
   APP_KEY=base64:your-app-key
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kop_merahputih
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

6. **Seed Database**
   ```bash
   php artisan db:seed --force
   ```

7. **Link Storage**
   ```bash
   php artisan storage:link
   ```

8. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

## 👤 Default Credentials

Setelah seeding, Anda dapat login dengan:

**Admin Account:**
- Email: `admin@kopmerahputih.com`
- Password: `password`

## 📁 Struktur Project

```
kop_merahputih/
├── app/
│   ├── Http/Controllers/          # Controllers
│   ├── Http/Requests/           # Form Requests
│   ├── Http/Middleware/         # Custom Middleware
│   ├── Models/                 # Eloquent Models
│   └── Exports/               # Export Classes
├── database/
│   ├── migrations/             # Database Migrations
│   └── seeders/              # Database Seeders
├── resources/
│   ├── views/                 # Blade Templates
│   └── js/                   # JavaScript Files
├── routes/                   # Route Definitions
└── storage/                  # File Storage
```

## 🔧 Configuration

### Environment Variables
- `APP_ENV`: Environment (local/production)
- `APP_DEBUG`: Debug mode (false untuk production)
- `DB_*`: Database configuration
- `MAIL_*`: Email configuration

### Security Settings
- Rate limiting otomatis untuk registrasi (3 attempts per hour)
- CSRF token validation
- Input sanitization untuk semua form
- Role-based permissions

## 📊 Fitur Export

System menyediakan berbagai format export:

1. **Export Anggota** (`/admin/export/members`)
   - Filter berdasarkan status
   - Filter berdasarkan sektor usaha
   - Format CSV

2. **Export Laporan Keuangan** (`/admin/export/financial`)
   - Filter berdasarkan tanggal
   - Filter berdasarkan tipe (simpanan/pinjaman)
   - Format CSV

3. **Export Ringkasan** (`/admin/export/summary`)
   - Statistik lengkap koperasi
   - Data per sektor usaha
   - Transaksi bulanan
   - Format CSV

## 🔄 Maintenance

### Backup Database
```bash
php artisan backup:run --only-db
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Queue Processing
```bash
php artisan queue:work
```

## 🐛 Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check file permissions di storage/
   - Verify database connection
   - Check .env configuration

2. **Migration Errors**
   - Ensure database exists
   - Check database credentials
   - Run `php artisan migrate:fresh`

3. **Permission Denied**
   - Check user roles and permissions
   - Run `php artisan db:seed --class=PermissionSeeder`

4. **Asset Issues**
   - Run `npm install` dan `npm run build`
   - Clear cache dengan `php artisan optimize:clear`

## 📝 Development

### Running Locally
```bash
php artisan serve
```

### Asset Compilation
```bash
npm run dev          # Development
npm run build        # Production
```

### Testing
```bash
php artisan test
```

## 🚀 Deployment

### Production Deployment Checklist

1. **Environment Setup**
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Configure production database
   - Set proper `APP_URL`

2. **Security**
   - Generate new `APP_KEY`
   - Configure HTTPS
   - Set up firewall rules
   - Enable rate limiting

3. **Performance**
   - Enable OPcache
   - Configure Redis cache
   - Set up CDN untuk assets
   - Enable Gzip compression

4. **Database**
   - Run migrations dengan `--force`
   - Seed initial data
   - Set up backup schedule

5. **Final Steps**
   - Clear semua cache
   - Optimize application
   - Test semua functionality
   - Set up monitoring

## 📞 Support

Untuk bantuan teknis:
- Email: support@kopmerahputih.desa.id
- Documentation: [Wiki Link]
- Issues: [GitHub Issues]

## 📄 License

Project ini dilisensikan under MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## 🤝 Kontribusi

1. Fork project
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📈 Roadmap

- [ ] Mobile app development
- [ ] SMS notifications
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] API documentation
- [ ] Integration dengan payment gateways

---

**Koperasi Merah Putih Desa** - Membangun ekonomi desa yang kuat dan berkelanjutan.
