<template>
  <div class="about-page">
    <!-- Grain Overlay -->
    <div class="grain-overlay"></div>

    <!-- Hero Section -->
    <section class="about-hero">
      <div class="hero-bg-images">
        <div class="hero-img hero-img-1">
          <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=800&h=1200&fit=crop&crop=center" alt="Cakes" loading="eager">
        </div>
        <div class="hero-img hero-img-2">
          <img src="https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=700&h=1000&fit=crop&crop=center" alt="Cake" loading="eager">
        </div>
        <div class="hero-img hero-img-3">
          <img src="https://images.unsplash.com/photo-1621303837174-89787a7d4729?w=600&h=900&fit=crop&crop=center" alt="Birthday cake" loading="eager">
        </div>
      </div>
      <div class="hero-overlay"></div>
      <div class="hero-particles">
        <span v-for="n in 15" :key="n" class="particle" :style="particleStyle(n)"></span>
      </div>
      <div class="about-hero-inner" ref="heroInner">
        <div class="hero-badge-row">
          <span class="hero-line-deco"></span>
          <p class="about-badge">
            <span class="badge-dot"></span>
            Our Story
          </p>
          <span class="hero-line-deco"></span>
        </div>
        <h1 class="about-title">
          Welcome to<br>
          <span class="accent">Sweet Cake Store</span>
        </h1>
        <p class="about-subtitle">Where every celebration deserves a delicious and beautifully crafted cake.</p>
        <div class="hero-cta-row">
          <a href="#story" class="hero-btn primary">Explore Our Story</a>
          <a href="#offers" class="hero-btn ghost">View Cakes</a>
        </div>
        <div class="hero-scroll-cue">
          <div class="scroll-mouse">
            <div class="scroll-dot"></div>
          </div>
          <span>Scroll</span>
        </div>
      </div>
    </section>

    <!-- Our Story -->
    <section class="about-section" id="story">
      <div class="section-inner">
        <div class="about-grid">
          <div class="about-text" ref="storyText">
            <p class="section-label left">
              <span class="label-line"></span>
              Our Story
            </p>
            <h2 class="section-title left-align">Baking Dreams<br>Since Day One</h2>
            <div class="story-text-block">
              <p class="about-desc">
                At Sweet Cake Store, we believe every celebration deserves a delicious and beautifully crafted cake. Since our founding, we have been dedicated to creating fresh, high-quality cakes and desserts that bring joy to our customers.
              </p>
              <p class="about-desc">
                Sweet Cake Store started with a passion for baking and a dream of sharing homemade flavors with the community. What began as a small bakery has grown into a trusted cake shop known for creativity, quality, and excellent customer service.
              </p>
            </div>
            <div class="story-stats">
              <div class="stat-item" v-for="stat in stats" :key="stat.label">
                <span class="stat-number" :data-target="stat.value">{{ stat.prefix }}0{{ stat.suffix }}</span>
                <span class="stat-label">{{ stat.label }}</span>
              </div>
            </div>
          </div>
          <div class="about-visual" ref="storyVisual">
            <div class="visual-stack">
              <div class="visual-img-main">
                <img src="https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=600&h=750&fit=crop&crop=center" alt="Cake decorating" loading="lazy">
                <div class="visual-img-badge">
                  <span>✨ Handcrafted</span>
                </div>
              </div>
              <div class="visual-img-float">
                <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=300&h=300&fit=crop&crop=center" alt="Cake slice" loading="lazy">
              </div>
              <div class="visual-since-badge">
                <span class="since-year">2026</span>
                <span class="since-text">Sokkhoeurn</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Mission -->
    <section class="about-section section-alt">
      <div class="section-inner">
        <div class="mission-layout" ref="missionSection">
          <div class="mission-images-col">
            <div class="mission-img-stack">
              <div class="mission-img mission-img-1">
                <img src="https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?w=500&h=600&fit=crop&crop=center" alt="Wedding cake" loading="lazy">
              </div>
              <div class="mission-img mission-img-2">
                <img src="https://images.unsplash.com/photo-1535141192574-5d4897c12f40?w=500&h=600&fit=crop&crop=center" alt="Decorated cake" loading="lazy">
              </div>
            </div>
          </div>
          <div class="mission-content">
            <p class="section-label left">
              <span class="label-line"></span>
              Our Mission
            </p>
            <h2 class="section-title left-align">Creating Memorable Moments</h2>
            <div class="mission-card">
              <div class="mission-shimmer-bar"></div>
              <div class="mission-icon-row">
                <div class="mission-icon-wrap">
                  <span class="mission-icon">💖</span>
                </div>
              </div>
              <p class="mission-text">
                Our mission is to create memorable moments through delicious cakes made with fresh ingredients, unique designs, and exceptional care. We strive to make every birthday, wedding, anniversary, and special occasion unforgettable.
              </p>
            </div>
            <div class="mission-pillars">
              <div class="pillar-item">
                <div class="pillar-img">
                  <img src="https://images.unsplash.com/photo-1509365390695-33aee754301f?w=200&h=200&fit=crop&crop=center" alt="Fresh ingredients" loading="lazy">
                </div>
                <span class="pillar-label">🌿 Fresh Ingredients</span>
              </div>
              <div class="pillar-item">
                <div class="pillar-img">
                  <img src="https://images.unsplash.com/photo-1486427944544-d2c246c4df14?w=200&h=200&fit=crop&crop=center" alt="Unique designs" loading="lazy">
                </div>
                <span class="pillar-label">🎨 Unique Designs</span>
              </div>
              <div class="pillar-item">
                <div class="pillar-img">
                  <img src="https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81?w=200&h=200&fit=crop&crop=center" alt="Made with love" loading="lazy">
                </div>
                <span class="pillar-label">💝 Made with Love</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- What We Offer -->
    <section class="about-section" id="offers">
      <div class="section-inner">
        <div ref="offersHeader">
          <p class="section-label">
            <span class="label-line"></span>
            What We Offer
            <span class="label-line"></span>
          </p>
          <h2 class="section-title">Delicious Creations</h2>
          <p class="section-subtitle">From classic favorites to custom masterpieces, we have something for every occasion.</p>
        </div>
        <div class="offers-grid" ref="offersSection">
          <div v-for="(item, i) in offers" :key="item.text" class="offer-item" :style="{ '--i': i }">
            <div class="offer-img-wrap">
              <img :src="item.image" :alt="item.text" loading="lazy">
              <div class="offer-img-overlay"></div>
              <div class="offer-img-icon">{{ item.icon }}</div>
            </div>
            <div class="offer-body">
              <h4>{{ item.text }}</h4>
              <p>{{ item.desc }}</p>
              <span class="offer-link">
                Learn more
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M3 8h10m0 0l-4-4m4 4l-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="about-section section-alt">
      <div class="section-inner">
        <div ref="whyHeader">
          <p class="section-label">
            <span class="label-line"></span>
            Why Choose Us
            <span class="label-line"></span>
          </p>
          <h2 class="section-title">The Sweet Cake Difference</h2>
        </div>
        <div class="why-grid" ref="whySection">
          <div v-for="(reason, i) in whyUs" :key="reason.title" class="why-card" :style="{ '--i': i }">
            <div class="why-img-wrap">
              <img :src="reason.image" :alt="reason.title" loading="lazy">
              <div class="why-img-overlay"></div>
            </div>
            <div class="why-body">
              <div class="why-number">{{ String(i + 1).padStart(2, '0') }}</div>
              <span class="why-icon">{{ reason.icon }}</span>
              <h4>{{ reason.title }}</h4>
              <p>{{ reason.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Values -->
    <section class="about-section">
      <div class="section-inner">
        <div ref="valuesHeader">
          <p class="section-label">
            <span class="label-line"></span>
            Our Values
            <span class="label-line"></span>
          </p>
          <h2 class="section-title">What We Stand For</h2>
        </div>
        <div class="values-grid" ref="valuesSection">
          <div v-for="(value, i) in values" :key="value.title" class="value-card" :style="{ '--i': i }">
            <div class="value-top-accent"></div>
            <div class="value-img-wrap">
              <img :src="value.image" :alt="value.title" loading="lazy">
              <div class="value-img-overlay"></div>
            </div>
            <div class="value-body">
              <div class="value-icon-wrap">
                <span class="value-icon">{{ value.icon }}</span>
              </div>
              <h4>{{ value.title }}</h4>
              <p>{{ value.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Meet Our Team -->
    <section class="about-section section-alt">
      <div class="section-inner">
        <div ref="teamHeader">
          <p class="section-label">
            <span class="label-line"></span>
            Meet Our Team
            <span class="label-line"></span>
          </p>
          <h2 class="section-title">The People Behind the Cakes</h2>
          <p class="section-subtitle">Our talented team of bakers, decorators, and customer service staff work together to provide the best experience for every customer.</p>
        </div>
        <div class="team-grid" ref="teamSection">
          <div v-for="(member, i) in team" :key="member.role" class="team-card" :style="{ '--i': i }">
            <div class="team-img-wrap">
              <img src="../image/image.png" :alt="member.role" loading="lazy">
              <div class="team-img-overlay"></div>
              <div class="team-avatar-float">{{ member.emoji }}</div>
            </div>
            <div class="team-body">
              <h4>{{ member.role }}</h4>
              <p>{{ member.desc }}</p>
              <div class="team-socials">
                <span class="social-dot"></span>
                <span class="social-dot"></span>
                <span class="social-dot"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Gallery Strip -->
    <section class="gallery-strip" ref="galleryStrip">
      <div class="gallery-label">
        <span class="gallery-label-text">Our Creations</span>
      </div>
      <div class="gallery-track">
        <div class="gallery-item" v-for="img in galleryImages" :key="img">
          <img :src="img" alt="Our cake creation" loading="lazy">
        </div>
        <div class="gallery-item" v-for="img in galleryImages" :key="'d-'+img">
          <img :src="img" alt="Our cake creation" loading="lazy">
        </div>
      </div>
    </section>

    <!-- Contact CTA -->
    <section class="about-cta">
      <div class="cta-pattern"></div>
      <div class="cta-glow cta-glow-1"></div>
      <div class="cta-glow cta-glow-2"></div>
      <div class="section-inner">
        <div class="cta-inner" ref="ctaSection">
          <div class="cta-content">
            <p class="cta-label">Let's Connect</p>
            <h2>Ready to Order Your Dream Cake?</h2>
            <p>Get in touch with us today and let's create something sweet together.</p>
            <div class="contact-info">
              <div class="contact-item">
                <span class="contact-icon">📍</span>
                <span>123 Bakery Lane, Sweet Town</span>
              </div>
              <div class="contact-item">
                <span class="contact-icon">📞</span>
                <span>(555) 123-4567</span>
              </div>
              <div class="contact-item">
                <span class="contact-icon">✉️</span>
                <span>hello@sweetcakestore.com</span>
              </div>
            </div>
            <div class="cta-btn-row">
              <a href="#" class="cta-btn">Order Now</a>
              <a href="#" class="cta-btn outline">View Menu</a>
            </div>
          </div>
          <div class="cta-visual">
            <div class="cta-img-wrap">
              <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&h=650&fit=crop&crop=center" alt="Order a cake" loading="lazy">
              <div class="cta-img-overlay"></div>
              <div class="cta-img-badge">🍰 Order Today</div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const heroInner = ref(null)
const storyText = ref(null)
const storyVisual = ref(null)
const missionSection = ref(null)
const offersSection = ref(null)
const offersHeader = ref(null)
const whySection = ref(null)
const whyHeader = ref(null)
const valuesSection = ref(null)
const valuesHeader = ref(null)
const teamSection = ref(null)
const teamHeader = ref(null)
const galleryStrip = ref(null)
const ctaSection = ref(null)

const stats = [
  { value: 500, prefix: '', suffix: '+', label: 'Cakes Delivered' },
  { value: 100, prefix: '', suffix: '%', label: 'Fresh Daily' },
  { value: 50, prefix: '', suffix: '+', label: 'Unique Flavors' },
]

const offers = [
  { icon: '🎂', text: 'Birthday Cakes', desc: 'Celebrate another wonderful year with a showstopping cake.', image: 'https://images.unsplash.com/photo-1621303837174-89787a7d4729?w=500&h=400&fit=crop&crop=center' },
  { icon: '💍', text: 'Wedding Cakes', desc: 'Elegant multi-tier cakes for your perfect day.', image: 'https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?w=500&h=400&fit=crop&crop=center' },
  { icon: '✨', text: 'Custom Designs', desc: 'Bring your vision to life with bespoke creations.', image: 'https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=500&h=400&fit=crop&crop=center' },
  { icon: '🧁', text: 'Cupcakes', desc: 'Bite-sized perfection in dozens of flavors.', image: 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&h=400&fit=crop&crop=center' },
  { icon: '🥐', text: 'Pastries & Desserts', desc: 'Freshly baked croissants, tarts, and more.', image: 'https://images.unsplash.com/photo-1555507036-ab1f4038024a?w=500&h=400&fit=crop&crop=center' },
  { icon: '🎄', text: 'Seasonal Specials', desc: 'Limited-edition treats for every holiday.', image: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=500&h=400&fit=crop&crop=center' },
]

const whyUs = [
  { icon: '🌿', title: 'Fresh Ingredients', desc: 'We use premium ingredients to ensure every cake tastes amazing.', image: 'https://images.unsplash.com/photo-1509365390695-33aee754301f?w=400&h=300&fit=crop&crop=center' },
  { icon: '👩‍🍳', title: 'Skilled Bakers', desc: 'Our team of experienced bakers brings creativity to every creation.', image: 'https://images.unsplash.com/photo-1464305795204-6f5bbfc7fb81?w=400&h=300&fit=crop&crop=center' },
  { icon: '🎨', title: 'Custom Designs', desc: "We design unique cakes tailored to our customers' needs.", image: 'https://images.unsplash.com/photo-1517433670267-08bbd4be890f?w=400&h=300&fit=crop&crop=center' },
  { icon: '💰', title: 'Affordable Prices', desc: 'Quality cakes at prices that fit your budget.', image: 'https://images.unsplash.com/photo-1562440499-64c9a111f713?w=400&h=300&fit=crop&crop=center' },
  { icon: '🚚', title: 'Reliable Delivery', desc: 'Timely and careful delivery for your special occasions.', image: 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400&h=300&fit=crop&crop=center' },
  { icon: '💬', title: 'Customer Support', desc: 'We are here to help you every step of the way.', image: 'https://images.unsplash.com/photo-1556217477-d325251ece38?w=400&h=300&fit=crop&crop=center' },
]

const values = [
  { icon: '🏆', title: 'Quality', desc: 'We use premium ingredients to ensure every cake tastes amazing.', image: 'https://images.unsplash.com/photo-1535141192574-5d4897c12f40?w=500&h=350&fit=crop&crop=center' },
  { icon: '🎨', title: 'Creativity', desc: "We design unique cakes tailored to our customers' needs.", image: 'https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=500&h=350&fit=crop&crop=center' },
  { icon: '😊', title: 'Customer Satisfaction', desc: 'We work hard to exceed customer expectations and create lasting memories.', image: 'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?w=500&h=350&fit=crop&crop=center' },
]

const team = [
  { emoji: '👩‍🍳', role: 'Master Bakers', desc: 'Creating delicious bases with years of experience.', image: 'https://images.unsplash.com/photo-1556217477-d325251ece38?w=500&h=600&fit=crop&crop=center' },
  { emoji: '🎨', role: 'Decorators', desc: 'Turning cakes into works of art.', image: 'https://images.unsplash.com/photo-1507048331197-7d4ac70811cf?w=500&h=600&fit=crop&crop=center' },
  { emoji: '💁‍♀️', role: 'Customer Service', desc: 'Ensuring a smooth and happy experience.', image: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=500&h=600&fit=crop&crop=center' },
]

const galleryImages = [
  'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1621303837174-89787a7d4729?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1535141192574-5d4897c12f40?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1562440499-64c9a111f713?w=500&h=350&fit=crop&crop=center',
  'https://images.unsplash.com/photo-1586788680434-30d324b2d46f?w=500&h=350&fit=crop&crop=center',
]

function particleStyle(n) {
  const size = 2 + Math.random() * 5
  return {
    '--x': Math.random() * 100 + '%',
    '--y': Math.random() * 100 + '%',
    '--size': size + 'px',
    '--delay': Math.random() * 6 + 's',
    '--dur': 5 + Math.random() * 7 + 's',
    '--dx': (Math.random() - 0.5) * 50 + 'px',
    '--dy': -30 - Math.random() * 60 + 'px',
  }
}

let observer = null

onMounted(() => {
  if (heroInner.value) heroInner.value.classList.add('visible')

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible')
          if (entry.target === storyText.value) animateCounters()
          const children = entry.target.querySelectorAll('.offer-item, .why-card, .value-card, .team-card')
          children.forEach((child, i) => {
            setTimeout(() => child.classList.add('visible'), i * 120)
          })
          observer.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.12, rootMargin: '0px 0px -50px 0px' }
  )

  const els = [
    storyText, storyVisual, missionSection,
    offersSection, offersHeader,
    whySection, whyHeader,
    valuesSection, valuesHeader,
    teamSection, teamHeader,
    ctaSection
  ]
  els.forEach((el) => { if (el.value) observer.observe(el.value) })

  if (galleryStrip.value) {
    const go = new IntersectionObserver((entries) => {
      entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add('active') })
    }, { threshold: 0 })
    go.observe(galleryStrip.value)
  }
})

onBeforeUnmount(() => { if (observer) observer.disconnect() })

function animateCounters() {
  document.querySelectorAll('.stat-number[data-target]').forEach((counter) => {
    const target = parseInt(counter.dataset.target)
    const suffix = counter.textContent.replace(/[0-9]/g, '')
    const duration = 2000
    const start = performance.now()
    function update(now) {
      const progress = Math.min((now - start) / duration, 1)
      const eased = 1 - Math.pow(1 - progress, 4)
      counter.textContent = Math.round(eased * target) + suffix
      if (progress < 1) requestAnimationFrame(update)
    }
    requestAnimationFrame(update)
  })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap');

/* ===== BASE ===== */
.about-page {
  overflow-x: hidden;
  font-family: 'Inter', sans-serif;
  color: #2d1b0e;
  background: #fffdfb;
}

/* Grain overlay */
.grain-overlay {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 9999;
  opacity: 0.025;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-repeat: repeat;
  background-size: 180px;
}

/* ===== KEYFRAMES ===== */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(50px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInLeft {
  from { opacity: 0; transform: translateX(-50px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes fadeInRight {
  from { opacity: 0; transform: translateX(50px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.88); }
  to { opacity: 1; transform: scale(1); }
}
@keyframes floatSlow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-14px); }
}
@keyframes floatRotate {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-10px) rotate(4deg); }
}
@keyframes particleDrift {
  0% { transform: translate(0, 0); opacity: 0; }
  15% { opacity: 0.7; }
  85% { opacity: 0.7; }
  100% { transform: translate(var(--dx), var(--dy)); opacity: 0; }
}
@keyframes marquee {
  to { transform: translateX(-50%); }
}
@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}
@keyframes pulseGlow {
  0%, 100% { box-shadow: 0 20px 60px rgba(200,119,58,0.15); }
  50% { box-shadow: 0 20px 80px rgba(200,119,58,0.3); }
}
@keyframes scrollDot {
  0%, 100% { transform: translateY(0); opacity: 1; }
  50% { transform: translateY(8px); opacity: 0.3; }
}
@keyframes lineReveal {
  from { transform: scaleX(0); }
  to { transform: scaleX(1); }
}
@keyframes badgePulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.3; transform: scale(0.6); }
}

/* ===== REVEAL SYSTEM ===== */
.about-text,
.about-visual,
.mission-layout,
.offers-grid,
.offers-header-el,
.why-grid,
.values-grid,
.team-grid,
.cta-inner {
  opacity: 0;
  transition: opacity 0.9s cubic-bezier(0.22, 1, 0.36, 1),
              transform 0.9s cubic-bezier(0.22, 1, 0.36, 1);
  transform: translateY(50px);
}

.about-text.visible {
  opacity: 1; transform: none;
  animation: fadeInLeft 0.9s cubic-bezier(0.22, 1, 0.36, 1) both;
}
.about-visual.visible {
  opacity: 1; transform: none;
  animation: fadeInRight 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.15s both;
}
.mission-layout.visible,
.offers-grid.visible,
.why-grid.visible,
.values-grid.visible,
.team-grid.visible,
.cta-inner.visible {
  opacity: 1; transform: none;
}

/* Header reveals */
.section-label, .section-title, .section-subtitle {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
              transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
}

.visible > .section-label,
.visible > .section-title,
.visible > .section-subtitle {
  opacity: 1; transform: none;
}
.visible > .section-label { transition-delay: 0s; }
.visible > .section-title { transition-delay: 0.1s; }
.visible > .section-subtitle { transition-delay: 0.2s; }

/* Card stagger */
.offer-item, .why-card, .value-card, .team-card {
  opacity: 0;
  transform: translateY(35px);
  transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1),
              transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
}
.offer-item.visible, .why-card.visible, .value-card.visible, .team-card.visible {
  opacity: 1; transform: none;
}

