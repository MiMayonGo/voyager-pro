<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'VoyagePro') }} — Discover Your Next Adventure</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  @vite(['resources/css/landing.css'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" x-data="{ open: false }">
  <div class="nav-inner">
    <a href="#" class="nav-logo">Voyage<span>Pro</span></a>
    <ul class="nav-links">
      <li><a href="#">Home</a></li>
      <li><a href="#packages">Packages</a></li>
      <li><a href="#how">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
    <div class="nav-actions">
      @auth
        <a href="{{ url('/dashboard') }}" class="btn-outline">Dashboard</a>
      @else
        <a href="{{ route('login') }}" class="btn-outline">Login</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn-primary">Register</a>
        @endif
      @endauth
    </div>
    <button class="burger" @click="open = !open" aria-label="Toggle menu">
      <span></span><span></span><span></span>
    </button>
  </div>
  <div class="mobile-menu" :class="{ open: open }">
    <a href="#">Home</a>
    <a href="#packages">Packages</a>
    <a href="#how">About</a>
    <a href="#contact">Contact</a>
    @auth
      <a href="{{ url('/dashboard') }}" style="color:var(--primary)">Dashboard</a>
    @else
      <a href="{{ route('login') }}" style="color:var(--primary)">Login</a>
      @if (Route::has('register'))
        <a href="{{ route('register') }}" style="color:var(--primary);font-weight:700">Register →</a>
      @endif
    @endauth
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-inner">
    <div class="hero-tag">
      <span class="hero-tag-dot"></span>
      Trusted by adventurers worldwide
    </div>
    <h1>Discover Your <span>Next Adventure</span></h1>
    <p class="hero-sub">
      Curated tour packages across the Philippines' most breathtaking destinations.
      Every detail handled, every moment unforgettable.
    </p>
    <div class="hero-stats">
      <div class="hero-stat">
        <div class="hero-stat-num">{{ $packages->count() }}+</div>
        <div class="hero-stat-label">Top Packages</div>
      </div>
      <div class="hero-stat">
        <div class="hero-stat-num">4.9★</div>
        <div class="hero-stat-label">Average Rating</div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURED PACKAGES -->
<section class="section packages-bg" id="packages" x-data>
  <div class="section-inner">
    <div class="section-header fade-up">
      <div>
        <span class="section-tag">✦ Featured</span>
        <h2 class="section-title">Top Rated <span>Packages</span></h2>
      </div>
      @auth
        <a href="{{ route('packages.index') }}" class="view-all">View All Packages →</a>
      @else
        <a href="{{ route('login') }}" class="view-all">View All Packages →</a>
      @endauth
    </div>

    @if($packages->isEmpty())
      <p style="color:var(--text-muted);text-align:center;padding:3rem 0">
        No packages available yet. Check back soon!
      </p>
    @else
      <div class="pkg-grid pkg-grid-5 fade-up">
        @foreach($packages as $index => $pkg)
          @php
            $rating      = $pkg['rating'] ?? 0;
            $reviewCount = $pkg['reviewCount'];
            $slots       = $pkg['slots'];
          @endphp
          <div class="pkg-card" @click="$store.tour.open({{ $index }})">
            <div class="pkg-img-wrap">
              <img src="{{ $pkg['image'] }}" alt="{{ $pkg['title'] }}" loading="lazy">
              @if($index === 0)
                <span class="pkg-badge">Top Rated</span>
              @elseif($reviewCount === 0)
                <span class="pkg-badge new">New</span>
              @endif
            </div>
            <div class="pkg-body">
              <div class="pkg-rating">
                <div class="stars">
                  @for($s = 1; $s <= 5; $s++)
                    <span class="{{ $s <= round($rating) ? 'star-filled' : 'star-empty' }}">★</span>
                  @endfor
                </div>
                <span class="rating-count">
                  {{ $rating > 0 ? $rating : 'No' }} ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})
                </span>
              </div>
              <h3 class="pkg-title">{{ $pkg['title'] }}</h3>
              <div class="pkg-meta">
                <span class="pill pill-teal">🕐 {{ $pkg['duration'] }}</span>
                @foreach(array_slice($pkg['categories'], 0, 2) as $cat)
                  <span class="pill pill-sky">{{ $cat }}</span>
                @endforeach
              </div>
              <div class="pkg-footer">
                <div>
                  <div class="pkg-price">{{ $pkg['price'] }}</div>
                  @if($slots > 0 && $slots <= 5)
                    <div class="slots-warning">{{ $slots }} slots left!</div>
                  @else
                    <div class="pkg-price-label">per person</div>
                  @endif
                </div>
                <button class="btn-sm" @click.stop="$store.tour.open({{ $index }})">View →</button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section how-bg" id="how">
  <div class="section-inner">
    <div style="text-align:center;margin-bottom:3rem" class="fade-up">
      <span class="section-tag">✦ Simple Process</span>
      <h2 class="section-title">How It <span>Works</span></h2>
      <p style="color:var(--text-muted);margin-top:0.5rem;font-size:0.95rem">Start your journey in three easy steps</p>
    </div>
    <div class="how-grid fade-up">
      <div class="how-step">
        <div class="how-icon-wrap"><span class="how-num">①</span></div>
        <h3 class="how-title">Browse &amp; Choose</h3>
        <p class="how-desc">Explore our curated packages. Filter by destination and budget to find your perfect trip.</p>
      </div>
      <div class="how-step">
        <div class="how-icon-wrap"><span class="how-num">②</span></div>
        <h3 class="how-title">Book &amp; Pay Securely</h3>
        <p class="how-desc">Reserve your slot with instant confirmation and invoice delivered to your inbox.</p>
      </div>
      <div class="how-step">
        <div class="how-icon-wrap"><span class="how-num">③</span></div>
        <h3 class="how-title">Travel &amp; Enjoy</h3>
        <p class="how-desc">Show up and let your expert tour manager handle everything. Your only job is to make memories.</p>
      </div>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter-bg" id="contact">
  <div class="newsletter-inner fade-up">
    <h2>Get Exclusive Travel Deals</h2>
    <p>Be the first to know about new packages, flash discounts, and seasonal promotions.</p>
    <form class="newsletter-form" onsubmit="return false;">
      <input type="email" placeholder="Enter your email address">
      <button type="submit">Subscribe</button>
    </form>
    <div class="newsletter-trust">
      <span class="trust-item">✓ No spam, ever</span>
      <span class="trust-item">✓ Unsubscribe anytime</span>
      <span class="trust-item">✓ Weekly deals only</span>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <a href="#" class="logo">Voyage<span>Pro</span></a>
      <p class="footer-desc">Curated tour experiences for the modern Filipino traveller. Every journey, expertly crafted.</p>
      <div class="social-links">
        <a href="#" class="social-btn" aria-label="Facebook">f</a>
        <a href="#" class="social-btn" aria-label="LinkedIn">in</a>
        <a href="#" class="social-btn" aria-label="Instagram">ig</a>
        <a href="#" class="social-btn" aria-label="Twitter">tw</a>
      </div>
    </div>
    <div class="footer-col">
      <h4>Packages</h4>
      <ul>
        <li><a href="#">Beach &amp; Islands</a></li>
        <li><a href="#">Mountains &amp; Trekking</a></li>
        <li><a href="#">Cultural Heritage</a></li>
        <li><a href="#">Adventure Tours</a></li>
        <li><a href="#">Eco Tourism</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Company</h4>
      <ul>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Our Team</a></li>
        <li><a href="#">Tour Managers</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Press</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Support</h4>
      <ul>
        <li><a href="#">Help Center</a></li>
        <li><a href="#">Booking Policy</a></li>
        <li><a href="#">Cancellations</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p class="footer-copy">© {{ date('Y') }} VoyagePro. All rights reserved.</p>
    <p class="footer-copy">Built with ❤ for Filipino adventurers</p>
  </div>
