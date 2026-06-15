<?php get_header(); ?>

<?php
  $c_zh    = mth_text('company_zh', '明德行國際有限公司');
  $c_en    = mth_text('contact_info_subtitle', 'Meng Tak Hong International Co., Ltd.');
  $c_ph1   = mth_text('company_phone1', '+853 28415128');
  $c_ph2   = mth_text('company_phone2', '+853 28584838');
  $c_mail  = mth_text('company_email', 'info@mengtakhong.com');
  $tel1 = preg_replace('/[^\d+]/', '', $c_ph1);
  $tel2 = preg_replace('/[^\d+]/', '', $c_ph2);
  $show_wa = strtolower(mth_text('contact_show_wa', 'no')) === 'yes';
?>

<div class="about-hero">
  <div class="eyebrow" translate="no"><?php echo esc_html(mth_text('contact_eyebrow', 'Contact Us')); ?></div>
  <h1 style="font-size:2.3rem;"><?php echo esc_html(mth_text('contact_title_1', '聯絡')); ?><span><?php echo esc_html(mth_text('contact_title_2', '我們')); ?></span></h1>
  <p><?php echo esc_html(mth_text('contact_subtitle', '歡迎 B2B 客戶查詢批發報價及補貨事宜')); ?></p>
</div>

<div class="contact-grid">
  <div class="contact-info">
    <h2><?php echo esc_html(mth_text('contact_info_title', '聯絡資料')); ?></h2>
    <div class="sub"><?php echo esc_html($c_en); ?></div>

    <div class="contact-row">
      <div class="icon">&#128205;</div>
      <div class="detail">
        <div class="label"><?php echo esc_html(mth_text('contact_addr_label', '地址')); ?></div>
        <div class="val"><?php echo nl2br(esc_html(mth_text('contact_addr_val', '澳門黑沙環慕拉士大馬路195號 南嶺工業大廈4樓F座'))); ?></div>
      </div>
    </div>
    <div class="contact-row">
      <div class="icon">&#128222;</div>
      <div class="detail">
        <div class="label"><?php echo esc_html(mth_text('contact_phone_label', '電話')); ?></div>
        <div class="val">
          <a href="tel:<?php echo esc_attr($tel1); ?>"><?php echo esc_html($c_ph1); ?></a><?php if ($c_ph2): ?> /
          <a href="tel:<?php echo esc_attr($tel2); ?>"><?php echo esc_html($c_ph2); ?></a><?php endif; ?>
        </div>
      </div>
    </div>
    <div class="contact-row">
      <div class="icon">&#9993;</div>
      <div class="detail">
        <div class="label"><?php echo esc_html(mth_text('contact_email_label', '電郵')); ?></div>
        <div class="val"><a href="mailto:<?php echo esc_attr($c_mail); ?>"><?php echo esc_html($c_mail); ?></a></div>
      </div>
    </div>
    <div class="contact-row">
      <div class="icon">&#128336;</div>
      <div class="detail">
        <div class="label"><?php echo esc_html(mth_text('contact_hours_label', '辦公時間')); ?></div>
        <div class="val"><?php echo esc_html(mth_text('contact_hours_val', '週一至週六 9:00 – 18:00')); ?></div>
      </div>
    </div>

    <div class="social-row" style="margin-top:32px;">
      <?php if ($show_wa): ?>
      <a href="<?php echo esc_url(mth_text('contact_wa_url', 'https://wa.me/85366687448')); ?>" class="social-btn wa" target="_blank">WhatsApp</a>
      <?php endif; ?>
      <a href="<?php echo esc_url(mth_text('social_fb_url', 'https://www.facebook.com/profile.php?id=61555744448402')); ?>" class="social-btn fb" target="_blank">Facebook</a>
      <a href="<?php echo esc_url(mth_text('social_ig_url', 'https://www.instagram.com/mengtakhong.mo/')); ?>" class="social-btn ig" target="_blank">Instagram</a>
    </div>
  </div>

  <div class="map-wrap">
    <iframe
      src="https://maps.google.com/maps?q=6H43%2BGC8+Macau&output=embed&z=18&hl=zh-TW"
      width="100%" height="450" style="border:0;border-radius:12px;" allowfullscreen="" loading="lazy"
      title="<?php echo esc_attr($c_zh); ?>"></iframe>
    <div style="margin-top:10px;text-align:center;">
      <a href="https://maps.google.com/maps?q=6H43%2BGC8+Macau" target="_blank" rel="noopener"
         style="font-size:.82rem;color:#D4AF37;text-decoration:none;border:1px solid rgba(212,175,55,.4);padding:6px 16px;border-radius:20px;display:inline-block;margin-top:4px;">
        &#128205; <?php echo esc_html(mth_text('contact_map_open_label', '在 Google Maps 開啟')); ?>
      </a>
    </div>
  </div>
</div>

<?php get_footer(); ?>