/* ===== HERO ===== */
.about-hero {
  position: relative;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: #1a0e06;
}

.hero-bg-images {
  position: absolute;
  inset: 0;
}

.hero-img {
  position: absolute;
  overflow: hidden;
}

.hero-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.4) saturate(0.85);
  transition: transform 10s cubic-bezier(0.22, 1, 0.36, 1), filter 1.5s;
}

.about-hero:hover .hero-img img {
  transform: scale(1.1);
  filter: brightness(0.45) saturate(1);
}

.hero-img-1 {
  left: 0; top: 0; width: 50%; height: 100%;
  clip-path: polygon(0 0, 100% 0, 82% 100%, 0 100%);
}
.hero-img-2 {
  left: 38%; top: 0; width: 42%; height: 100%;
  clip-path: polygon(25% 0, 100% 0, 100% 100%, 0 100%);
}
.hero-img-3 {
  right: 0; top: 5%; width: 28%; height: 90%;
  clip-path: polygon(35% 0, 100% 0, 100% 100%, 0 100%);
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg,
    rgba(26,14,6,0.25) 0%,
    rgba(26,14,6,0.5) 40%,
    rgba(26,14,6,0.92) 100%
  );
  z-index: 1;
}

.hero-particles {
  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;
}

.particle {
  position: absolute;
  left: var(--x);
  top: var(--y);
  width: var(--size);
  height: var(--size);
  background: rgba(232,167,108,0.6);
  border-radius: 50%;
  animation: particleDrift var(--dur) var(--delay) ease-in-out infinite;
}