</footer>

<!-- ════ PACKAGE DETAIL MODAL ════ -->
<div x-data
     x-show="$store.tour.isOpen"
     @keydown.escape.window="$store.tour.close()"
     @click.self="$store.tour.close()"
     class="modal-backdrop"
     style="display:none"
     role="dialog"
     aria-modal="true">

  <div class="modal-panel">
    <button class="modal-close" @click="$store.tour.close()" aria-label="Close">&times;</button>

    <!-- Image -->
    <div>
      <img class="modal-img"
           :src="$store.tour.pkg?.image"
           :alt="$store.tour.pkg?.title">
    </div>

    <!-- Details -->
    <div class="modal-details">
      <div class="modal-details-scroll">
        <h2 class="modal-title" x-text="$store.tour.pkg?.title"></h2>

        <div class="pkg-rating" style="margin-bottom:0.7rem">
          <div class="stars">
            <template x-for="s in 5" :key="s">
              <span :class="s <= Math.round($store.tour.pkg?.rating ?? 0) ? 'star-filled' : 'star-empty'">★</span>
            </template>
          </div>
          <span class="rating-count"
                x-text="($store.tour.pkg?.rating ?? 'No rating') + ' · ' + $store.tour.pkg?.reviewCount + ' reviews'"></span>
        </div>

        <div class="pkg-meta" style="margin-bottom:1rem">
          <span class="pill pill-teal" x-text="'🕐 ' + $store.tour.pkg?.duration"></span>
          <template x-for="cat in ($store.tour.pkg?.categories ?? [])" :key="cat">
            <span class="pill pill-sky" x-text="cat"></span>
          </template>
        </div>

        <div class="modal-price-row">
          <span class="modal-price" x-text="$store.tour.pkg?.price"></span>
          <span class="pkg-price-label">/ person</span>
          <template x-if="$store.tour.pkg?.slots > 0 && $store.tour.pkg?.slots <= 5">
            <span class="slots-warning" x-text="$store.tour.pkg?.slots + ' slots left!'"></span>
          </template>
        </div>

        <div class="modal-tabs">
          <button class="modal-tab"
                  :class="{ active: $store.tour.tab === 'itinerary' }"
                  @click="$store.tour.tab = 'itinerary'">📅 Itinerary</button>
          <button class="modal-tab"
                  :class="{ active: $store.tour.tab === 'overview' }"
                  @click="$store.tour.tab = 'overview'">📋 Overview</button>
        </div>

        <!-- Itinerary Tab -->
        <div x-show="$store.tour.tab === 'itinerary'" class="modal-tab-panel">
          <template x-if="($store.tour.pkg?.itinerary ?? []).length === 0">
            <p style="color:var(--text-muted);font-size:0.85rem">No itinerary added yet.</p>
          </template>
          <template x-for="day in ($store.tour.pkg?.itinerary ?? [])" :key="day.day">
            <div class="itinerary-item">
              <div class="day-num" x-text="'Day ' + day.day"></div>
              <div>
                <div class="day-title" x-text="day.title"></div>
                <div class="day-desc"  x-text="day.desc"></div>
              </div>
            </div>
          </template>
        </div>

        <!-- Overview Tab -->
        <div x-show="$store.tour.tab === 'overview'" class="modal-tab-panel">
          <ul class="overview-list">
            <li><strong>Duration</strong> <span x-text="$store.tour.pkg?.duration"></span></li>
            <li>
              <strong>Available Slots</strong>
              <span x-text="$store.tour.pkg?.slots > 0 ? $store.tour.pkg?.slots + ' remaining' : 'Sold out'"></span>
            </li>
            <li>
              <strong>Categories</strong>
              <span x-text="($store.tour.pkg?.categories ?? []).join(', ') || '—'"></span>
            </li>
            <li><strong>Price</strong> <span x-text="$store.tour.pkg?.price + ' per person'"></span></li>
          </ul>
        </div>

      </div>

      <div class="modal-footer">
        @auth
          <a :href="'/packages/' + $store.tour.pkg?.slug" class="btn-book-now" style="text-align:center;display:block;text-decoration:none">
            View Full Details &amp; Book →
          </a>
        @else
          <a href="{{ route('login') }}" class="btn-book-now" style="text-align:center;display:block;text-decoration:none">
            Login to Book →
          </a>
        @endauth
      </div>
    </div>
  </div>
</div>

<script>
  // Alpine store — populated with server-rendered package data
  document.addEventListener('alpine:init', () => {
    Alpine.store('tour', {
      isOpen: false,
      tab: 'itinerary',
      pkg: null,
      packages: @json($packages),
      open(index) {
        this.pkg    = this.packages[index] ?? null;
        this.tab    = 'itinerary';
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
      },
      close() {
        this.isOpen = false;
        document.body.style.overflow = '';
      },
    });
  });

  // Scroll reveal
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
    });
  }, { threshold: 0.1 });
  document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

</body>
</html>