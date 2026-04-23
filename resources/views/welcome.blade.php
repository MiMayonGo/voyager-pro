<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name', 'VoyagePro') }} — Discover Your Next Adventure</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

  <!-- Stylesheet via Vite -->
  @vite(['resources/css/landing.css'])

  <!-- Alpine.js -->
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
        <span></span>
        <span></span>
        <span></span>
      </button>

    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" :class="{ open: open }">
      <a href="#">Home</a>
      <a href="#packages">Packages</a>
      <a href="#how">About</a>
      <a href="#contact">Contact</a>
      @auth
        <a href="{{ url('/dashboard') }}" style="color: var(--primary);">Dashboard</a>
      @else
        <a href="{{ route('login') }}" style="color: var(--primary);">Login</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 700;">Register →</a>
        @endif
      @endauth
    </div>
  </nav>


  <!-- HERO -->
  <section class="hero">
    <div class="hero-inner">

      <div class="hero-tag">
        <span class="hero-tag-dot"></span>
        Trusted by 12,000+ adventurers
      </div>

      <h1>Discover Your <span>Next Adventure</span></h1>

      <p class="hero-sub">
        Curated tour packages across the Philippines' most breathtaking
        destinations. Every detail handled, every moment unforgettable.
      </p>

      <!-- Search Bar -->
      <div class="search-bar">
        <div class="search-field">
          <label for="search-destination">Destination</label>
          <select id="search-destination">
            <option value="">All Destinations</option>
            <option>Palawan</option>
            <option>Bohol</option>
            <option>Siargao</option>
            <option>Batanes</option>
            <option>Cebu</option>
            <option>Davao</option>
          </select>
        </div>
        <div class="search-divider"></div>
        <div class="search-field">
          <label for="search-date">Travel Date</label>
          <input type="date" id="search-date" min="{{ date('Y-m-d') }}">
        </div>
        <div class="search-divider"></div>
        <div class="search-field">
          <label for="search-difficulty">Difficulty</label>
          <select id="search-difficulty">
            <option value="">Any Level</option>
            <option>Easy</option>
            <option>Moderate</option>
            <option>Challenging</option>
          </select>
        </div>
        <button class="search-btn" type="button">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <circle cx="6.5" cy="6.5" r="5" stroke="white" stroke-width="1.5"/>
            <path d="M10 10L14 14" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
          Search
        </button>
      </div>

      <!-- Stats -->
      <div class="hero-stats">
        <div class="hero-stat">
          <div class="hero-stat-num">240+</div>
          <div class="hero-stat-label">Tour Packages</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-num">58</div>
          <div class="hero-stat-label">Destinations</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-num">12k</div>
          <div class="hero-stat-label">Happy Travellers</div>
        </div>
        <div class="hero-stat">
          <div class="hero-stat-num">4.9★</div>
          <div class="hero-stat-label">Average Rating</div>
        </div>
      </div>

    </div>
  </section>


  <!-- FEATURED PACKAGES -->
  <section class="section packages-bg" id="packages">
    <div class="section-inner">

      <div class="section-header fade-up">
        <div>
          <span class="section-tag">✦ Featured</span>
          <h2 class="section-title">Handpicked <span>Packages</span></h2>
        </div>
        <a href="#" class="view-all">View All Packages →</a>
      </div>

      <div class="pkg-grid fade-up">

        <div class="pkg-card">
          <div class="pkg-img-wrap">
            <img src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?w=600&q=80" alt="Palawan Island Hopping" loading="lazy">
            <span class="pkg-badge">Featured</span>
          </div>
          <div class="pkg-body">
            <div class="pkg-rating">
              <div class="stars">
                <span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span>
              </div>
              <span class="rating-count">4.9 (38)</span>
            </div>
            <h3 class="pkg-title">Palawan Island Hopping &amp; Underground River</h3>
            <div class="pkg-meta">
              <span class="pill pill-teal">🕐 6D/5N</span>
              <span class="pill pill-sky">Easy</span>
              <span class="pill">📍 Palawan</span>
            </div>
            <div class="pkg-footer">
              <div>
                <div class="pkg-price">₱24,500</div>
                <div class="pkg-price-label">per person</div>
              </div>
              <a href="#" class="btn-sm">View →</a>
            </div>
          </div>
        </div>

        <div class="pkg-card">
          <div class="pkg-img-wrap">
            <img src="https://images.unsplash.com/photo-1552751753-0fc84ae5b6c8?w=600&q=80" alt="Chocolate Hills Bohol" loading="lazy">
            <span class="pkg-badge" style="background: var(--primary);">Popular</span>
          </div>
          <div class="pkg-body">
            <div class="pkg-rating">
              <div class="stars">
                <span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span>
              </div>
              <span class="rating-count">4.8 (54)</span>
            </div>
            <h3 class="pkg-title">Bohol Chocolate Hills &amp; Tarsier Sanctuary</h3>
            <div class="pkg-meta">
              <span class="pill pill-teal">🕐 5D/4N</span>
              <span class="pill pill-sky">Easy</span>
              <span class="pill">📍 Bohol</span>
            </div>
            <div class="pkg-footer">
              <div>
                <div class="pkg-price">₱18,800</div>
                <div class="pkg-price-label">per person</div>
              </div>
              <a href="#" class="btn-sm">View →</a>
            </div>
          </div>
        </div>

        <div class="pkg-card">
          <div class="pkg-img-wrap">
            <img src="https://images.unsplash.com/photo-1519046904884-53103b34b206?w=600&q=80" alt="Siargao Surfing" loading="lazy">
            <span class="pkg-badge new">New</span>
          </div>
          <div class="pkg-body">
            <div class="pkg-rating">
              <div class="stars">
                <span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-empty">★</span>
              </div>
              <span class="rating-count">4.7 (21)</span>
            </div>
            <h3 class="pkg-title">Siargao Surfing &amp; Island Discovery Tour</h3>
            <div class="pkg-meta">
              <span class="pill pill-teal">🕐 7D/6N</span>
              <span class="pill pill-amber">Moderate</span>
              <span class="pill">📍 Siargao</span>
            </div>
            <div class="pkg-footer">
              <div>
                <div class="pkg-price">₱28,500</div>
                <div class="pkg-price-label">per person</div>
              </div>
              <a href="#" class="btn-sm">View →</a>
            </div>
          </div>
        </div>

        <div class="pkg-card">
          <div class="pkg-img-wrap">
            <img src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=600&q=80" alt="Batanes Heritage" loading="lazy">
          </div>
          <div class="pkg-body">
            <div class="pkg-rating">
              <div class="stars">
                <span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span>
              </div>
              <span class="rating-count">5.0 (12)</span>
            </div>
            <h3 class="pkg-title">Batanes Heritage &amp; Scenic Highlands Trek</h3>
            <div class="pkg-meta">
              <span class="pill pill-teal">🕐 6D/5N</span>
              <span class="pill" style="background:#FFF1F2; color:#9F1239; border-color:#FECDD3;">Challenging</span>
              <span class="pill">📍 Batanes</span>
            </div>
            <div class="pkg-footer">
              <div>
                <div class="pkg-price">₱35,000</div>
                <span class="slots-warning">3 slots left!</span>
              </div>
              <a href="#" class="btn-sm">View →</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>


  <!-- BROWSE BY CATEGORY -->
  <section class="section cats-bg">
    <div class="section-inner">
      <div style="text-align: center; margin-bottom: 2.5rem;" class="fade-up">
        <span class="section-tag">✦ Explore</span>
        <h2 class="section-title">Browse by <span>Category</span></h2>
      </div>
      <div class="cats-grid fade-up">
        <a href="#" class="cat-card"><span class="cat-icon">🏔️</span><span class="cat-name">Adventure</span><span class="cat-count">48 packages</span></a>
        <a href="#" class="cat-card"><span class="cat-icon">🏖️</span><span class="cat-name">Beach</span><span class="cat-count">62 packages</span></a>
        <a href="#" class="cat-card"><span class="cat-icon">🏛️</span><span class="cat-name">Cultural</span><span class="cat-count">34 packages</span></a>
        <a href="#" class="cat-card"><span class="cat-icon">🌿</span><span class="cat-name">Eco Tour</span><span class="cat-count">27 packages</span></a>
        <a href="#" class="cat-card"><span class="cat-icon">🎭</span><span class="cat-name">City Tour</span><span class="cat-count">19 packages</span></a>
        <a href="#" class="cat-card"><span class="cat-icon">🤿</span><span class="cat-name">Diving</span><span class="cat-count">31 packages</span></a>
        <a href="#" class="cat-card"><span class="cat-icon">🍜</span><span class="cat-name">Food Tour</span><span class="cat-count">15 packages</span></a>
      </div>
    </div>
  </section>


  <!-- HOW IT WORKS -->
  <section class="section how-bg" id="how">
    <div class="section-inner">
      <div style="text-align: center; margin-bottom: 3rem;" class="fade-up">
        <span class="section-tag">✦ Simple Process</span>
        <h2 class="section-title">How It <span>Works</span></h2>
        <p style="color: var(--text-muted); margin-top: 0.5rem; font-size: 0.95rem;">Start your journey in three easy steps</p>
      </div>
      <div class="how-grid fade-up">
        <div class="how-step">
          <div class="how-icon-wrap"><span class="how-num">①</span></div>
          <h3 class="how-title">Browse &amp; Choose</h3>
          <p class="how-desc">Explore our curated packages. Filter by destination, difficulty, and budget to find your perfect trip.</p>
        </div>
        <div class="how-step">
          <div class="how-icon-wrap"><span class="how-num">②</span></div>
          <h3 class="how-title">Book &amp; Pay Securely</h3>
          <p class="how-desc">Reserve your slot and pay through our encrypted gateway. Instant confirmation and invoice delivered to your inbox.</p>
        </div>
        <div class="how-step">
          <div class="how-icon-wrap"><span class="how-num">③</span></div>
          <h3 class="how-title">Travel &amp; Enjoy</h3>
          <p class="how-desc">Show up and let your expert tour manager handle everything. Your only job is to make memories.</p>
        </div>
      </div>
    </div>
  </section>


  <!-- TESTIMONIALS -->
  <section class="section testimonials-bg">
    <div class="section-inner">
      <div style="text-align: center; margin-bottom: 2.5rem;" class="fade-up">
        <span class="section-tag">✦ Reviews</span>
        <h2 class="section-title">What Our <span>Guests Say</span></h2>
      </div>

      <div class="testimonial-carousel fade-up"
        x-data="{
          current: 0,
          reviews: [
            { text: 'Absolutely incredible experience from start to finish. The Palawan itinerary was perfectly paced — the right balance of adventure and relaxation. Our tour manager, Carlo, anticipated every need. Already booking my second trip!', name: 'Maria Santos', loc: 'Cebu City, PH', initials: 'MS' },
            { text: 'I have traveled with many operators across Asia, but VoyagePro stands in a different league. The booking process was seamless, the hotel choices superb, and the group was perfectly curated. Highly, highly recommend.', name: 'James Reyes', loc: 'Manila, PH', initials: 'JR' },
            { text: 'The Batanes trip was unlike anything I have ever experienced. Waking up to those rolling hills and stone houses felt like stepping into another century. VoyagePro made every logistical headache disappear. 10/10.', name: 'Ana de Guzman', loc: 'Davao City, PH', initials: 'AG' }
          ],
          init() { setInterval(() => this.current = (this.current + 1) % this.reviews.length, 5000); }
        }">

        <div class="testimonial-card">
          <div class="testimonial-quote">"</div>
          <p class="testimonial-text" x-text="reviews[current].text"></p>
          <div class="testimonial-author">
            <div class="avatar" x-text="reviews[current].initials"></div>
            <div>
              <div class="author-name" x-text="reviews[current].name"></div>
              <div class="author-loc" x-text="reviews[current].loc"></div>
            </div>
            <div class="stars" style="margin-left: auto;">
              <span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span><span class="star-filled">★</span>
            </div>
          </div>
        </div>

        <div class="carousel-controls">
          <button class="carousel-btn" @click="current = (current - 1 + reviews.length) % reviews.length" aria-label="Previous review">&#8592;</button>
          <div class="carousel-dots">
            <template x-for="(r, i) in reviews" :key="i">
              <button class="dot" :class="{ active: i === current }" @click="current = i" :aria-label="'Review ' + (i + 1)"></button>
            </template>
          </div>
          <button class="carousel-btn" @click="current = (current + 1) % reviews.length" aria-label="Next review">&#8594;</button>
        </div>
      </div>
    </div>
  </section>


  <!-- NEWSLETTER CTA -->
  <section class="newsletter-bg" id="contact">
    <div class="newsletter-inner fade-up">
      <h2>Get Exclusive Travel Deals</h2>
      <p>Join 8,000+ subscribers and be the first to know about new packages, flash discounts, and seasonal promotions.</p>
      <form class="newsletter-form" onsubmit="return false;">
        <input type="email" placeholder="Enter your email address" aria-label="Email address">
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


  <script>
    const observer = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          observer.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
  </script>

</body>
</html>