.about-hero-inner {
  position: relative;
  z-index: 3;
  max-width: 780px;
  margin: 0 auto;
  padding: 4rem 2rem;
  text-align: center;
  opacity: 0;
  transform: translateY(60px);
  transition: opacity 1.2s 0.3s cubic-bezier(0.22, 1, 0.36, 1),
              transform 1.2s 0.3s cubic-bezier(0.22, 1, 0.36, 1);
}
.about-hero-inner.visible {
  opacity: 1; transform: none;
}

.hero-badge-row {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 2.5rem;
}

.hero-line-deco {
  width: 50px;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(232,167,108,0.4), transparent);
}

.about-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  color: #e8a76c;
  border: 1px solid rgba(232,167,108,0.15);
  padding: 0.6rem 1.6rem;
  border-radius: 50px;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
}

.badge-dot {
  width: 6px; height: 6px;
  background: #e8a76c;
  border-radius: 50%;
  animation: badgePulse 2s ease-in-out infinite;
}

.about-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2.5rem, 6vw, 4.2rem);
  font-weight: 700;
  color: #fff;
  line-height: 1.1;
  margin-bottom: 1.5rem;
  letter-spacing: -0.025em;
}

.accent {
  color: #e8a76c;
  position: relative;
  display: inline-block;
}
.accent::after {
  content: '';
  position: absolute;
  bottom: 4px; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, #e8a76c, #f5d0a0);
  border-radius: 2px;
  opacity: 0.5;
  transform: scaleX(0);
  transform-origin: left;
  animation: lineReveal 0.9s 1.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.about-subtitle {
  color: rgba(212,184,150,0.8);
  font-size: 1.1rem;
  line-height: 1.75;
  max-width: 520px;
  margin: 0 auto 2.5rem;
  font-weight: 400;
}

.hero-cta-row {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.hero-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.85rem 2rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  cursor: pointer;
}

.hero-btn.primary {
  background: linear-gradient(135deg, #c8773a, #e8a76c);
  color: #fff;
  box-shadow: 0 4px 20px rgba(200,119,58,0.3);
}
.hero-btn.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(200,119,58,0.4);
}

.hero-btn.ghost {
  background: transparent;
  color: #e8a76c;
  border: 1px solid rgba(232,167,108,0.3);
}
.hero-btn.ghost:hover {
  background: rgba(232,167,108,0.1);
  border-color: rgba(232,167,108,0.5);
  transform: translateY(-2px);
}

.hero-scroll-cue {
  position: absolute;
  bottom: 2rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  color: rgba(212,184,150,0.35);
  font-size: 0.65rem;
  letter-spacing: 0.15em;
  text-transform: uppercase;
}

.scroll-mouse {
  width: 22px; height: 36px;
  border: 1.5px solid rgba(212,184,150,0.3);
  border-radius: 12px;
  display: flex;
  justify-content: center;
  padding-top: 8px;
}

.scroll-dot {
  width: 3px; height: 8px;
  background: rgba(212,184,150,0.5);
  border-radius: 2px;
  animation: scrollDot 2s ease-in-out infinite;
}

/* ===== SECTIONS ===== */
.about-section {
  padding: 7rem 0;
  position: relative;
}

.section-alt {
  background: linear-gradient(180deg, #fdf5ef 0%, #fefaf5 50%, #fdf5ef 100%);
}

.section-inner {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 2rem;
}

.section-label {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  font-size: 0.7rem;
  font-weight: 700;
  color: #c8773a;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  margin-bottom: 0.75rem;
}
.section-label.left { justify-content: flex-start; }

.label-line {
  width: 30px; height: 1px;
  background: linear-gradient(90deg, transparent, #d4a87a);
}

.section-title {
  font-family: 'Playfair Display', serif;
  text-align: center;
  font-size: clamp(1.9rem, 3.5vw, 2.6rem);
  font-weight: 700;
  color: #2d1b0e;
  margin-bottom: 1rem;
  letter-spacing: -0.015em;
  line-height: 1.2;
}
.section-title.left-align {
  text-align: left;
  margin-bottom: 2rem;
}

.section-subtitle {
  text-align: center;
  color: #8a6b55;
  font-size: 0.95rem;
  line-height: 1.7;
  max-width: 560px;
  margin: 0 auto 3rem;
}

/* ===== STORY ===== */
.about-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 5rem;
  align-items: center;
}

.about-desc {
  color: #6b5040;
  font-size: 0.95rem;
  line-height: 1.85;
  margin-bottom: 1rem;
}

.story-text-block { margin-bottom: 2.5rem; }

.story-stats {
  display: flex;
  background: white;
  border-radius: 18px;
  border: 1px solid rgba(240,230,216,0.7);
  box-shadow: 0 4px 24px rgba(200,119,58,0.05);
  overflow: hidden;
  margin-bottom: 2.5rem;
}

.stat-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.2rem;
  padding: 1.5rem 1rem;
  position: relative;
}
.stat-item:not(:last-child)::after {
  content: '';
  position: absolute;
  right: 0; top: 25%; height: 50%; width: 1px;
  background: linear-gradient(180deg, transparent, #e8d5c0, transparent);
}

.stat-number {
  font-family: 'Playfair Display', serif;
  font-size: 1.7rem;
  font-weight: 700;
  color: #c8773a;
}
.stat-label {
  font-size: 0.68rem;
  color: #9a7560;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.story-signature {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 0;
  border-top: 1px solid rgba(240,230,216,0.6);
}

.signature-avatar {
  width: 48px; height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(200,119,58,0.2);
}

.signature-name {
  font-weight: 700;
  color: #2d1b0e;
  font-size: 0.9rem;
}
.signature-role {
  font-size: 0.78rem;
  color: #9a7560;
}

/* Visual Stack */
.visual-stack {
  position: relative;
  width: 100%;
  max-width: 420px;
}

.visual-img-main {
  border-radius: 24px;
  overflow: hidden;
  box-shadow: 0 24px 64px rgba(45,27,14,0.12);
  position: relative;
}
.visual-img-main img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  display: block;
  transition: transform 7s cubic-bezier(0.22, 1, 0.36, 1);
}
.visual-stack:hover .visual-img-main img {
  transform: scale(1.07);
}

.visual-img-badge {
  position: absolute;
  bottom: 20px; left: 20px;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(10px);
  padding: 0.5rem 1.1rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 600;
  color: #2d1b0e;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.visual-img-float {
  position: absolute;
  bottom: -28px; right: -24px;
  width: 140px; height: 140px;
  border-radius: 22px;
  overflow: hidden;
  border: 4px solid white;
  box-shadow: 0 16px 40px rgba(45,27,14,0.15);
  animation: floatRotate 6s ease-in-out infinite;
  z-index: 2;
}
.visual-img-float img {
  width: 100%; height: 100%;
  object-fit: cover;
}

.visual-since-badge {
  position: absolute;
  top: -14px; left: -14px;
  background: linear-gradient(135deg, #c8773a, #e8a76c);
  color: white;
  border-radius: 16px;
  padding: 0.7rem 1rem;
  text-align: center;
  box-shadow: 0 8px 24px rgba(200,119,58,0.3);
  z-index: 2;
  animation: floatSlow 5s ease-in-out 1s infinite;
}
.since-year {
  display: block;
  font-family: 'Playfair Display', serif;
  font-size: 1.4rem;
  font-weight: 700;
  line-height: 1;
}
.since-text {
  font-size: 0.6rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  opacity: 0.85;
}

/* ===== MISSION ===== */
.mission-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 4rem;
  align-items: start;
}

.mission-images-col {
  position: sticky;
  top: 2rem;
}

.mission-img-stack {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.mission-img {
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(45,27,14,0.08);
  transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.5s;
}
.mission-img:hover {
  transform: translateY(-5px);
  box-shadow: 0 16px 40px rgba(45,27,14,0.14);
}
.mission-img img {
  width: 100%; height: 200px;
  object-fit: cover; display: block;
  transition: transform 5s cubic-bezier(0.22, 1, 0.36, 1);
}
.mission-img:hover img { transform: scale(1.08); }

.mission-card {
  background: white;
  border: 1px solid rgba(240,230,216,0.7);
  border-radius: 22px;
  padding: 2.5rem 2.25rem;
  box-shadow: 0 4px 20px rgba(200,119,58,0.04);
  position: relative;
  overflow: hidden;
}

.mission-shimmer-bar {
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, #c8773a, #f5d0a0, #c8773a);
  background-size: 200% 100%;
  animation: shimmer 3s linear infinite;
}

.mission-icon-wrap {
  width: 54px; height: 54px;
  background: linear-gradient(135deg, #fdf0e6, #fce8d4);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 14px rgba(200,119,58,0.08);
}
.mission-icon { font-size: 1.5rem; }

.mission-text {
  color: #5a4535;
  font-size: 0.97rem;
  line-height: 1.85;
}

.mission-pillars {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
  flex-wrap: wrap;
}

.pillar-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.6rem;
  background: white;
  border: 1px solid rgba(240,230,216,0.6);
  border-radius: 16px;
  padding: 1rem 1.25rem 0.85rem;
  transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  cursor: default;
  flex: 1;
  min-width: 120px;
}
.pillar-item:hover {
  border-color: rgba(200,119,58,0.3);
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(200,119,58,0.08);
}

.pillar-img {
  width: 52px; height: 52px;
  border-radius: 14px;
  overflow: hidden;
}
.pillar-img img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 3s;
}
.pillar-item:hover .pillar-img img { transform: scale(1.15); }

.pillar-label {
  font-size: 0.78rem;
  font-weight: 600;
  color: #2d1b0e;
  text-align: center;
}

/* ===== OFFERS ===== */
.offers-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}

