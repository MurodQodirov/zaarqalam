# Zarqalam Website

Korporativ website: brending, qadoq, sovg‘a naborlari va poligrafiya xizmatlari katalogi.

## Texnologiyalar

- PHP 8+
- Vanilla CSS
- Vanilla JavaScript
- JSON ma’lumotlar ombori (database yo‘q)

## Ishga tushirish

### PHP built-in server (tavsiya etiladi)

```bash
cd zarqalam
php -S localhost:8000 router.php
```

Brauzerda oching: [http://localhost:8000](http://localhost:8000)

### Apache

Loyiha ildizini document root qilib qo‘ying. `mod_rewrite` yoqilgan bo‘lishi kerak (`.htaccess` ishlashi uchun).

## Sahifalar

| URL | Tavsif |
|---|---|
| `/` | Bosh sahifa |
| `/services` | Xizmatlar |
| `/services/branding` | Logotip va brending |
| `/services/packaging` | Qadoq |
| `/services/gifts` | Sovg‘a naborlari |
| `/services/catalogs` | Katalog va taqdimotlar |
| `/portfolio` | Portfolio |
| `/portfolio/:slug` | Loyiha sahifasi |
| `/about` | Biz haqimizda |
| `/contact` | Aloqa / buyurtma |
| `/privacy-policy` | Maxfiylik siyosati |

## Ma’lumotlar (JSON)

Barcha kontent `data/` papkasida:

- `site.json` — sayt matnlari, aloqa, afzalliklar, jarayon
- `services.json` — xizmatlar
- `portfolio.json` — portfolio loyihalari
- `orders.json` — kelgan buyurtmalar (forma orqali yoziladi)

## Buyurtma formasi

`/contact` formasi `api/contact.php` ga yuboriladi:

1. Validatsiya qilinadi
2. Ixtiyoriy fayl `public/uploads/` ga saqlanadi
3. Buyurtma `data/orders.json` ga yoziladi
4. Faqat muvaffaqiyatli saqlangandan keyin foydalanuvchiga success xabar ko‘rsatiladi

Telefon, Telegram va Instagram ma’lumotlarini `data/site.json` dan o‘zgartiring.

## Dizayn

- Fon: `#FFFFFF`
- Matn: `#171717`
- Ikkinchi matn: `#737373`
- Muqobil fon: `#F5F5F3`
- Oltin aksent: `#B89A52`
- Chegara: `#E7E7E7`
- Shrift: Manrope

## SEO

- Har sahifa uchun title va meta description
- Open Graph teglari
- `sitemap.xml`
- `robots.txt`
- 404 sahifa
- Rasmlarda `alt` atributlari
- Lazy loading

## Papka tuzilmasi

```
zarqalam/
├── api/contact.php
├── data/*.json
├── includes/
├── pages/
├── public/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── uploads/
├── index.php
├── router.php
└── README.md
```

## Eslatma

Hozirgi portfolio va xizmat rasmlari SVG placeholder. Real loyiha rasmlarini `public/images/` ga qo‘yib, JSON dagi yo‘llarni yangilang.
