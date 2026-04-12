# 📚 Quick Reference - CSS Classes & JS Functions

## CSS Classes Available

### Layout Classes

#### Display Utilities
```css
.d-none                 /* display: none */
.d-block                /* display: block */
.d-inline               /* display: inline */
.d-inline-block         /* display: inline-block */
.d-flex                 /* display: flex */
.d-grid                 /* display: grid */

/* Responsive variants */
.d-sm-none              /* Hide on small+ devices */
.d-md-block             /* Show only on medium+ devices */
.d-none-sm              /* Hide on mobile, show on tablet+ */
.d-none-md              /* Show on mobile, hide on desktop+ */
```

#### Spacing Utilities
```css
/* Margin */
.m-1, .m-2, .m-3, .m-4, .m-5       /* all sides */
.mt-*, .mb-*, .me-*, .ms-*          /* individual sides */
.mx-auto, .my-auto                  /* auto margins */

/* Padding */
.p-1, .p-2, .p-3, .p-4, .p-5       /* all sides */
.pt-*, .pb-*, .pe-*, .ps-*          /* individual sides */

/* Responsive spacing */
.px-sm-1                            /* Mobile padding */
.py-md-2                            /* Tablet padding */
```

#### Flex Utilities
```css
.flex-column            /* flex-direction: column */
.flex-row               /* flex-direction: row */
.justify-content-between
.justify-content-center
.align-items-center
.gap-1, .gap-2, .gap-3              /* gap between items */
```

### Color & Theme Classes

#### Text Colors
```css
.text-white             /* White text */
.text-muted             /* Muted gray text */
.text-danger            /* Red text */
.text-success           /* Green text */
.text-warning           /* Yellow text */
.text-info              /* Blue text */
```

#### Background Colors
```css
.bg-dark                /* Dark background */
.bg-light               /* Light background */
.bg-danger, .bg-success, .bg-warning, .bg-info
```

### Component Classes

#### Cards
```html
<div class="card">
    <img class="card-img-top" src="...">
    <div class="card-body">
        <h5 class="card-title">Title</h5>
        <p class="card-text">Content</p>
    </div>
</div>
```

#### Film Cards (Custom)
```css
.card-film              /* Card styling untuk film */
.card-film:hover        /* Hover effect */
.badge-genre            /* Genre badge */
```

#### Forms
```css
.form-control           /* Input field */
.form-select            /* Select dropdown */
.form-label             /* Label */
.form-check             /* Checkbox/Radio */
.form-range             /* Range slider */
```

#### Buttons
```css
.btn                    /* Base button */
.btn-imdb               /* Yellow CineRate button */
.btn-outline-imdb       /* Outlined button */
.btn-sm, .btn-lg        /* Size variants */
.btn-danger, .btn-success, etc.
```

#### Navigation
```css
.navbar                 /* Navigation bar */
.navbar-imdb            /* Styled navbar */
.navbar-expand-lg       /* Expandible navbar */
.nav-link               /* Navigation item */
.navbar-toggler         /* Mobile menu button */
```

#### Alerts
```css
.alert                  /* Alert container */
.alert-success          /* Success alert */
.alert-danger           /* Error alert */
.alert-warning          /* Warning alert */
.alert-info             /* Info alert */
.alert-auto-hide        /* Auto-disappear after 5s */
```

#### Badges & Badges
```css
.badge                  /* Badge component */
.badge-primary, .badge-success, etc.
.imdb-rating-badge      /* Custom rating badge */
```

#### Tables
```css
.table                  /* Table */
.table-hover            /* Hover effect */
.table-responsive       /* Responsive table */
.table-responsive-custom
.poster-thumbnail       /* Small image in table */
```

#### Pagination
```css
.pagination             /* Pagination container */
.page-item              /* Page item */
.page-item.active       /* Active page */
.page-link              /* Page link */
```

### Rating & Review Classes