.offer-item {
  background: white;
  border: 1px solid rgba(240,230,216,0.6);
  border-radius: 22px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.offer-item:hover {
  transform: translateY(-10px);
  box-shadow: 0 28px 56px rgba(200,119,58,0.1);
  border-color: rgba(200,119,58,0.2);
}

.offer-img-wrap {
  position: relative;
  height: 190px;
  overflow: hidden;
}
.offer-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 6s cubic-bezier(0.22, 1, 0.36, 1);
}
.offer-item:hover .offer-img-wrap img { transform: scale(1.12); }

.offer-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, transparent 30%, rgba(45,27,14,0.35) 100%);
  transition: opacity 0.4s;
}

.offer-img-icon {
  position: absolute;
  top: 14px; right: 14px;
  width: 40px; height: 40px;
  background: rgba(255,255,255,0.9);
  backdrop-filter: blur(8px);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transition: transform 0.4s;
}
.offer-item:hover .offer-img-icon { transform: rotate(-8deg) scale(1.1); }

.offer-body {
  padding: 1.25rem 1.5rem 1.5rem;
}
.offer-body h4 {
  font-weight: 700;
  color: #2d1b0e;
  margin-bottom: 0.4rem;
  font-size: 1rem;
}
.offer-body p {
  font-size: 0.82rem;
  color: #9a7560;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.offer-link {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: #c8773a;
  transition: gap 0.3s, color 0.3s;
}
.offer-item:hover .offer-link {
  gap: 0.7rem;
  color: #a05e28;
}

