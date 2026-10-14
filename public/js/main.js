(() => {
  const header = document.querySelector("[data-site-header]");
  if (header) {
    const onScroll = () => {
      header.classList.toggle("is-scrolled", window.scrollY > 8);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  const menuToggle = document.querySelector("[data-menu-toggle]");
  const mobileNav = document.querySelector("[data-mobile-nav]");

  if (menuToggle && mobileNav) {
    menuToggle.addEventListener("click", () => {
      const open = mobileNav.classList.toggle("is-open");
      menuToggle.classList.toggle("is-open", open);
      menuToggle.setAttribute("aria-expanded", open ? "true" : "false");
    });

    mobileNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        mobileNav.classList.remove("is-open");
        menuToggle.classList.remove("is-open");
        menuToggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  const filterButtons = document.querySelectorAll("[data-filter]");
  const projectCards = document.querySelectorAll("[data-category]");

  if (filterButtons.length && projectCards.length) {
    filterButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const filter = button.getAttribute("data-filter") || "all";

        filterButtons.forEach((btn) => btn.classList.remove("is-active"));
        button.classList.add("is-active");

        projectCards.forEach((card) => {
          const category = card.getAttribute("data-category");
          const show = filter === "all" || category === filter;
          card.classList.toggle("is-hidden", !show);
        });
      });
    });
  }

  const form = document.querySelector("[data-order-form]");
  if (form) {
    const status = form.querySelector("[data-form-status]");
    const submitBtn = form.querySelector("[type='submit']");

    form.addEventListener("submit", async (event) => {
      event.preventDefault();

      form.querySelectorAll(".error").forEach((el) => {
        el.textContent = "";
      });

      if (status) {
        status.className = "form-status";
        status.textContent = "";
      }

      const formData = new FormData(form);
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = "Yuborilmoqda...";
      }

      try {
        const response = await fetch("/api/contact.php", {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          if (result.errors) {
            Object.entries(result.errors).forEach(([field, message]) => {
              const errorEl = form.querySelector(`[data-error='${field}']`);
              if (errorEl) errorEl.textContent = message;
            });
          }

          if (status) {
            status.className = "form-status is-error";
            status.textContent = result.message || "Xatolik yuz berdi. Qayta urinib ko‘ring.";
          }
          return;
        }

        form.reset();
        if (status) {
          status.className = "form-status is-success";
          status.textContent = result.message || "Buyurtmangiz muvaffaqiyatli qabul qilindi.";
        }
      } catch (error) {
        if (status) {
          status.className = "form-status is-error";
          status.textContent = "Tarmoq xatosi. Keyinroq urinib ko‘ring.";
        }
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = "Buyurtma yuborish";
        }
      }
    });
  }

  const revealItems = document.querySelectorAll(".reveal");
  if (revealItems.length && "IntersectionObserver" in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12 }
    );

    revealItems.forEach((item) => observer.observe(item));
  } else {
    revealItems.forEach((item) => item.classList.add("is-visible"));
  }
})();
