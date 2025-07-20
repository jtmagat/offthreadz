<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>OFFTHREADZ</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', 'Segoe UI', sans-serif;
      background-color: #000;
      color: #fff;
      scroll-behavior: smooth;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    /* Hero Section */
    .hero {
      position: relative;
      height: 100vh;
      overflow: hidden;
    }

    .hero-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(35%);
    }

    .hero-text {
      position: absolute;
      top: 50%;
      left: 10%;
      transform: translateY(-50%);
      color: white;
    }

    .hero-text h1 {
      font-size: 3.5rem;
      letter-spacing: 2px;
      margin-bottom: 10px;
      animation: fadeInUp 1s ease forwards;
    }

    .tagline {
      font-size: 1.2rem;
      font-weight: 300;
      font-style: italic;
      letter-spacing: 1px;
      color: #bbb;
      margin-bottom: 20px;
      animation: fadeInUp 1.3s ease forwards;
    }

    .shop-btn {
      padding: 14px 30px;
      background: white;
      color: black;
      border-radius: 6px;
      font-weight: 700;
      font-size: 1rem;
      box-shadow: 0 4px 12px rgba(255, 255, 255, 0.2);
      transition: background 0.3s ease;
      display: inline-block;
      animation: fadeInUp 1.6s ease forwards;
    }

    .shop-btn:hover {
      background: #ddd;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Features Section */
    .features, .culture, .gallery, .newsletter {
      padding: 60px 40px;
      background-color: #0a0a0a;
      text-align: center;
    }

    .features h2, .culture h2, .gallery h2, .newsletter h2 {
      font-size: 2rem;
      margin-bottom: 30px;
      letter-spacing: 1px;
    }

    .feature-grid, .gallery-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 30px;
      max-width: 1000px;
      margin: 0 auto;
    }

    .feature-item, .gallery-item {
      background-color: #111;
      padding: 30px 20px;
      border-radius: 10px;
      transition: transform 0.3s ease;
    }

    .feature-item:hover, .gallery-item:hover {
      transform: translateY(-5px);
    }

    .feature-item h3 {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: #fff;
    }

    .feature-item p {
      font-size: 0.9rem;
      color: #aaa;
    }

    /* Culture Section */
    .culture p {
      max-width: 800px;
      margin: 0 auto;
      color: #bbb;
      line-height: 1.6;
      font-size: 1rem;
    }

    /* Gallery Section */
    .gallery-img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      transition: filter 0.3s ease;
    }

    .gallery-img:hover {
      filter: brightness(1.2);
    }

    /* Newsletter */
    .newsletter input[type="email"] {
      padding: 10px;
      width: 250px;
      max-width: 90%;
      border-radius: 5px;
      border: none;
      margin-right: 10px;
      font-size: 1rem;
    }

    .newsletter button {
      padding: 10px 20px;
      background: white;
      color: black;
      font-weight: 600;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .newsletter button:hover {
      background: #ddd;
    }

    footer {
      text-align: center;
      padding: 20px;
      background: #0a0a0a;
      color: #555;
      font-size: 0.85rem;
    }

    .hero {
  position: relative;
  height: 100vh;
  overflow: hidden;
}

.hero-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(30%);
  transition: transform 8s ease;
  animation: zoomBg 20s infinite alternate;
}

@keyframes zoomBg {
  0% { transform: scale(1); }
  100% { transform: scale(1.1); }
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.4));
  z-index: 1;
}

.hero-text {
  position: absolute;
  top: 50%;
  left: 10%;
  transform: translateY(-50%);
  z-index: 2;
  color: #fff;
}

.hero-text h1 {
  font-size: 4rem;
  letter-spacing: 2px;
  margin-bottom: 15px;
  animation: fadeInUp 1.2s ease forwards;
}

.tagline {
  font-size: 1.2rem;
  font-style: italic;
  color: #ccc;
  animation: fadeInUp 1.5s ease forwards;
}

.shop-btn {
  padding: 14px 30px;
  background: white;
  color: black;
  border-radius: 8px;
  font-weight: 700;
  font-size: 1rem;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
  transition: all 0.3s ease;
  display: inline-block;
  margin-top: 20px;
  animation: fadeInUp 1.8s ease forwards;
}

.shop-btn:hover {
  transform: translateY(-2px);
  background: #eee;
}

/* Glowing effect */
.glow {
  text-shadow: 0 0 8px rgba(255,255,255,0.8), 0 0 20px rgba(255,255,255,0.3);
}

/* Flicker effect for tagline */
@keyframes flicker {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

.flicker {
  animation: flicker 2s infinite;
}

/* Fade in animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

  </style>
</head>
<body>

<!-- Hero -->
<section class="hero">
  <div class="hero-overlay"></div>
  <img src="assets/logooo.jpg" alt="New Drop" class="hero-img">
  <div class="hero-text">
    <h1 class="glow">DROP 03 OUT NOW</h1>
    <div class="tagline flicker">ALL EYEZ ON ME</div>
    <a href="products.php" class="shop-btn">SHOP NOW</a>
  </div>
</section>


  <!-- Features -->
  <section class="features">
    <h2>WHY OFFTHREADZ?</h2>
    <div class="feature-grid">
      <div class="feature-item">
        <h3>Street-Approved Style</h3>
        <p>Curated for the bold. Each drop speaks to culture, rebellion, and raw self-expression.</p>
      </div>
      <div class="feature-item">
        <h3>Limited Drops</h3>
        <p>Once it’s gone, it’s gone. Wear it loud before anyone else can.</p>
      </div>
      <div class="feature-item">
        <h3>Quality Meets Edge</h3>
        <p>Built to last. Designed to disrupt. We don’t do basic — we do different.</p>
      </div>
    </div>
  </section>

  <!-- Culture / About -->
  <section class="culture">
    <h2>OFFTHREADZ CULTURE</h2>
    <p>
      OFFTHREADZ isn’t just clothing. It’s identity, grit, and unapologetic youth. We represent those
      who move differently — who treat the street as runway, and who turn their scars into statements.
      Our community wears confidence, not just cloth.
    </p>
  </section>

<!-- Etiketas & Stickers -->
<section class="gallery">
  <h2>ETIKETAS & STICKERS</h2>
  <div class="gallery-grid">
    <div class="gallery-item">
      <img src="assets/13.png" class="gallery-img" alt="OFFTHREADZ Etiketa (Label)">
    </div>
    <div class="gallery-item">
      <img src="assets/14.png" class="gallery-img" alt="OFFTHREADZ Sticker Design">
    </div>
  </div>
</section>


  <!-- Newsletter -->
  <section class="newsletter">
    <h2>STAY IN THE THREAD</h2>
    <form>
      <input type="email" placeholder="Enter your email">
      <button type="submit">JOIN NOW</button>
    </form>
  </section>

  <footer>
  <p>&copy; 2024 OFFTHREADZ. All rights reserved.</p>
  <p>
  📸 <a href="https://www.instagram.com/offthreadz/" target="_blank" style="color: #fff; font-weight: bold; text-decoration: underline;">
    Instagram @offthreadz
  </a>
</p>
  </p>
</footer>


</body>
</html>