/* ===== WHY US ===== */
.why-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}

.why-card {
  position: relative;
  background: white;
  border: 1px solid rgba(240,230,216,0.6);
  border-radius: 22px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.why-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 28px 56px rgba(200,119,58,0.1);
  border-color: rgba(200,119,58,0.2);
}

.why-img-wrap {
  height: 155px;
  overflow: hidden;
}
.why-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 6s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s;
  filter: brightness(0.95);
}
.why-card:hover .why-img-wrap img {
  transform: scale(1.12);
  filter: brightness(1.05);
}

.why-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, transparent 40%, rgba(45,27,14,0.25) 100%);
  pointer-events: none;
}

.why-body {
  padding: 1.5rem 1.5rem 1.75rem;
  text-align: center;
  position: relative;
}

.why-number {
  position: absolute;
  top: -18px; right: 16px;
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  font-weight: 700;
  color: rgba(200,119,58,0.06);
  line-height: 1;
  transition: color 0.4s;
}
.why-card:hover .why-number { color: rgba(200,119,58,0.12); }

.why-icon {
  font-size: 2rem;
  display: inline-block;
  margin-bottom: 0.75rem;
  transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));
}
.why-card:hover .why-icon {
  transform: scale(1.25) rotate(-6deg);
}

.why-card h4 {
  font-weight: 700;
  color: #2d1b0e;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}
.why-card p {
  font-size: 0.82rem;
  color: #9a7560;
  line-height: 1.65;
}

/* ===== VALUES ===== */
.values-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.value-card {
  position: relative;
  background: white;
  border: 1px solid rgba(240,230,216,0.6);
  border-radius: 24px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.value-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 28px 56px rgba(200,119,58,0.1);
  border-color: rgba(200,119,58,0.2);
}

.value-top-accent {
  position: absolute;
  top: 0; left: 50%;
  transform: translateX(-50%);
  width: 0; height: 3px;
  background: linear-gradient(90deg, transparent, #c8773a, transparent);
  transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
  z-index: 2;
}
.value-card:hover .value-top-accent { width: 130px; }

.value-img-wrap {
  position: relative;
  height: 190px;
  overflow: hidden;
}
.value-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 6s cubic-bezier(0.22, 1, 0.36, 1);
}
.value-card:hover .value-img-wrap img { transform: scale(1.1); }

.value-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, transparent 25%, rgba(45,27,14,0.45) 100%);
}