```css
.star-rating            /* Star rating display */
.star-rating-large      /* Large stars */
.star-interactive       /* Interactive stars */
.star-interactive.active /* Active star */
.imdb-rating-badge      /* Rating badge */
```

### Responsive Image Classes

```css
.img-responsive         /* Responsive image */
.img-fluid              /* Bootstrap fluid image */
.detail-poster          /* Detail page poster */
.no-poster              /* Placeholder for missing poster */
.poster-thumbnail       /* Small poster in table */
```

### Dashboard Classes

```css
.stat-card              /* Statistic card */
.stat-number            /* Large number */
.stat-label             /* Label text */
.bg-film                /* Film stat color */
.bg-user                /* User stat color */
.bg-rating              /* Rating stat color */
.bg-ulasan              /* Review stat color */
```

### Section Styling

```css
.section-heading        /* Section title */
.hero-section           /* Hero/banner section */
.rating-box             /* Rating display box */
.form-rating            /* Rating form */
.ulasan-item            /* Review item */
.login-box              /* Login form container */
```

### Accessibility Classes

```css
.sr-only                /* Screen reader only */
.btn:focus-visible      /* Keyboard focus */
using-keyboard          /* Body class when using keyboard */
```

---

## JavaScript Functions

### Rating Functions

```javascript
/**
 * Pilih rating bintang (1-10)
 * @param {number} nilai - Rating value (1-10)
 */
pilihRating(nilai);

// Usage:
pilihRating(5);  // Set 5 stars
```

### Image Functions

```javascript
/**
 * Preview poster sebelum upload
 * @param {Event} event - File input event
 */
previewPoster(event);

// Usage:
<input type="file" onchange="previewPoster(event)">
```

### Utility Functions

```javascript
/**
 * Debounce function untuk optimize events
 * @param {function} func - Function to debounce
 * @param {number} wait - Wait time in ms
 */
debounce(func, wait);

// Usage:
window.addEventListener('resize', 
    debounce(handleResize, 50)
);
```

### Initialization Functions

```javascript
/**
 * Initialize responsive features
 * Auto-called on DOMContentLoaded
 */
initAutoHideAlert();        // Auto-hide alerts
initLazyLoading();          // Lazy load images
initFormValidation();       // Form validation
initResponsiveTable();      // Responsive tables
```

### Event Handlers

```javascript
/**
 * Handle viewport changes
 * Auto-called on resize/orientationchange
 */
handleViewportChange();

// Auto-triggered on:
// - window resize
// - orientation change
// - viewport change
```

---

## HTML Template Examples

### Responsive Card

```html
<div class="card card-film">
    <img class="card-img-top" src="poster.jpg" alt="Film">
    <div class="card-body">
        <h5 class="card-title">Film Title</h5>
        <p class="text-muted">Genre / Year</p>
        <div class="star-rating">
            ★★★★☆
        </div>
    </div>
</div>
```

### Responsive Form

```html
<form class="needs-validation">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" required>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Phone</label>
            <input type="tel" class="form-control">
        </div>
    </div>
    
    <button type="submit" class="btn btn-imdb">Submit</button>
</form>
```

### Responsive Navigation

```html
<nav class="navbar navbar-expand-lg navbar-imdb sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">CineRate</a>
        
        <button class="navbar-toggler" type="button" 
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Browse</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
```

### Rating Form

```html
<div class="form-rating">
    <h4>Beri Rating</h4>
    
    <form method="POST" action="{{ route('tmdb.rating', $id) }}">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Rating</label>
            <div class="rating-stars">
                <span class="star-interactive" onclick="pilihRating(1)">
                    <i class="bi bi-star"></i>
                </span>
                <span class="star-interactive" onclick="pilihRating(2)">
                    <i class="bi bi-star"></i>
                </span>
                <!-- ... 3-5 stars ... -->
            </div>
            <input type="hidden" id="input-rating" name="rating">
        </div>
        
        <button type="submit" class="btn btn-imdb">Kirim Rating</button>
    </form>
</div>
```

