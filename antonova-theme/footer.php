<?php
if (!defined('ABSPATH')) {
    exit;
}

$antonova_footer_home = home_url('/');
?>
  <footer>
    <div class="footer-identity">
      <a class="brand footer-brand" href="<?php echo esc_url($antonova_footer_home); ?>#top"><span class="brand-logo-wrap"><img class="brand-logo brand-logo-footer" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-720.webp" srcset="<?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-360.webp 360w, <?php echo esc_url(get_template_directory_uri()); ?>/assets/optimized/1a6376d9cc69-720.webp 720w" sizes="(max-width: 700px) 170px, 230px" width="720" height="240" decoding="async" alt="Oksana Antonova — Art of Desserts" loading="lazy"></span></a>
      <p><?php echo esc_html(antonova_content('antonova_city', 'Домашняя кондитерская · Москва')); ?></p>
      <a class="footer-phone" href="tel:<?php echo esc_attr(antonova_content('antonova_phone_tel', '+79647281844')); ?>"><?php echo esc_html(antonova_content('antonova_phone_display', '+7 964 728-18-44')); ?></a>
    </div>
    <div class="footer-socials" aria-label="Социальные сети">
      <a class="social-link social-telegram" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21.8 3.3 18.7 20c-.2 1.2-.9 1.5-1.9.9l-4.7-3.5-2.3 2.2c-.3.3-.5.5-1 .5l.3-4.8 8.8-7.9c.4-.3-.1-.5-.6-.2L6.5 14l-4.7-1.5c-1-.3-1-1 .2-1.5L20.3 4c.9-.3 1.7.2 1.5-.7Z"/></svg>Telegram <b>↗</b></a>
      <a class="social-link social-instagram" href="<?php echo esc_url(antonova_content('antonova_instagram_url', 'https://www.instagram.com/_____antonova_____')); ?>" target="_blank" rel="noopener"><svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4.2"/><circle class="icon-dot" cx="17.5" cy="6.8" r="1"/></svg>Instagram <b>↗</b></a>
      <a class="social-link social-vk" href="<?php echo esc_url(antonova_content('antonova_vk_candy_url', 'https://vk.ru/candy_chef_aov')); ?>" target="_blank" rel="noopener"><svg class="social-icon social-icon-vk" viewBox="0 0 24 24" aria-hidden="true"><path d="M3.4 6.8h3.4c.2 4 2 5.7 3.4 6.1V6.8h3.2v3.5c1.4-.1 2.9-1.7 3.4-3.5H20c-.4 2.2-2.2 3.8-3.5 4.5 1.3.6 3.4 2 4.1 4.8h-3.5c-.6-1.7-2-3-3.7-3.2v3.2H13C6.2 16.1 3.7 11.6 3.4 6.8Z"/></svg>Candy Chef <b>↗</b></a>
      <a class="social-link social-vk" href="<?php echo esc_url(antonova_content('antonova_vk_oksana_url', 'https://vk.com/aov_antonovaoksana')); ?>" target="_blank" rel="noopener"><svg class="social-icon social-icon-vk" viewBox="0 0 24 24" aria-hidden="true"><path d="M3.4 6.8h3.4c.2 4 2 5.7 3.4 6.1V6.8h3.2v3.5c1.4-.1 2.9-1.7 3.4-3.5H20c-.4 2.2-2.2 3.8-3.5 4.5 1.3.6 3.4 2 4.1 4.8h-3.5c-.6-1.7-2-3-3.7-3.2v3.2H13C6.2 16.1 3.7 11.6 3.4 6.8Z"/></svg>Oksana <b>↗</b></a>
      <a class="social-link social-threads" href="<?php echo esc_url(antonova_content('antonova_threads_url', 'https://www.threads.com/@_antonova_')); ?>" target="_blank" rel="noopener"><svg class="social-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M17.8 10.7c-.1-3.3-2-5.2-5.4-5.4-2.5-.1-4.5 1.1-5.4 3.2l2.1.9c.6-1.3 1.7-1.9 3.2-1.9 1.8.1 2.8.9 3 2.5a8.3 8.3 0 0 0-2.8-.5c-3 0-5.1 1.5-5.1 3.8 0 2.2 1.8 3.7 4.3 3.7 2 0 3.4-.9 4.3-2.4-.6 3-2.5 4.6-5.4 4.4-4.4-.2-7-3.1-6.8-7.5.2-4.7 3.3-7.5 8.1-7.4 4.8.1 7.8 3.1 7.9 7.8.1 4.2-1.9 7-5.2 8.4l.9 2.1c4.2-1.8 6.6-5.5 6.5-10.6-.1-6-4-9.8-10-10-6-.1-10.1 3.6-10.4 9.6-.2 5.7 3.3 9.5 8.8 9.8 4.8.3 7.5-2.5 7.7-7.5.8.5 1.4 1.1 1.8 1.7l1.8-1.4a8.6 8.6 0 0 0-3.9-3.3Zm-6 4.2c-1.2 0-2-.6-2-1.6 0-1.1 1-1.7 2.7-1.7 1 0 2 .2 2.8.6-.4 1.7-1.6 2.7-3.5 2.7Z"/></svg>Threads <b>↗</b></a>
    </div>
    <div class="footer-privacy"><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Политика конфиденциальности</a></div>
  </footer>
  <?php if (is_front_page()) : ?>
    <a class="mobile-sticky" href="<?php echo esc_url(antonova_content('antonova_telegram_url', 'https://t.me/antonovaov')); ?>" target="_blank" rel="noopener"><span>➤</span> Заказать в Telegram</a>
  <?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
