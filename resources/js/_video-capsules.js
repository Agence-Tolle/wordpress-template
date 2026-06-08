document.querySelectorAll('[data-video-grid]').forEach(grid => {
    const capsules = [...grid.querySelectorAll('[data-capsule-index]')];
    const lightbox = document.querySelector('[data-lightbox]');

    if (!lightbox) return;

    const lightboxVideo  = lightbox.querySelector('[data-lightbox-video]');
    const btnPrev        = lightbox.querySelector('[data-lightbox-prev]');
    const btnNext        = lightbox.querySelector('[data-lightbox-next]');
    const btnClose       = lightbox.querySelector('[data-lightbox-close]');
    let currentIndex     = 0;

    // --- Hover : lecture en aperçu ---

    capsules.forEach(capsule => {
        const video  = capsule.querySelector('[data-hover-video]');
        const src    = video?.dataset.videoSrc;

        if (!video || !src) return;

        capsule.addEventListener('mouseenter', () => {
            // Charge la source seulement au premier survol
            if (!video.src) {
                video.src = src;
                video.load();
            }
            video.play().catch(() => {});
        });

        capsule.addEventListener('mouseleave', () => {
            video.pause();
        });
    });

    // --- Lightbox ---

    function openLightbox(index) {
        const capsule  = capsules[index];
        const videoUrl = capsule?.dataset.videoUrl;

        if (!videoUrl) return;

        currentIndex         = index;
        lightboxVideo.src    = videoUrl;
        lightboxVideo.load();
        lightboxVideo.play().catch(() => {});

        lightbox.hidden      = false;
        lightbox.removeAttribute('hidden');
        requestAnimationFrame(() => {
            lightbox.classList.replace('opacity-0', 'opacity-100');
            lightbox.classList.replace('pointer-events-none', 'pointer-events-auto');
        });

        updateNavButtons();
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        lightboxVideo.pause();
        lightboxVideo.src = '';

        lightbox.classList.replace('opacity-100', 'opacity-0');
        lightbox.classList.replace('pointer-events-auto', 'pointer-events-none');

        lightbox.addEventListener('transitionend', () => {
            lightbox.hidden = true;
        }, { once: true });

        document.body.style.overflow = '';
    }

    function goTo(index) {
        lightboxVideo.pause();
        openLightbox(index);
    }

    function updateNavButtons() {
        btnPrev.disabled = currentIndex === 0;
        btnNext.disabled = currentIndex === capsules.length - 1;
    }

    // Déclencheurs
    grid.addEventListener('click', e => {
        const trigger = e.target.closest('[data-lightbox-trigger]');
        if (trigger) {
            e.stopPropagation();
            openLightbox(Number(trigger.dataset.lightboxTrigger));
        }
    });

    btnPrev.addEventListener('click', () => goTo(currentIndex - 1));
    btnNext.addEventListener('click', () => goTo(currentIndex + 1));
    btnClose.addEventListener('click', closeLightbox);

    // Fermeture sur clic sur le fond
    lightbox.addEventListener('click', e => {
        if (e.target === lightbox) closeLightbox();
    });

    // Navigation clavier
    document.addEventListener('keydown', e => {
        if (lightbox.hidden) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft' && currentIndex > 0) goTo(currentIndex - 1);
        if (e.key === 'ArrowRight' && currentIndex < capsules.length - 1) goTo(currentIndex + 1);
    });
});