.value-body {
  padding: 1.75rem 1.75rem 2rem;
  text-align: center;
  margin-top: -32px;
  position: relative;
  z-index: 1;
}

.value-icon-wrap {
  width: 70px; height: 70px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.25rem;
  box-shadow: 0 8px 28px rgba(45,27,14,0.1);
  border: 1px solid rgba(240,230,216,0.8);
  transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.value-card:hover .value-icon-wrap {
  transform: scale(1.12) translateY(-5px);
  box-shadow: 0 14px 36px rgba(200,119,58,0.15);
}

.value-icon { font-size: 1.8rem; }

.value-card h4 {
  font-weight: 700;
  color: #2d1b0e;
  margin-bottom: 0.6rem;
  font-size: 1.05rem;
}
.value-card p {
  font-size: 0.85rem;
  color: #9a7560;
  line-height: 1.65;
}

/* ===== TEAM ===== */
.team-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
}

.team-card {
  position: relative;
  background: white;
  border: 1px solid rgba(240,230,216,0.6);
  border-radius: 24px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.team-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 28px 56px rgba(200,119,58,0.1);
  border-color: rgba(200,119,58,0.2);
}

.team-img-wrap {
  position: relative;
  height: 280px;
  overflow: hidden;
}
.team-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 6s cubic-bezier(0.22, 1, 0.36, 1), filter 0.5s;
  filter: brightness(0.9) saturate(0.95);
}
.team-card:hover .team-img-wrap img {
  transform: scale(1.08);
  filter: brightness(1) saturate(1.05);
}

