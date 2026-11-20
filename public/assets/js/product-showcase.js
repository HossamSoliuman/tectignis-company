/*
 * Products page: switch a product's main preview from its thumbnails, open
 * its screenshots in the full-screen viewer, and play videos in place.
 */
(function () {
    'use strict';

    var isRtl = document.documentElement.getAttribute('dir') === 'rtl';
    var dialog = document.querySelector('[data-lightbox]');

    /* ---------- Screenshot viewer ---------- */
    var viewer = (function () {
        if (!dialog || typeof dialog.showModal !== 'function') {
            return null;
        }

        var image = dialog.querySelector('[data-lightbox-image]');
        var caption = dialog.querySelector('[data-lightbox-caption]');
        var count = dialog.querySelector('[data-lightbox-count]');
        var prev = dialog.querySelector('[data-lightbox-prev]');
        var next = dialog.querySelector('[data-lightbox-next]');
        var items = [];
        var index = 0;
        var opener = null;
        var touchStartX = null;

        function preload(position) {
            var item = items[(position + items.length) % items.length];

            if (item) {
                new Image().src = item.src;
            }
        }

        function show(position) {
            index = (position + items.length) % items.length;
            image.src = items[index].src;
            image.alt = items[index].alt;
            caption.textContent = items[index].alt;
            count.textContent = items.length > 1 ? (index + 1) + ' / ' + items.length : '';

            if (items.length > 1) {
                preload(index + 1);
                preload(index - 1);
            }
        }

        function open(gallery, position, trigger) {
            items = gallery;
            opener = trigger || null;
            prev.hidden = next.hidden = items.length < 2;
            show(position);
            document.documentElement.style.overflow = 'hidden';
            dialog.showModal();
        }

        dialog.addEventListener('close', function () {
            document.documentElement.style.overflow = '';
            image.removeAttribute('src');

            if (opener) {
                opener.focus();
            }
        });

        dialog.querySelector('[data-lightbox-close]').addEventListener('click', function () {
            dialog.close();
        });
        prev.addEventListener('click', function () {
            show(index - 1);
        });
        next.addEventListener('click', function () {
            show(index + 1);
        });

        // A click on the dark area around the screenshot closes the viewer.
        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) {
                dialog.close();
            }
        });

        dialog.addEventListener('keydown', function (event) {
            if (items.length < 2 || (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight')) {
                return;
            }

            event.preventDefault();
            show(index + ((event.key === 'ArrowRight') !== isRtl ? 1 : -1));
        });

        image.addEventListener('touchstart', function (event) {
            touchStartX = event.changedTouches[0].clientX;
        }, { passive: true });

        image.addEventListener('touchend', function (event) {
            if (touchStartX === null || items.length < 2) {
                return;
            }

            var distance = event.changedTouches[0].clientX - touchStartX;
            touchStartX = null;

            if (Math.abs(distance) > 50) {
                show(index + ((distance < 0) !== isRtl ? 1 : -1));
            }
        });

        return { open: open };
    })();

    /* ---------- Product showcases ---------- */
    document.querySelectorAll('[data-showcase]').forEach(function (section) {
        var gallery = [];

        try {
            gallery = JSON.parse(section.getAttribute('data-gallery') || '[]');
        } catch (error) {
            gallery = [];
        }

        var main = section.querySelector('[data-showcase-main]');
        var opener = section.querySelector('[data-lightbox-open]');
        var thumbs = section.querySelectorAll('[data-thumb]');
        var current = 0;

        thumbs.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var position = parseInt(thumb.getAttribute('data-thumb'), 10);
                var item = gallery[position];

                if (!item || !main || position === current) {
                    return;
                }

                current = position;
                main.classList.add('is-loading');
                main.onload = function () {
                    main.classList.remove('is-loading');
                };
                main.src = item.src;
                main.alt = item.alt;

                thumbs.forEach(function (other) {
                    var active = other === thumb;
                    other.classList.toggle('is-active', active);
                    other.setAttribute('aria-pressed', active ? 'true' : 'false');
                });
            });
        });

        if (opener && gallery.length) {
            opener.addEventListener('click', function () {
                if (viewer) {
                    viewer.open(gallery, current, opener);
                } else {
                    window.open(gallery[current].src, '_blank', 'noopener');
                }
            });
        }

        var playFirst = section.querySelector('[data-play-first]');

        if (playFirst) {
            playFirst.addEventListener('click', function (event) {
                var poster = section.querySelector('.tx-video__poster');

                if (!poster) {
                    return;
                }

                event.preventDefault();
                poster.closest('.tx-showcase__videos').scrollIntoView({ behavior: 'smooth', block: 'start' });
                poster.click();
            });
        }
    });

    /* ---------- Videos: load the player only when asked to play ---------- */
    document.querySelectorAll('[data-video]').forEach(function (frame) {
        var poster = frame.querySelector('.tx-video__poster');

        if (!poster) {
            return;
        }

        poster.addEventListener('click', function () {
            var type = frame.getAttribute('data-video');
            var player;

            if (type === 'file') {
                player = document.createElement('video');
                player.src = frame.getAttribute('data-src');
                player.controls = true;
                player.autoplay = true;
                player.playsInline = true;
                player.setAttribute('preload', 'auto');
            } else {
                player = document.createElement('iframe');
                player.src = frame.getAttribute('data-src');
                player.title = frame.getAttribute('data-title') || '';
                player.allow = 'autoplay; encrypted-media; fullscreen; picture-in-picture';
                player.allowFullscreen = true;
                player.referrerPolicy = 'strict-origin-when-cross-origin';
            }

            frame.replaceChild(player, poster);
            player.focus();

            // Only one video plays at a time.
            document.querySelectorAll('.tx-video__frame > video').forEach(function (other) {
                if (other !== player && !other.paused) {
                    other.pause();
                }
            });
        });
    });
})();
