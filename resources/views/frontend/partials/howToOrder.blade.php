<section class="section how-order-section">
    <!-- badge -->
    <div class="badge">How to Order</div>

    <h1>It’s as easy as this</h1>

    <div class="cards">

        <div class="card">
            <div class="icon">
                <img src="{{ asset('frontend/images/location.png') }}"
                     alt="Provide Location"
                     class="icon-img">
            </div>
            <h3>Provide your location</h3>
            <p>Fill out your address and search nearby restaurants</p>
        </div>

        <div class="card">
            <div class="icon">
                <img src="{{ asset('frontend/images/dish.png') }}"
                     alt="Choose Food"
                     class="icon-img">
            </div>
            <h3>Choose your restaurant & food</h3>
            <p>Browse menus from a wide range of cuisines</p>
        </div>

        <div class="card">
            <div class="icon">
                <img src="{{ asset('frontend/images/fast-food.png') }}"
                     alt="Fast Delivery"
                     class="icon-img">
            </div>
            <h3>Pay & get your food</h3>
            <p>Pay securely and enjoy fast delivery</p>
        </div>

    </div>
</section>

<style>
.how-order-section {
  text-align: center;
  padding: 90px 24px;
  max-width: 1300px;
  margin: 0 auto;
  font-family: 'Poppins', sans-serif;
}

/* BADGE */
.badge {
  display: inline-block;
  padding: 8px 28px;
  border: 2px solid #a78bfa;
  color: #7c3aed;
  border-radius: 50px;
  font-size: 15px;
  font-weight: 500;
  background: #fff;
  margin-bottom: 24px;
}

/* HEADING */
.how-order-section h1 {
  font-size: 42px;
  font-weight: 600;
  color: #1f1f1f;
  margin-bottom: 60px;
  letter-spacing: -0.4px;
}

/* CARD WRAPPER */
.cards {
  display: flex;
  justify-content: center;
  gap: 40px;
  flex-wrap: wrap;
}

/* CARD */
.card {
  background: linear-gradient(135deg, #efe7ff, #e5d7ff);
  width: 360px;
  padding: 48px 36px;
  border-radius: 16px;
  text-align: left;
  box-shadow: 0 10px 30px rgba(124, 58, 237, 0.12);
  transition: all 0.25s ease;
}

.card:hover {
  transform: translateY(-10px);
  box-shadow: 0 22px 40px rgba(124, 58, 237, 0.25);
}

/* ICON BOX */
.icon {
  width: 70px;
  height: 70px;
  background: linear-gradient(135deg, #7c3aed, #9f67ff);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 26px;
}

/* IMAGE FIX */
.icon-img {
  width: 32px;
  height: 32px;
  object-fit: contain;
  filter: brightness(0) invert(1); /* white icon look */
}

/* CARD TITLE */
.card h3 {
  font-size: 22px;
  font-weight: 600;
  color: #1e1e1e;
  margin-bottom: 10px;
}

/* CARD TEXT */
.card p {
  font-size: 15px;
  color: #555;
  line-height: 1.7;
}

/* RESPONSIVE */
@media (max-width: 992px) {
  .how-order-section h1 {
    font-size: 34px;
  }

  .card {
    width: 100%;
    max-width: 420px;
  }
}
</style>