.team-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, transparent 35%, rgba(45,27,14,0.55) 100%);
  transition: opacity 0.4s;
}

.team-avatar-float {
  position: absolute;
  bottom: -26px; left: 50%;
  transform: translateX(-50%);
  width: 54px; height: 54px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  box-shadow: 0 6px 24px rgba(45,27,14,0.15);
  border: 3px solid white;
  z-index: 2;
  transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.team-card:hover .team-avatar-float {
  transform: translateX(-50%) scale(1.15) translateY(-5px);
}

.team-body {
  padding: 2rem 1.5rem 1.75rem;
  text-align: center;
}
.team-card h4 {
  font-weight: 700;
  color: #2d1b0e;
  margin-bottom: 0.4rem;
  font-size: 1.05rem;
}
.team-card p {
  font-size: 0.84rem;
  color: #9a7560;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.team-socials {
  display: flex;
  justify-content: center;
  gap: 6px;
}
.social-dot {
  width: 28px; height: 28px;
  border-radius: 50%;
  background: #fdf0e6;
  border: 1px solid rgba(200,119,58,0.15);
  transition: all 0.3s;
  cursor: pointer;
}
.social-dot:hover {
  background: #c8773a;
  border-color: #c8773a;
  transform: scale(1.15);
}

/* ===== GALLERY ===== */
.gallery-strip {
  overflow: hidden;
  padding: 4rem 0;
  background: #1a0e06;
  position: relative;
  mask-image: linear-gradient(90deg, transparent, black 8%, black 92%, transparent);
  -webkit-mask-image: linear-gradient(90deg, transparent, black 8%, black 92%, transparent);
}

.gallery-label {
  position: absolute;
  left: 0; top: 0; bottom: 0;
  z-index: 2;
  display: flex;
  align-items: center;
  padding-left: 3rem;
}
.gallery-label-text {
  writing-mode: vertical-rl;
  text-orientation: mixed;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 0.25em;
  text-transform: uppercase;
  color: rgba(232,167,108,0.3);
}

.gallery-track {
  display: flex;
  gap: 1rem;
  width: max-content;
  animation: marquee 50s linear infinite;
}
.gallery-strip:not(.active) .gallery-track {
  animation-play-state: paused;
}

.gallery-item {
  width: 340px; height: 230px;
  border-radius: 16px;
  overflow: hidden;
  flex-shrink: 0;
  transition: transform 0.5s cubic-bezier(0.22, 1, 0.36, 1);
}
.gallery-item:hover { transform: scale(1.04); }
.gallery-item img {
  width: 100%; height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 5s cubic-bezier(0.22, 1, 0.36, 1);
  filter: brightness(0.85) saturate(0.9);
}
.gallery-item:hover img {
  transform: scale(1.12);
  filter: brightness(1) saturate(1);
}

/* ===== CTA ===== */
.about-cta {
  position: relative;
  background: linear-gradient(160deg, #1a0e06 0%, #3d2212 50%, #2d1b0e 100%);
  padding: 6rem 0;
  overflow: hidden;
}

.cta-pattern {
  position: absolute;
  inset: 0;
  opacity: 0.02;
  background-image:
    radial-gradient(circle at 20% 30%, white 1px, transparent 1px),
    radial-gradient(circle at 80% 70%, white 1px, transparent 1px);
  background-size: 60px 60px, 80px 80px;
}

.cta-glow {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
}
.cta-glow-1 {
  top: -80px; right: -80px;
  width: 350px; height: 350px;
  background: radial-gradient(circle, rgba(200,119,58,0.1), transparent 70%);
}
.cta-glow-2 {
  bottom: -100px; left: -60px;
  width: 300px; height: 300px;
  background: radial-gradient(circle, rgba(200,119,58,0.06), transparent 70%);
}

.cta-inner {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 4rem;
}

.cta-label {
  font-size: 0.7rem;
  font-weight: 700;
  color: #e8a76c;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  margin-bottom: 0.75rem;
}

.cta-content h2 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.8rem, 3vw, 2.4rem);
  color: #f5d9b8;
  margin-bottom: 0.75rem;
  line-height: 1.25;
}
.cta-content p {
  color: #a08060;
  font-size: 0.95rem;
  margin-bottom: 2rem;
  line-height: 1.65;
  max-width: 440px;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
  margin-bottom: 2rem;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  color: #d4b896;
  font-size: 0.9rem;
  transition: color 0.3s, transform 0.3s;
  cursor: default;
}
.contact-item:hover {
  color: #f0d5b0;
  transform: translateX(6px);
}

