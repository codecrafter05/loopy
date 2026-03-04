<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Loopy — Donuts & Coffee Menu</title>
  <link rel="icon" href="{{ asset('assets/imgs/favicon.png') }}" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>

  <header>
    <div class="wrap">
      <div class="topbar">
        <a href="#" class="brand" aria-label="Loopy">
          <img src="{{ asset('assets/imgs/logo3.png') }}" alt="Loopy" class="brandLogo" width="120" height="48" />
        </a>
      </div>
    </div>
  </header>

  <div class="catStripSpacer" id="catStripSpacer" aria-hidden="true"></div>

  <nav class="catStrip" id="catStrip" aria-label="Menu categories">
    <div class="wrap">
      <div class="cats" role="tablist">
        @foreach($categories as $index => $cat)
        <button class="catBtn" data-cat="{{ $cat->slug }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" role="tab">{{ $cat->name }}</button>
        @endforeach
      </div>
    </div>
  </nav>

  <main class="wrap" id="menuRoot" aria-label="Menu">
    @forelse($categories as $cat)
    <section id="sec-{{ $cat->slug }}" class="menuSection">
      <div class="sectionHead">
        <div>
          <h3>{{ $cat->name }}</h3>
        </div>
        <p>{{ $cat->menuItems->count() }} {{ Str::plural('item', $cat->menuItems->count()) }}</p>
      </div>
      <div class="grid">
        @foreach($cat->menuItems as $item)
        @php
          $imgUrl = $item->image_url ?? asset('assets/imgs/image.png');
        @endphp
        <article class="card" data-cat="{{ $cat->name }}" data-name="{{ e($item->name) }}" data-desc="{{ e($item->description ?? '') }}" data-img="{{ $imgUrl }}" data-prices='{{ json_encode($item->prices ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}'>
          <div class="media">
            <img src="{{ $imgUrl }}" alt="{{ e($item->name) }}" loading="lazy" decoding="async" width="280" height="200" />
          </div>
          <div class="content">
            <div class="topline"><h4 class="name">{{ $item->name }}</h4></div>
            @if($item->description)
            <p class="desc">{{ $item->description }}</p>
            @endif
            <div class="prices">
              <div class="priceChips">
                @foreach($item->prices ?? [] as $p)
                <span class="chip">
                  @if(!empty($p['label'] ?? null))
                  <small>{{ e($p['label']) }}</small> {{ e($p['value']) }}
                  @else
                  {{ e($p['value']) }}
                  @endif
                </span>
                @endforeach
              </div>
            </div>
          </div>
        </article>
        @endforeach
      </div>
    </section>
    @empty
    <section class="menuSection">
      <div class="sectionHead">
        <h3>Menu</h3>
      </div>
      <p style="text-align:center; padding:2rem; color:#666;">No menu items yet. Add categories and products from the <a href="{{ url('/admin') }}" style="color:var(--primary, #c9a227);">admin panel</a>.</p>
    </section>
    @endforelse
  </main>

  <div class="modalBack" id="modalBack" role="dialog" aria-modal="true" aria-label="Item details" data-open="false">
    <div class="modal" role="document">
      <div class="modalTop">
        <b id="modalCat">Category</b>
        <button class="close" id="closeModal" aria-label="Close">✕</button>
      </div>
      <div class="modalBody">
        <div class="modalMedia">
          <img id="modalImg" alt="" decoding="async" />
        </div>
        <div class="modalInfo">
          <h4 id="modalTitle">Item</h4>
          <p id="modalDesc">Description</p>
          <div class="prices" id="modalPricesWrap">
            <div class="priceChips" id="modalPrices"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      var header = document.querySelector("header");
      var catStrip = document.getElementById("catStrip");
      var spacer = document.getElementById("catStripSpacer");
      function updateStrip(){
        if(!header || !catStrip || !spacer) return;
        spacer.style.height = catStrip.offsetHeight + "px";
        catStrip.style.top = (window.scrollY > header.offsetHeight ? 0 : header.offsetHeight) + "px";
      }
      updateStrip();
      window.addEventListener("scroll", updateStrip, { passive: true });
      window.addEventListener("resize", updateStrip);

      var sections = document.querySelectorAll(".menuSection");
      var catBtns = document.querySelectorAll(".catBtn");
      var catsEl = document.querySelector(".cats");
      function updateCatFromScroll(){
        if(!sections.length || !catBtns.length) return;
        var y = 180;
        var current = null;
        for(var i = 0; i < sections.length; i++){
          var t = sections[i].getBoundingClientRect().top;
          if(t <= y && (current === null || t > current.getBoundingClientRect().top)) current = sections[i];
        }
        if(!current) current = sections[0];
        var catId = current.id ? current.id.replace("sec-","") : (catBtns[0] && catBtns[0].dataset.cat) || "";
        var activeBtn = null;
        catBtns.forEach(function(b){
          var isActive = b.dataset.cat === catId;
          b.setAttribute("aria-current", isActive ? "true" : "false");
          if(isActive) activeBtn = b;
        });
        if(activeBtn && catsEl) activeBtn.scrollIntoView({ inline: "center", block: "nearest", behavior: "smooth" });
      }
      window.addEventListener("scroll", updateCatFromScroll, { passive: true });
      window.addEventListener("resize", updateCatFromScroll);
      updateCatFromScroll();

      var root = document.getElementById("menuRoot");
      var searchInput = document.getElementById("searchInput");
      if(searchInput) searchInput.addEventListener("input", function(){
        var q = this.value.trim().toLowerCase();
        root.querySelectorAll(".card").forEach(function(c){
          var sec = c.closest(".menuSection");
          var text = (c.dataset.name + " " + c.dataset.desc + " " + (sec ? sec.querySelector("h3").textContent : "")).toLowerCase();
          c.style.display = q && text.indexOf(q) === -1 ? "none" : "";
        });
        root.querySelectorAll(".menuSection").forEach(function(s){
          s.style.display = s.querySelector(".card:not([style*='none'])") ? "" : "none";
        });
      });

      document.querySelectorAll(".catBtn").forEach(function(btn){
        btn.addEventListener("click", function(){
          document.querySelectorAll(".catBtn").forEach(function(b){ b.setAttribute("aria-current", b === btn ? "true" : "false"); });
          if(catsEl) btn.scrollIntoView({ inline: "center", block: "nearest", behavior: "smooth" });
          if(searchInput) searchInput.value = "";
          root.querySelectorAll(".menuSection, .card").forEach(function(el){ el.style.display = ""; });
          var t = document.getElementById("sec-" + btn.dataset.cat);
          if(t) t.scrollIntoView({ behavior: "smooth", block: "start" });
        });
      });

      function renderPrice(p){
        if(!p || p.value == null) return "";
        var val = String(p.value || "");
        if(p.label) return "<span class=\"chip\"><small>" + p.label + "</small> " + val + "</span>";
        return "<span class=\"chip\">" + val + "</span>";
      }

      root.addEventListener("click", function(e){
        var media = e.target.closest(".media");
        if(!media) return;
        var card = media.closest(".card");
        if(!card) return;
        var prices = [];
        try {
          var raw = card.getAttribute("data-prices") || "[]";
          prices = typeof raw === "string" ? JSON.parse(raw) : (Array.isArray(raw) ? raw : []);
        } catch(err) { prices = []; }
        document.getElementById("modalCat").textContent = card.dataset.cat;
        document.getElementById("modalImg").src = card.dataset.img;
        document.getElementById("modalImg").alt = card.dataset.name;
        document.getElementById("modalTitle").textContent = card.dataset.name;
        document.getElementById("modalDesc").textContent = card.dataset.desc;
        var html = (Array.isArray(prices) ? prices : []).map(renderPrice).filter(Boolean).join("");
        document.getElementById("modalPrices").innerHTML = html || "";
        document.getElementById("modalPricesWrap").style.display = html ? "" : "none";
        document.getElementById("modalBack").setAttribute("data-open", "true");
        document.body.style.overflow = "hidden";
      });

      function closeModal(){
        document.getElementById("modalBack").setAttribute("data-open", "false");
        document.body.style.overflow = "";
      }
      document.getElementById("closeModal").addEventListener("click", closeModal);
      document.getElementById("modalBack").addEventListener("click", function(e){ if(e.target === this) closeModal(); });
      window.addEventListener("keydown", function(e){ if(e.key === "Escape") closeModal(); });
    })();
  </script>
</body>
</html>
