# 🎯 Implementation Guide - Responsive Features

## Daftar Isi
1. [Setup Awal](#setup-awal)
2. [Testing Responsif](#testing-responsif)
3. [Customization Guide](#customization-guide)
4. [Platform-Specific Tips](#platform-specific-tips)
5. [Performance Tuning](#performance-tuning)

---

## Setup Awal

### 1. Pastikan Semua File Sudah Terupdate

```bash
# Verify file baru sudah ada
resources/css/responsive.css       ✅ CREATED
resources/views/layouts/app.blade.php  ✅ UPDATED (CSS & JS)
```

### 2. Clear Cache & Recompile Assets

```bash
# Development environment
npm run dev

# Production environment
npm run build

# Jika menggunakan Artisan
php artisan view:clear
php artisan cache:clear
```

### 3. Verify META Tags

Pastikan di `<head>` ada:
```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

✅ Sudah ada di `app.blade.php`

### 4. Test Di Browser

Buka aplikasi dan press **F12** → **Ctrl+Shift+M** untuk device emulation.

---

## Testing Responsif

### 1. Device Testing Minimal (Wajib)

#### Mobile (320px - 480px)
```
- iPhone SE (375px × 812px)
- Galaxy S21 (360px × 800px)
- Pixel 6 (412px × 915px)

Test:
✓ Navbar tidak overcrowded
✓ Form inputs full width
✓ Cards stacked single column
✓ Images tidak too wide
✓ Footer readable
```

#### Tablet (768px - 1024px)
```
- iPad Air (768px × 1024px)
- Galaxy Tab (600px × 960px)

Test:
✓ Multi-column layout terlihat
✓ Form layout horizontal jika cocok
✓ Navigation bar expanded
✓ Sidebar positioning correct
```

#### Desktop (1200px+)
```
- Laptop (1366px × 768px)
- Desktop (1920px × 1080px)

Test:
✓ Full layout dengan sidebar
✓ Large imagery
✓ Multi-column grid 3+ columns
✓ Hover effects working
```

### 2. Orientation Testing

```javascript
// Test landscape orientation
Math.max(window.innerWidth, window.innerHeight)  // width
Math.min(window.innerWidth, window.innerHeight)  // height

// Landscape mode usually has less height
// So adjust hero section, card heights
```

### 3. Browser DevTools Emulation

```
Chrome DevTools:
1. F12 open DevTools
2. Ctrl+Shift+M toggle device toolbar
3. Select device dari dropdown
4. Test manual resizing
5. Check responsiveness slider
```

### 4. Real Device Testing

**Recommended:**
- Test pada actual smartphone (iOS & Android)
- Test pada actual tablet
- Test pada actual laptop

**Why:** Emulation ≠ Real Device
- Touch events berbeda
- Rendering berbeda
- Performance berbeda

### 5. Screenshot Testing

```bash
# Chrome Headless dapat gunakan untuk automation
# Untuk manual: F12 → DevTools → ... → Capture screenshot
```

---

## Customization Guide

### 1. Ubah Breakpoints

Jika ingin custom breakpoints, edit `resources/css/responsive.css`:

```css
/* Default breakpoints Bootstrap */
576px   - Small devices
768px   - Medium devices
992px   - Large devices
1200px  - Extra large
1400px  - XXL devices

/* Custom: Jika ingin tambah lebih banyak */
@media (min-width: 1600px) {
    /* Your custom styles */
}
```

### 2. Ubah Font Sizing

Edit di `app.blade.php` bagian CSS:

```css
/* Current clamp usage */
h1 { font-size: clamp(1.5rem, 5vw, 3rem); }

/* Adjust:
   - min: smallest size at very small screens
   - preferred: vw percentage (responsive scalar)
   - max: largest size at very large screens
*/

/* Example: More aggressive scaling */
h1 { font-size: clamp(1.2rem, 8vw, 4rem); }

/* Example: Less aggressive scaling */
h1 { font-size: clamp(1.8rem, 3vw, 2.5rem); }
```

### 3. Ubah Color Scheme

Edit CSS Variables di `app.blade.php`:

```css
:root {
    --imdb-yellow: #f5c518;        /* Primary color */
    --imdb-dark: #121212;          /* Background */
    --imdb-dark-3: #1f1f1f;        /* Card background */
    --imdb-text: #e0e0e0;          /* Text color */
    --imdb-text-muted: #999999;    /* Muted text */
    --imdb-link: #5799ef;          /* Link color */
}

/* Update contoh: untuk light theme */
:root {
    --imdb-yellow: #f5c518;
    --imdb-dark: #ffffff;
    --imdb-text: #333333;
    --imdb-text-muted: #666666;
    --imdb-link: #0066ff;
}
```

### 4. Ubah Spacing

Edit di class berikut:

```css
/* Card spacing */
.card { margin-bottom: 1rem; }      /* Ubah dari 1rem ke 1.5rem */

/* Row gap */
.row { gap: clamp(1rem, 2vw, 1.5rem); }  /* Ubah gap size */

/* Container padding */
main { padding: 1rem; }              /* Ubah padding */
```

### 5. Ubah Touch Target Size

Edit minimum button/input size:

```css
/* Default: 44px (Apple) / 48px (Google) */
button, a[role="button"], input[type="button"] {
    min-height: 44px;
    min-width: 44px;
}

/* Increase: untuk better accessibility */
button {
    min-height: 48px;
    min-width: 48px;
}
```

---

## Platform-Specific Tips

### iOS (iPhone/iPad)

#### Issues & Solutions:

**1. Input Font Size Zoom**
```css
/* Problem: 14px-15px font causes zoom on iOS */
input { font-size: 16px !important; }
```

**2. Safe Area Insets**
```css
/* Handle notch & home indicator */
body {
    padding-top: env(safe-area-inset-top);
    padding-bottom: env(safe-area-inset-bottom);
}
```

**3. Double-Tap Zoom on Links**
```css
/* Disable double-tap zoom */
a, button {
    touch-action: manipulation;
}
```

**4. Sticky Position Behavior**
```css
/* position: sticky berbeda di iOS */
.navbar { position: sticky; top: 0; z-index: 1000; }
```

**5. Smooth Scrolling**
```css
/* iOS smooth scroll */
html { -webkit-overflow-scrolling: touch; }
```

### Android (Smartphone)

#### Issues & Solutions:

**1. Chrome Mobile Behavior**
```javascript
// Android font scaling
document.doument.body.style.fontSize = '16px';
```

**2. Zoom Prevention**
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0, 
       maximum-scale=1.0, user-scalable=no">
```

**3. Touch Feedback**
```css
/* Better visual feedback */
button:active {
    background-color: darkened;
    transform: scale(0.98);
}
```

### Tablet (iPad/Galaxy Tab)

#### Issues & Solutions:

**1. Wide Layout Optimization**
```css
/* Tablet usually 768px-1024px */
@media (min-width: 768px) and (max-width: 1024px) {
    .col-md-4 { width: calc(33.333% - 1rem); }
    .col-md-6 { width: calc(50% - 1rem); }
}
```

**2. Sidebar Positioning**
```css
@media (min-width: 768px) {
    .sidebar { position: sticky; top: 70px; }
}
```

**3. Multi-touch Gesture**
```javascript
// Handle pinch-zoom
document.addEventListener('gesturestart', function(e) {
    e.preventDefault();
});
```

### Desktop/Laptop

#### Optimizations:

**1. Hover Effects**
```css
@media (hover: hover) {
    .card:hover { transform: translateY(-5px); }
}
```

**2. Cursor Feedback**
```css
button { cursor: pointer; }
a { cursor: pointer; }
.disabled { cursor: not-allowed; }
```

**3. Keyboard Navigation**
```javascript
// Tab through elements
// Focus states visible
// Enter to activate buttons
```

---

## Performance Tuning

### 1. Optimize Images untuk Mobile

```html
<!-- Use multiple image sizes -->
<picture>
    <source media="(max-width: 576px)" srcset="img-small.jpg">
    <source media="(min-width: 577px)" srcset="img-large.jpg">
    <img src="img-large.jpg" alt="Description">
</picture>
```

### 2. Lazy Load untuk Efficiency

```javascript
// Sudah built-in di app.blade.php
if ('IntersectionObserver' in window) {
    // Images load on demand
}
```

### 3. CSS Optimization

```css
/* Don't use complex selectors */
.card-film .card_body .card-title span { } /* ❌ Bad */

/* Use simpler selectors */
.card-title { }  /* ✅ Good */
```

### 4. JavaScript Performance

```javascript
// Avoid repetitive DOM queries
const buttons = document.querySelectorAll('.btn');  /* Once */
buttons.forEach(btn => { /* Use cached */});

// NOT:
for (let i = 0; i < 1000; i++) {
    document.querySelectorAll('.btn');  /* Don't repeat */
}
```

### 5. Network Performance

```bash
# Serve minified CSS/JS
npm run build  # Production build

# Enable gzip compression pada server
# Check: Network tab → Content-Encoding: gzip
```

### 6. Cache Optimization

```bash
# Laravel caching
php artisan config:cache
php artisan view:cache
php artisan route:cache
```

---

## Advanced Customization

### 1. Dark/Light Mode Toggle

```javascript
// Add di app.blade.php scripts
const toggleTheme = () => {
    const html = document.documentElement;
    html.style.colorScheme = 
        html.style.colorScheme === 'dark' ? 'light' : 'dark';
};
```

### 2. Custom Breakpoints CSS

```css
/* If you want more granular control */
@media (max-width: 640px) {}        /* Extra small */
@media (max-width: 768px) {}        /* Small */
@media (max-width: 1024px) {}       /* Medium */
@media (max-width: 1280px) {}       /* Large */
@media (max-width: 1536px) {}       /* Extra large */
```

### 3. Aspect Ratio Container

```css
/* 16:9 aspect ratio */
.aspect-ratio-16-9 {
    position: relative;
    padding-bottom: 56.25%;
}

.aspect-ratio-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
```

**Usage:**
```html
<div class="aspect-ratio-16-9">
    <img class="aspect-ratio-content" src="image.jpg">
</div>
```

### 4. Container Queries (Advanced)

```css
/* CSS Container Queries (Chrome 105+) */
@container (min-width: 400px) {
    .card { grid-template-columns: 1fr 1fr; }
}
```

---

## Troubleshooting Common Issues

### Issue 1: "Layout looks ok tapi text terlalu kecil"
```css
/* Check clamp values */
/* First value (min) terlalu kecil? */
p { font-size: clamp(0.7rem, 1.5vw, 1rem); }  /* ❌ Too small */
p { font-size: clamp(0.85rem, 1.5vw, 1rem); } /* ✅ Better */
```

### Issue 2: "Responsive grid tidak work"
```css
/* Check if row/col still using Bootstrap defaults */
.row { display: grid; }  /* Must use grid */
/* If using Bootstrap col-*, that overrides */
```

### Issue 3: "Mobile navigation tidak responsive"
```javascript
// Check Bootstrap JS loaded
<script src="bootstrap.bundle.min.js"></script>
// AND
<button class="navbar-toggler" data-bs-toggle="collapse">
```

### Issue 4: "Hover effects on touch device"
```css
/* Use proper media query */
@media (hover: none) and (pointer: coarse) {
    .card:hover { /* Won't trigger on touch */ }
    .card:active { /* Use active instead */ }
}
```

### Issue 5: "Form too wide on mobile"
```css
/* Ensure width: 100% */
.form-control { width: 100%; max-width: 100%; }
/* Check if container limiting width */
.container { width: 100%; padding: 0 1rem; }
```

---

## Quick Reference Commands

```bash
# Development
npm run dev              # Watch mode development
php artisan serve       # Start dev server

# Production
npm run build           # Compile assets for production
php artisan optimize    # Optimize application

# Testing
php artisan test        # Run tests
npm run lint            # Check code quality

# Maintenance
php artisan cache:clear # Clear all caches
php artisan view:clear  # Clear view cache
php artisan route:cache # Cache routes (prod)
```

---

## Checklist untuk Production

- [ ] Test pada 5+ actual devices
- [ ] Lighthouse score > 80
- [ ] PageSpeed Insights green
- [ ] No console errors
- [ ] Images optimized
- [ ] CSS/JS minified
- [ ] Cache headers configured
- [ ] Service Worker tested
- [ ] Accessibility audit passed
- [ ] SEO tags configured

---

## Contact & Support

Jika ada issues dengan responsive implementation:

1. Check RESPONSIVE_UPDATE.md
2. Check browser console untuk errors
3. Test di incognito/private mode
4. Clear cache dan reload
5. Try different device/browser

---

**Version:** 2.0
**Last Updated:** March 30, 2026