.contact-icon {
  width: 36px; height: 36px;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.06);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  flex-shrink: 0;
  transition: all 0.3s;
}
.contact-item:hover .contact-icon {
  background: rgba(200,119,58,0.15);
  border-color: rgba(200,119,58,0.2);
}

.cta-btn-row {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.cta-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.85rem 2rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
  cursor: pointer;
}

.cta-btn:first-child {
  background: linear-gradient(135deg, #c8773a, #e8a76c);
  color: #fff;
  box-shadow: 0 4px 20px rgba(200,119,58,0.3);
}
.cta-btn:first-child:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 32px rgba(200,119,58,0.4);
}

.cta-btn.outline {
  background: transparent;
  color: #e8a76c;
  border: 1px solid rgba(232,167,108,0.3);
}
.cta-btn.outline:hover {
  background: rgba(232,167,108,0.1);
  border-color: rgba(232,167,108,0.5);
  transform: translateY(-3px);
}

.cta-visual { flex-shrink: 0; }

.cta-img-wrap {
  position: relative;
  width: 280px; height: 380px;
  border-radius: 24px;
  overflow: hidden;
  animation: pulseGlow 4s ease-in-out infinite;
}
.cta-img-wrap img {
  width: 100%; height: 100%;
  object-fit: cover;
  transition: transform 7s cubic-bezier(0.22, 1, 0.36, 1);
}
.cta-img-wrap:hover img { transform: scale(1.08); }

.cta-img-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, transparent 50%, rgba(26,14,6,0.45) 100%);
}

.cta-img-badge {
  position: absolute;
  bottom: 20px; left: 50%;
  transform: translateX(-50%);
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(10px);
  padding: 0.6rem 1.3rem;
  border-radius: 50px;
  font-size: 0.8rem;
  font-weight: 600;
  color: #2d1b0e;
  box-shadow: 0 4px 16px rgba(0,0,0,0.15);
  white-space: nowrap;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .about-grid { gap: 3.5rem; }
  .mission-layout { gap: 3rem; }
}

@media (max-width: 900px) {
  .offers-grid, .why-grid, .values-grid, .team-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .mission-layout {
    grid-template-columns: 1fr;
    gap: 2.5rem;
  }
  .mission-images-col {
    position: static;
  }
  .mission-img-stack {
    flex-direction: row;
  }
  .mission-img { flex: 1; }
  .mission-img img { height: 180px; }
}

@media (max-width: 768px) {
  .about-hero { min-height: 85vh; }
  .hero-img-1 { width: 55%; clip-path: polygon(0 0, 100% 0, 88% 100%, 0 100%); }
  .hero-img-2 { left: 42%; width: 45%; }
  .hero-img-3 { display: none; }

  .about-section { padding: 5rem 0; }
  .section-inner { padding: 0 1.25rem; }

  .about-grid {
    grid-template-columns: 1fr;
    gap: 3rem;
  }
  .about-visual { order: -1; }

  .visual-stack { max-width: 340px; margin: 0 auto; }
  .visual-img-main img { height: 300px; }
  .visual-img-float { width: 110px; height: 110px; bottom: -18px; right: -12px; }
  .visual-since-badge { top: -10px; left: -10px; padding: 0.5rem 0.8rem; }
  .since-year { font-size: 1.1rem; }

  .offers-grid, .why-grid, .values-grid, .team-grid {
    grid-template-columns: 1fr;
    max-width: 400px;
    margin: 0 auto;
  }

  .mission-img-stack { flex-direction: column; }
  .mission-img img { height: 200px; }

  .cta-inner { flex-direction: column; text-align: center; }
  .cta-content p { margin-left: auto; margin-right: auto; }
  .contact-info { align-items: center; }
  .contact-item { width: 100%; justify-content: center; }
  .contact-item:hover { transform: translateX(0) translateY(-2px); }
  .cta-btn-row { justify-content: center; }

  .cta-img-wrap { width: 220px; height: 300px; }
  .gallery-item { width: 260px; height: 180px; }
  .gallery-label { display: none; }
  .hero-scroll-cue { display: none; }
}

@media (max-width: 480px) {
  .about-title { font-size: 2rem; }
  .about-badge { font-size: 0.62rem; padding: 0.45rem 1rem; }
  .hero-cta-row { flex-direction: column; align-items: center; }
  .hero-btn { width: 100%; max-width: 260px; justify-content: center; }

  .story-stats { flex-direction: column; }
  .stat-item:not(:last-child)::after {
    right: 20%; top: auto; bottom: 0;
    width: 50%; height: 1px;
    background: linear-gradient(90deg, transparent, #e8d5c0, transparent);
  }

  .mission-pillars { flex-direction: column; }

  .cta-btn-row { flex-direction: column; align-items: center; }
  .cta-btn { width: 100%; max-width: 260px; justify-content: center; }
}
</style>