### Responsive Grid

```html
<div class="row">
    <div class="col-12 col-md-6 col-lg-4">
        <!-- Will be 1 col on mobile, 2 on tablet, 3 on desktop -->
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <!-- ... -->
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <!-- ... -->
    </div>
</div>
```

### Alert with Auto-Hide

```html
<div class="alert alert-success alert-auto-hide">
    <i class="bi bi-check-circle"></i>
    Sukses! Pesan akan hilang dalam 5 detik.
</div>
```

---

## Common Patterns

### Mobile Menu (Hamburger)

```html
<!-- Navbar with toggle -->
<nav class="navbar navbar-expand-lg">
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
        <!-- Menu items -->
    </div>
</nav>
```

### Flexible Image Container

```html
<!-- Images scale responsively -->
<div class="aspect-ratio-16-9">
    <img class="aspect-ratio-content" src="image.jpg" alt="">
</div>
```

### Touch-Friendly Buttons

```html
<!-- Buttons are minimum 44x44px -->
<button class="btn btn-imdb">
    <i class="bi bi-plus"></i> Add
</button>

<!-- Or with custom sizing -->
<button class="btn btn-imdb" style="min-height: 48px; min-width: 48px;">
    <i class="bi bi-menu"></i>
</button>
```

### Responsive Table

```html
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

### Lazy Loading Image

```html
<!-- Use data-src for lazy loading -->
<img data-src="large-image.jpg" alt="">
<!-- Will be loaded when visible on screen -->
```

---

## CSS Variables (Custom Properties)

```css
/* Access dengan var() */
color: var(--imdb-yellow);      /* #f5c518 */
color: var(--imdb-dark);         /* #121212 */
color: var(--imdb-text);         /* #e0e0e0 */
color: var(--imdb-text-muted);   /* #999999 */
color: var(--imdb-link);         /* #5799ef */
```

---

## Media Queries Quick Reference

```css
/* Mobile first approach */
/* No query: Mobile (< 576px) */

/* Tablet */
@media (min-width: 768px) { }

/* Laptop */
@media (min-width: 992px) { }

/* Desktop */
@media (min-width: 1200px) { }

/* Large Desktop */
@media (min-width: 1400px) { }

/* Landscape orientation */
@media (orientation: landscape) { }

/* Portrait orientation */
@media (orientation: portrait) { }

/* Touch-capable devices */
@media (hover: none) and (pointer: coarse) { }

/* Hover-capable devices */
@media (hover: hover) { }

/* High DPI / Retina */
@media (-webkit-min-device-pixel-ratio: 2) { }
```

---

## Performance Tips

### CSS
```css
/* ✅ Good: Simple selectors */
.card { }

/* ❌ Bad: Complex selectors */
body > div.container > div.row > div.col > .card-film { }

/* Good: Use CSS Grid/Flex untuk responsive */
grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));

/* Instead of multiple media queries */
```

### JavaScript
```javascript
/* ✅ Good: Cache DOM queries */
const buttons = document.querySelectorAll('.btn');
buttons.forEach(btn => { /* use 'buttons' */ });

/* ❌ Bad: Repeat queries */
for (let i = 0; i < 100; i++) {
    document.querySelectorAll('.btn'); /* Don't repeat! */
}

/* ✅ Good: Debounce scroll/resize */
window.addEventListener('scroll', debounce(handler, 50));

/* ❌ Bad: Fire for every pixel */
window.addEventListener('scroll', handler);
```

---

## Testing Checklist

When modifying CSS/JS:

- [ ] Test on mobile (320px)
- [ ] Test on tablet (768px)
- [ ] Test on desktop (1920px)
- [ ] Test on landscape
- [ ] Test with keyboard only
- [ ] Test with screen reader
- [ ] Check console for errors
- [ ] Verify touch events work
- [ ] Check performance (Lighthouse)
- [ ] Test on slow 4G network

---

**Last Updated:** March 30, 2026
**Version:** 2.0
